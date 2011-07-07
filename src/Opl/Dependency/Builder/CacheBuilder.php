<?php
/*
 *  TRINITY FRAMEWORK <http://www.invenzzia.org>
 *
 * This file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. It is also available through
 * WWW at this URL: <http://www.invenzzia.org/license/new-bsd>
 *
 * Copyright (c) Invenzzia Group <http://www.invenzzia.org>
 * and other contributors. See website for details.
 */
namespace Opl\Dependency\Builder;
use Opl\Cache\Interfaces\ReadableStrategyInterface;
use Opl\Dependency\BuilderInterface;

/**
 * This builder allows to read the service definitions from a caching system.
 * It is the programmer's responsibility to store the definitions there.
 * 
 * @author Tomasz Jędrzejewski
 * @copyright Invenzzia Group <http://www.invenzzia.org/> and contributors.
 * @license http://www.invenzzia.org/license/new-bsd New BSD License
 */
class CacheBuilder implements BuilderInterface
{
	/**
	 * The readable caching strategy.
	 * @var ReadableStrategyInterface
	 */
	protected $strategy;
	/**
	 * The key, where the definitions are stored.
	 * @var string
	 */
	protected $key;

	/**
	 * Initializes the strategy.
	 * 
	 * @param ReadableStrategyInterface $strategy The caching strategy
	 * @param string $key The caching key, where the definitions are stored.
	 */
	public function __construct(ReadableStrategyInterface $strategy, $key)
	{
		$this->strategy = $strategy;
		$this->key = (string) $key;
	} // end __construct();

	/**
	 * @see BuilderInterface
	 */
	public function getDefinitions()
	{
		return $this->strategy->get($this->key);
	} // end getDefinitions();
} // end CacheBuilder;
