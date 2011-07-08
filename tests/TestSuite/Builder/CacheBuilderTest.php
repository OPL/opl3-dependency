<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2011 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Builder;
use Opl\Dependency\Builder\CacheBuilder;

/**
 * @covers \Opl\Dependency\Builder\CacheBuilder
 */
class CacheBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function testBuilderReadsTheSpecifiedKeyFromCache()
	{
		$strategyMock = $this->getMock('Opl\\Cache\\Interfaces\\ReadableStrategyInterface');
		$strategyMock->expects($this->once())
			->method('get')
			->with('foo')
			->will($this->returnValue(array('foo' => 'bar')));
		
		$cacheBuilder = new CacheBuilder($strategyMock, 'foo');
		$this->assertEquals(array('foo' => 'bar'), $cacheBuilder->getDefinitions());
	} // end testBuilderReadsTheSpecifiedKeyFromCache();
} // end CacheBuilderTest;