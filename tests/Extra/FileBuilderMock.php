<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace Extra;
use Opl\Dependency\Builder\FileBuilder;

class FileBuilderMock extends FileBuilder
{
	public function getDefinitions()
	{
		return array();
	} // end getDefinitions();

	public function _getPaths()
	{
		return $this->paths;
	} // end getPaths();
} // end FileBuilderMock;