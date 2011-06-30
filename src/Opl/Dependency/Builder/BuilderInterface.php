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
namespace Opl\Dependency\Builder;
use Opl\Dependency\ServiceLocator;

/**
 * The interface allows writing the service builders that load the service
 * definitions from external sources. They should be used in conjunction
 * with <tt>BuildingContainer</tt>.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
interface BuilderInterface
{
	/**
	 * Returns the array containing the service definitions.
	 * 
	 * @return array
	 */
	public function getDefinitions();
} // end BuilderInterface;