<?php
/**
 * The test suite file that configures the execution of the test cases.
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2009 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace TestSuite\Builder;

class AllTests extends \PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new AllTests('TestSuite\\Builder');
		$suite->addTestSuite('TestSuite\\Builder\\FileBuilderTest');
		$suite->addTestSuite('TestSuite\\Builder\\XmlFileBuilderTest');

		return $suite;
	} // end suite();
} // end AllTests;