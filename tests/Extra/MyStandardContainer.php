<?php
/**
 * Unit tests for Open Power Dependency
 *
 * @author Tomasz "Zyx" Jędrzejewski
 * @copyright Copyright (c) 2011 Invenzzia Group
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
namespace Extra;
use Opl\Dependency\Container\StandardContainer;
use Opl\Dependency\ServiceLocator;
use stdClass;

class MyStandardContainer extends StandardContainer
{
	public function getDummyService(ServiceLocator $locator)
	{
		return new stdClass();
	} // end getDummyService();
	
	public function getDummy2Service()
	{
		echo 'fake method!';
	} // end getDummy2Service();
	
	public function getDummy3Service(ServiceLocator $locator, $bar)
	{
		echo 'fake method';
	} // end getDummy3Service();
	
	public function ommadawn()
	{
		echo 'should not be treated as a service.';
	} // end ommadawn();
} // end MyStandardContainer();