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

/**
 * Loads the service definitions from a YAML file.
 *
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class YamlFileBuilder extends FileBuilder
{
	/**
	 * @see BuilderInterface
	 */
	public function getDefinitions()
	{
		if(null === $this->currentFile)
		{
			throw new BadMethodCallException('Cannot load a YAML file: no file specified');
		}

		return Yaml::load($this->findFile($this->currentFile));
	} // end getDefinitions();
} // end YamlFileBuilder;