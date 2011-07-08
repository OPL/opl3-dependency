<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2011 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Builder;
use Opl\Dependency\Builder\YamlFileBuilder;

/**
 * @covers \Opl\Dependency\Builder\YamlFileBuilder
 */
class YamlFileBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function testValidYamlDocument()
	{
		$builder = new YamlFileBuilder('./data/');
		$builder->setFile('file1.yml');
		
		$this->assertEquals(array(
			'foo' => array(
				'className' => 'Opl\Foo\Bar',
				'constructor' => array(
					'service:bar', 15
				),
				'initializers' => array(
					'setJoe' => array('service:joe')
				)
			)
		), $builder->getDefinitions());
	} // end testValidXmlDocument();
	
	/**
	 * @expectedException BadMethodCallException
	 */
	public function testGetDefinitionsThrowsExceptionIfNoFileDefined()
	{
		$builder = new YamlFileBuilder('./data/');
		$builder->getDefinitions();
	} // end testGetDefinitionsThrowsExceptionIfNoFileDefined();
} // end YamlFileBuilderTest;