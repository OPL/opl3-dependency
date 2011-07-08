<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Builder;
use Extra\FileBuilderMock;
use Opl\Dependency\Builder\FileBuilder;

/**
 * @covers \Opl\Dependency\Builder\FileBuilder
 */
class FileBuilderTest extends \PHPUnit_Framework_TestCase
{
	public function testConstructAcceptsSinglePathAsString()
	{
		$fileBuilder = new FileBuilderMock('./foo/');
		$this->assertSame(array(0 => './foo/'), $fileBuilder->_getPaths());
	} // end testConstructAcceptsSinglePathAsString();

	public function testConstructAcceptsManyPathsAsArray()
	{
		$fileBuilder = new FileBuilderMock(array('./foo/', './bar/'));
		$this->assertSame(array(0 => './foo/', './bar/'), $fileBuilder->_getPaths());
	} // end testConstructAcceptsManyPathsAsArray();

	public function testConstructAddsTrailingSlashes()
	{
		$fileBuilder = new FileBuilderMock(array('./foo/', './bar'));
		$this->assertSame(array(0 => './foo/', './bar/'), $fileBuilder->_getPaths());
	} // end testConstructAddsTrailingSlashes();

	public function testConstructAddsTrailingSlashesToEmptyStrings()
	{
		$fileBuilder = new FileBuilderMock(array('./foo/', ''));
		$this->assertSame(array(0 => './foo/', '/'), $fileBuilder->_getPaths());
	} // end testConstructAddsTrailingSlashesToEmptyStrings();

	public function testSettingFilename()
	{
		$fileBuilder = new FileBuilderMock('./foo/');
		$fileBuilder->setFile('foo.txt');
		$this->assertEquals('foo.txt', $fileBuilder->getFile());

		$fileBuilder->setFile('bar.txt');
		$this->assertEquals('bar.txt', $fileBuilder->getFile());
	} // end testSettingFilename();

	public function testGetFileAndGetIdentifierReturnTheSameThing()
	{
		$fileBuilder = new FileBuilderMock(array('./foo/', ''));
		$fileBuilder->setFile('abcdef.txt');

		$this->assertEquals('abcdef.txt', $fileBuilder->getFile());
		$this->assertEquals('abcdef.txt', $fileBuilder->getIdentifier());
	} // end testGetFileAndGetIdentifierReturnTheSameThing();

	public function testFindFileScansTheListOfDirectories()
	{
		$fileBuilder = new FileBuilderMock(array('./data2/', './data/'));
		$this->assertEquals('./data/file1.xml', $fileBuilder->findFile('file1.xml'));
	} // end testFindFileScansTheListOfDirectories();

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testFindFileThrowsAnExceptionIfTheFileIsNotFound()
	{
		$fileBuilder = new FileBuilderMock(array('./data2/', './data3/'));
		$fileBuilder->findFile('file.xml');
	} // end testFindFileThrowsAnExceptionIfTheFileIsNotFound();
} // end FileBuilderTest;