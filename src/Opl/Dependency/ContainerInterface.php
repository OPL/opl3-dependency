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

/**
 * The interface for writing containers that are able to provide various
 * services for the system.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface ContainerInterface
{
	/**
	 * Returns the list containing the names of provided services.
	 * @return array
	 */
	public function getProvidedServices();
	
	/**
	 * Creates the given service and returns the object.
	 * 
	 * @param string $name The service name
	 * @param ServiceLocator $locator The service locator
	 * @return object
	 */
	public function getService($name, ServiceLocator $locator);
} // end ContainerInterface;