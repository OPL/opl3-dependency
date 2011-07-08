<?php
/*
 *  TRINITY FRAMEWORK <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Dependency\Builder;
use Opl\Dependency\Exception\BuilderException;
use BadMethodCallException;

class XmlFileBuilder extends FileBuilder
{
	/**
	 * @see BuilderInterface
	 */
	public function getDefinitions()
	{
		if(null === $this->currentFile)
		{
			throw new BadMethodCallException('Cannot load an XML file: no file specified');
		}
		
		libxml_use_internal_errors(true);
		$document = \simplexml_load_file($this->findFile($this->currentFile));
		foreach (libxml_get_errors() as $error)
		{
			throw new BuilderException('An error occured while parsing \''.$this->currentFile.'\': '.$error);
		}
		
		$data = array();
		foreach($document->service as $service)
		{
			$stub = array('className' => $service['class-name'], 'constructor' => array(), 'initializers' => array());
			
			if(isset($service->constructor))
			{
				foreach($service->constructor as $argument)
				{
					$stub['constructor'][] = (string)$argument;
				}
			}
			if(isset($service->initializer))
			{
				foreach($service->initializer as $initializer)
				{
					$initializer = array();
					if(isset($initializer->argument))
					{
						foreach($initializer->argument as $argument)
						{
							$initializer[] = (string)$argument;
						}
					}
					$stub['initializers'][$initializer['name']] = $initializer;
				}
			}
			$data[$service['name']] = $stub;
		}
		return $data;
	} // end getDefinitions();
} // end XmlFileBuilder;