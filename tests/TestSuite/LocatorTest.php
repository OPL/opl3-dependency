<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite;
use Opl\Dependency\Locator;
use stdClass;

/**
 * @covers \Opl\Dependency\Locator
 */
class LocatorTest extends \PHPUnit_Framework_TestCase
{
	public function testGettingAndSettingObjects()
	{
		$locator = new Locator();
		$locator->set('foo', $foo = new stdClass());
		$locator->set('bar', $bar = new stdClass());
		$this->assertSame($foo, $locator->get('foo'));
		$this->assertSame($bar, $locator->get('bar'));
	} // end testGettingAndSettingObjects();
	
	/**
	 * @expectedException Opl\Dependency\Exception\LocatorException
	 */
	public function testGetThrowsExceptionIfObjectDoesNotExist()
	{
		$locator = new Locator();
		$locator->set('foo', $foo = new stdClass());
		$this->assertSame($foo, $locator->get('foo'));
		$locator->get('bar');
	} // end testGetThrowsExceptionIfObjectDoesNotExist();
	
	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetThrowsExceptionIfValueIsNotAnObject()
	{
		$locator = new Locator();
		$locator->set('foo', 42);
	} // end testSetThrowsExceptionIfValueIsNotAnObject();
	
	public function testSetReturnsSelfReference()
	{
		$locator = new Locator();
		$this->assertSame($locator, $locator->set('foo', new stdClass()));
	} // end testSetReturnsSelfReference();
	
	public function testGetContractValidatesTheContract()
	{
		$locator = new Locator();
		$locator->set('foo', $foo = new stdClass());
		$this->assertSame($foo, $locator->getContract('foo', array('\stdClass')));
	} // end testGetContractValidatesTheContract();
	
	/**
	 * @expectedException Opl\Dependency\Exception\ContractException
	 */
	public function testGetContractThrowsExceptionIfContractFails()
	{
		$locator = new Locator();
		$locator->set('foo', $foo = new stdClass());
		$locator->getContract('foo', array('\barClass'));
	} // end testGetContractValidatesTheContract();
	
	/**
	 * @expectedException Opl\Dependency\Exception\LocatorException
	 */
	public function testGetContractThrowsExceptionIfObjectDoesNotExist()
	{
		$locator = new Locator();
		$locator->getContract('bar', array('\stdClass'));
	} // end testGetContractThrowsExceptionIfObjectDoesNotExist();
	
	public function testExistsChecksIfObjectExists()
	{
		$locator = new Locator();
		$this->assertFalse($locator->exists('foo'));
		$locator->set('foo', new stdClass());
		$this->assertTrue($locator->exists('foo'));
	} // end testExistsChecksIfObjectExists();
} // end LocatorTest;