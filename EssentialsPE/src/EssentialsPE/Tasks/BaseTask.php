<?php

declare(strict_types = 1);

namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\scheduler\PluginTask;

abstract class BaseTask extends PluginTask {

	/** @var Loader */
	private $loader;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}