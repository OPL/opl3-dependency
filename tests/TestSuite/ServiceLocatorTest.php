<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite;
use Opl\Collector\Collector;
use Opl\Dependency\ServiceLocator;
use ReflectionObject;
use stdClass;

/**
 * @covers \Opl\Dependency\Locator
 * @covers \Opl\Dependency\ServiceLocator
 */
class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
	public function testCreatingAliases()
	{
		$locator = new ServiceLocator(new Collector());
		$this->assertFalse($locator->hasAlias('foo'));
		$locator->addAlias('foo', 'bar');
		$this->assertTrue($locator->hasAlias('foo'));
		$this->assertEquals('bar', $locator->getAlias('foo'));
	} // end testCreatingAliases();
	
	public function testRemovingAlias()
	{
		$locator = new ServiceLocator(new Collector());
		$this->assertFalse($locator->hasAlias('foo'));
		$locator->addAlias('foo', 'bar');
		$this->assertTrue($locator->hasAlias('foo'));
		$locator->removeAlias('foo');
		$this->assertFalse($locator->hasAlias('foo'));
	} // end testRemovingAlias();
	
	public function testAddAliasReturnsSelfReference()
	{
		$locator = new ServiceLocator(new Collector());
		$this->assertSame($locator, $locator->addAlias('foo', 'bar'));
	} // end testAddAliasReturnsSelfReference();
	
	public function testRemoveAliasReturnsSelfReference()
	{
		$locator = new ServiceLocator(new Collector());
		$locator->addAlias('foo', 'bar');
		$this->assertSame($locator, $locator->removeAlias('foo'));
	} // end testRemoveAliasReturnsSelfReference();
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testGetAliasThrowsExceptionIfAliasDoesNotExist()
	{
		$locator = new ServiceLocator(new Collector());
		$locator->getAlias('not_exists');
	} // end testGetAliasThrowsExceptionIfAliasDoesNotExist();
	
	/**
	 * @expectedException OutOfRangeException
	 */
	public function testRemoveAliasThrowsExceptionIfAliasDoesNotExist()
	{
		$locator = new ServiceLocator(new Collector());
		$locator->removeAlias('not_exists');
	} // end testRemoveAliasThrowsExceptionIfAliasDoesNotExist();

	public function testAddAliasOverwritesThePreviousDefinition()
	{
		$locator = new ServiceLocator(new Collector());
		$locator->addAlias('foo', 'bar');
		$this->assertEquals('bar', $locator->getAlias('foo'));
		$locator->addAlias('foo', 'joe');
		$this->assertEquals('joe', $locator->getAlias('foo'));
	} // end testAddAliasOverwritesThePreviousDefinition();
	
	public function testRegisterContainerImportsTheServiceDefinitions()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo', 'bar')));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		
		$reflection = new ReflectionObject($locator);
		
		$reflData = $reflection->getProperty('containerServices');
		$reflData->setAccessible(true);
		$this->assertEquals(array('foo' => $container, 'bar' => $container), $reflData->getValue($locator));
	} // end testRegisterContainerImportsTheServiceDefinitions();
	
	/**
	 * @expectedException DomainException
	 */
	public function testRegisterContainerThrowsExceptionIfContainerDoesNotReturnArray()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(42));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
	} // end testRegisterContainerThrowsExceptionIfContainerDoesNotReturnArray();
	
	public function testMissImportsTheService()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo')));
		$container->expects($this->once())
			->method('getService')
			->will($this->returnValue($obj = new stdClass()));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		
		$this->assertSame($obj, $locator->get('foo'));
	} // end testMissImportsTheService();
	
	/**
	 * @expectedException Opl\Dependency\Exception\LocatorException
	 */
	public function testMissThrowsExceptionOnInvalidDataReturnedFromService()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo')));
		$container->expects($this->once())
			->method('getService')
			->will($this->returnValue(42));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		
		$locator->get('foo');
	} // end testMissThrowsExceptionOnInvalidDataReturnedFromService();
	
	/**
	 * @expectedException Opl\Dependency\Exception\LocatorException
	 */
	public function testMissThrowsExceptionIfServiceDoesNotExist()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo')));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		
		$locator->get('bar');
	} // end testMissThrowsExceptionIfServiceDoesNotExist();
	
	public function testMissUsesAliases()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo')));
		$container->expects($this->once())
			->method('getService')
			->will($this->returnValue($obj = new stdClass()));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		$locator->addAlias('bar', 'foo');
		
		$this->assertSame($obj, $locator->get('bar'));
	} // end testMissUsesAliases();
	
	public function testRemoveAliasRemovesAlsoTheServiceObject()
	{
		$container = $this->getMock('Opl\Dependency\ContainerInterface');
		$container->expects($this->once())
			->method('getProvidedServices')
			->will($this->returnValue(array('foo')));
		$container->expects($this->once())
			->method('getService')
			->will($this->returnValue($obj = new stdClass()));
		
		$locator = new ServiceLocator(new Collector());
		$locator->registerContainer($container);
		$locator->addAlias('bar', 'foo');
		
		$this->assertSame($obj, $locator->get('bar'));
		$this->assertTrue($locator->exists('bar'));
		$this->assertTrue($locator->exists('foo'));
		
		$locator->removeAlias('bar');
		
		$this->assertFalse($locator->exists('bar'));
		$this->assertTrue($locator->exists('foo'));
	} // end testRemoveAliasRemovesAlsoTheServiceObject();
	
	public function testGetConfigurationReturnsTheCollector()
	{
		$locator = new ServiceLocator($collector = new Collector());
		$this->assertSame($collector, $locator->getConfiguration());
	} // end testGetConfigurationReturnsTheCollector();
} // end ServiceLocatorTest;