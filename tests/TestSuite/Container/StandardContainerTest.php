<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2011 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Container;
use Extra\MyStandardContainer;
use Opl\Collector\Collector;
use Opl\Dependency\ServiceLocator;

/**
 * @covers \Opl\Dependency\Container\StandardContainer
 */
class StandardContainerTest extends \PHPUnit_Framework_TestCase
{
	public function testServiceDiscovery()
	{
		$container = new MyStandardContainer();
		$this->assertEquals(array('Dummy'), $container->getProvidedServices());
	} // end testServiceDiscovery();
	
	public function testGetServiceReturnsTheRequestedService()
	{
		$container = new MyStandardContainer();
		$container->getProvidedServices();
		$this->assertEquals('stdClass', get_class($container->getService('Dummy', new ServiceLocator(new Collector()))));
	} // end testGetServiceReturnsTheRequestedService();
} // end StandardContainerTest;