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
namespace Opl\Dependency\Container;
use Opl\Dependency\Builder\BuilderInterface;
use Opl\Dependency\ContainerInterface;
use Opl\Dependency\ServiceLocator;
use Opl\Dependency\Exception\ContainerException;

/**
 * The building container uses the provided definitions to create a service
 * on the fly.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class BuildingContainer implements ContainerInterface
{
	/**
	 * The builder used to collect the service definitions.
	 * @var BuilderInterface
	 */
	protected $builder;	
	/**
	 * The list of the service definitions.
	 * @var array
	 */
	protected $definitions;
	
	/**
	 * Creates the container and initializes it to work with the specified
	 * builder.
	 * 
	 * @param BuilderInterface $builder The builder that provides the data.
	 */
	public function __construct(BuilderInterface $builder)
	{
		$this->builder = $builder;
	} // end __construct();
	
	/**
	 * @see ContainerInterface
	 */
	public function getProvidedServices()
	{
		if(empty($this->definitions))
		{
			$this->definitions = $this->builder->getDefinitions();
		}
		$serviceList = array();
		foreach($this->definitions as $name => &$definition)
		{
			$serviceList[] = $name;
		}
		return $serviceList;
	} // end getProvidedServices();
	
	/**
	 * @see ContainerInterface
	 */
	public function getService($name, ServiceLocator $locator)
	{
		$config = $locator->getConfiguration();
		$object = $this->buildObject($locator, $config, $this->definitions[$name]['className'], $this->definitions[$name]['constructor']);
		foreach($this->definitions[$name]['initializers'] as $name => $args)
		{
			$this->callInitializer($locator, $config, $object, $name, $args);
		}
		return $object;
	} // end getService();

	/**
	 * Creates a new object of the given type and returns it.
	 * 
	 * @internal
	 * @param ServiceLocator $locator The service locator used for resolving dependencies.
	 * @param Collector $config The configuration used for resolving the dependencies.
	 * @param string $className The class name.
	 * @param array $constructorArgs The constructor args.
	 * @return array
	 */
	protected function buildObject(ServiceLocator $locator, Collector $config, $className, $constructorArgs)
	{
		$reflection = new \ReflectionClass($className);
		
		if(!$reflection->isInstantiable())
		{
			throw new ContainerException('The class \''.$className.'\' is not instantiable.');
		}
		
		$args = array();
		foreach($constructorArgs as $arg)
		{
			$args[] = $this->extractArgument($locator, $config, $arg);			
		}
		
		return $reflection->newInstanceArgs($args);
	} // end buildObject();
	
	/**
	 * Calls an initializer method on the given object.
	 * 
	 * @param ServiceLocator $locator The service locator used for resolving dependencies.
	 * @param Collector $config The configuration used for resolving dependencies.
	 * @param object $object The object we are working on.
	 * @param string $initializer The initialization method name.
	 * @param array $methodArgs The initialization method arguments.
	 */
	protected function callInitializer(ServiceLocator $locator, Collector $config, $object, $initializer, $methodArgs)
	{
		if(!method_exists($object, $initializer))
		{
			throw new ContainerException('The initializer \''.get_class($className).'::'.$initializer.'\' does not exist.');
		}
		$args = array();
		foreach($methodArgs as $arg)
		{
			$args[] = $this->extractArgument($locator, $config, $arg);			
		}
		call_user_func_args(array($object, $initializer), $args);
	} // end callInitializer();
	
	/**
	 * Extracts the argument value. The method currently supports finding dependencies, static
	 * values and reading the configuration options.
	 * 
	 * @param ServiceLocator $locator
	 * @param Collector $config
	 * @param mixed $argument
	 * @return mixed 
	 */
	protected function extractArgument(ServiceLocator $locator, Collector $config, $argument)
	{
		if(is_scalar($argument))
		{
			if(($pos = strpos($argument, 'service:')) === 0)
			{
				return $locator->get(substr($argument, $pos, strlen($argument) - $pos));
			}
			elseif(preg_match('^\%([a-zA-Z0-9\_\.]+)\%$', $argument, $found))
			{
				return $config->get($found[1]);
			}
		}
		return $argument;
	} // end extractArgument();
} // end BuildingContainer;