<?php

namespace EssentialsPEconomy\Events;

use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\event\plugin\PluginEvent;

abstract class BaseEvent extends PluginEvent {

	protected $loader;

	public function __construct(EssentialsPEconomy $loader) {
		parent::__construct($loader);
		$this->loader = $loader;
	}

	/**
	 * @return EssentialsPEconomy
	 */
	public function getLoader(): EssentialsPEconomy {
		return $this->loader;
	}
}