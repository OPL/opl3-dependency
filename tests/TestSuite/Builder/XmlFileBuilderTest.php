<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2011 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Builder;
use Opl\Dependency\Builder\XmlFileBuilder;

/**
 * @covers \Opl\Dependency\Builder\XmlFileBuilder
 */
class XmlFileBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function testValidXmlDocument()
	{
		$builder = new XmlFileBuilder('./data/');
		$builder->setFile('file1.xml');
		
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
		$builder = new XmlFileBuilder('./data/');
		$builder->getDefinitions();
	} // end testGetDefinitionsThrowsExceptionIfNoFileDefined();
	
	/**
	 * @expectedException Opl\Dependency\Exception\BuilderException
	 */
	public function testXmlErrorsAreReportedAsExceptions()
	{
		$builder = new XmlFileBuilder('./data/');
		$builder->setFile('file_invalid.xml');
		$builder->getDefinitions();
	} // end testXmlErrorsAreReportedAsExceptions();
} // end XmlFileBuilderTest;