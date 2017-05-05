<?php

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use pocketmine\event\Listener;

abstract class BaseEventHandler implements Listener {

	private $loader;
	private $module = Loader::MODULE_ESSENTIALS;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return int
	 */
	public function getModule(): int {
		return $this->module;
	}

	/**
	 * @param int $module
	 */
	public function setModule(int $module) {
		$this->module = $module;
	}
}