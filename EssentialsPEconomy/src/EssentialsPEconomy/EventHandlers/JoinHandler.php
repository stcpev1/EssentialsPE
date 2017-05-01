<?php

namespace EssentialsPEconomy\EventHandlers;

use EssentialsPEconomy\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinHandler implements Listener {

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

	public function onPlayerJoin(PlayerJoinEvent $event) {
		$this->getLoader()->getProvider()->addPlayer($event->getPlayer());
	}
}