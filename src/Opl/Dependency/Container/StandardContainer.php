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
use Opl\Dependency\ContainerInterface;
use Opl\Dependency\ServiceLocator;

/**
 * The standard container represents services as the <tt>getXXXService()</tt>
 * methods, where the <tt>XXX</tt> is the capitalized service name. The class
 * should be overwritten in order to implement some services. It uses self-reflection
 * to discover them.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
abstract class StandardContainer implements ContainerInterface
{
	/**
	 * Self-reflection object.
	 * @var ReflectionObject
	 */
	protected $reflection;
	/**
	 * The service map constructed using the reflection.
	 * @var array
	 */
	protected $serviceMap = array();
	
	/**
	 * @see ContainerInterface
	 */
	public function getProvidedServices()
	{
		$this->reflection = new \ReflectionObject($this);
		$serviceList = array();
		foreach($this->reflection->getMethods() as $method)
		{
			if(preg_match('/^get([a-zA-Z0-9\_]+)Service$/', $method->getName(), $matches) && $method->getNumberOfRequiredParameters() == 1)
			{
				$this->serviceMap[$matches[1]] = $method->getName();
				$serviceList[] = $matches[1];
			}
		}

		return $serviceList;
	} // end getProvidedServices();

	/**
	 * @see ContainerInterface
	 */
	public function getService($name, ServiceLocator $locator)
	{
		return $this->{$this->serviceMap[$name]}($locator);
	} // end getService();
} // end StandardContainer;