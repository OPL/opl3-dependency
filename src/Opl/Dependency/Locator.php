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
use Opl\Dependency\Exception\ContractException;
use Opl\Dependency\Exception\LocatorException;
use InvalidArgumentException;

/**
 * The core implementation of an object registry. 
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class Locator
{
	/**
	 * The list of managed objects.
	 * @var array
	 */
	protected $registry = array();
	
	/**
	 * Returns an object from the locator registry. If the object does
	 * not exist, an attempt is made to construct it.
	 * 
	 * @throws LocatorException
	 * @param string $name The object name
	 * @return object
	 */
	public function get($name)
	{
		if(!array_key_exists($name, $this->registry))
		{
			$this->registry[$name] = $this->miss($name);
		}
		return $this->registry[$name];
	} // end get();
	
	/**
	 * Returns an object from the locator registry. If the object does
	 * not exist, an attempt is made to construct it. The returned
	 * object must follow the specified contracts which are provided
	 * as an array of interface and/or class names.
	 * 
	 * @throws LocatorException
	 * @param string $name The object name
	 * @return object
	 */
	public function getContract($name, array $contracts)
	{
		if(!array_key_exists($name, $this->registry))
		{
			$this->registry[$name] = $this->miss($name);
		}
		$object = $this->registry[$name];
		foreach($contracts as $contract)
		{
			if(!($object instanceof $contract))
			{
				throw new ContractException('The contract for object \''.$name.'\' failed: '.$contract.' not implemented.');
			}
		}
		return $this->registry[$name];
	} // end getContract();
	
	/**
	 * Registers an object within the locator.
	 * 
	 * @param string $name The object name.
	 * @param object $object The object to register.
	 * @return Locator Fluent interface.
	 */
	public function set($name, $object)
	{
		if(!is_object($object))
		{
			throw new InvalidArgumentException('The second argument of Locator::set() must be a valid object.');
		}
		$this->registry[$name] = $object;
		return $this;
	} // end set();
	
	/**
	 * Checks if the object with the given name actually exists. No attempt
	 * to construct it is made - only the current registry is checked.
	 * 
	 * @param string $name The object name.
	 * @return boolean 
	 */
	public function exists($name)
	{
		return array_key_exists($name, $this->registry);
	} // end exists();
	
	/**
	 * This method is fired, if the locator cannot find an object with
	 * the given name. It is expected that this method will construct
	 * the object or throw an exception.
	 * 
	 * @throws LocatorException
	 * @param string $name The object name.
	 * @return object
	 */
	protected function miss($name)
	{
		throw new LocatorException('Cannot find an object with the \''.$name.'\' name.');
	} // end miss();
} // end Locator;