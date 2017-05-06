<?php

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use pocketmine\event\Listener;

abstract class BaseEventHandler implements Listener {

	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}