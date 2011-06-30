<?php
/*
 *  OPEN POWER LIBS <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Dependency;
use Opl\Collector\Collector;
use Opl\Collector\LoaderInterface;
use Opl\Dependency\Exception\LocatorException;
use DomainException;
use OutOfRangeException;

/**
 * The actual Dependency Injection Container implementation. Service locator
 * is responsible for storing and building the new service objects on
 * demand using the predefined rules.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class ServiceLocator extends Locator
{
	/**
	 * The service aliases.
	 * @var array 
	 */
	protected $aliases = array();
	/**
	 * Maps the services to the service containers.
	 * @var array
	 */
	protected $containerServices = array();	
	/**
	 * The configuration provider.
	 * @var Opl\Collector\Collector
	 */
	protected $collector;
	
	/**
	 * Creates the service locator and initializes the configuration
	 * for it.
	 * 
	 * @param Opl\Collector\Collector $collector The configuration collector.
	 */
	public function __construct(Collector $collector)
	{
		$this->collector = $collector;
	} // end __construct();
	
	/**
	 * Registers a new service container and its services within the
	 * locator. If the container implements <tt>Opl\Collector\LoaderInterface</tt>,
	 * the configuration is imported, too.
	 * 
	 * @throws DomainException
	 * @param ContainerInterface $container The container to register.
	 * @return ServiceLocator Fluent interface.
	 */
	public function registerContainer(ContainerInterface $container)
	{
		if($container instanceof LoaderInterface)
		{
			$this->collector->loadFromLoader(Collector::ROOT, $container);
		}
		$serviceList = $container->getProvidedServices();
		if(is_array($serviceList))
		{
			foreach($serviceList as $serviceName)
			{
				$this->containerServices[$serviceName] = $container;
			}
		}
		else
		{
			throw new DomainException('The container registered in the service locator should return an array of service names.');
		}
		return $this;
	} // end registerContainer();
	
	/**
	 * Registers a new service name alias.
	 * 
	 * @param string $alias The alias.
	 * @param string $objectName The original service name.
	 * @return ServiceLocator Fluent interface.
	 */
	public function addAlias($alias, $objectName)
	{
		$this->aliases[$alias] = (string) $objectName;
		return $this;
	} // end addAlias();
	
	/**
	 * Returns the original name hidden under the given service alias.
	 * If the alias is not defined, an exception is thrown.
	 * 
	 * @throws OutOfRangeException
	 * @param string $alias The alias name
	 * @return string The original service name
	 */
	public function getAlias($alias)
	{
		if(!isset($this->aliases[$alias]))
		{
			throw new OutOfRangeException('The specified alias does not exist: \''.$alias.'\'');
		}
		return $this->aliases[$alias];
	} // end getAlias();
	
	/**
	 * Checks if the service name alias is defined.
	 * 
	 * @param string $alias The service name alias.
	 * @return boolean 
	 */
	public function hasAlias($alias)
	{
		return isset($this->aliases[$alias]);
	} // end hasAlias();
	
	/**
	 * Removes the alias from the system. If the object for the given
	 * alias has already been created, it is removed, too.
	 * 
	 * @param string $alias The service alias
	 * @return ServiceLocator Fluent interface.
	 */
	public function removeAlias($alias)
	{
		if(!isset($this->aliases[$alias]))
		{
			throw new OutOfRangeException('The specified alias does not exist: \''.$alias.'\'');
		}
		unset($this->aliases[$alias]);
		
		if(array_key_exists($alias, $this->registry))
		{
			unset($this->registry[$alias]);
		}
		return $this;
	} // end removeAlias();
	
	/**
	 * Returns the configuration used by this locator.
	 * 
	 * @return Opl\Collector\Collector 
	 */
	public function getConfiguration()
	{
		return $this->collector;
	} // end getConfiguration();
	
	/**
	 * @see Locator
	 */
	protected function miss($name)
	{		
		if(!array_key_exists($name, $this->containerServices))
		{
			if(isset($this->aliases[$name]))
			{
				return $this->get($this->aliases[$name]);
			}
			throw new LocatorException('Cannot locate the service \''.$name.'\': not registered.');
		}
		$object = $this->containerServices[$name]->getService($name, $this);
		if(!is_object($object))
		{
			throw new LocatorException('Cannot locate the service \''.$name.'\': invalid data returned from the container.');
		}
		return $object;
	} // end miss();
} // end ServiceLocator;