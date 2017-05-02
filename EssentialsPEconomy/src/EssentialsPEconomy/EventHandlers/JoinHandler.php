<?php

namespace EssentialsPEconomy\EventHandlers;

use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinHandler implements Listener {

	private $loader;

	public function __construct(EssentialsPEconomy $loader) {
		$this->loader = $loader;
	}

	public function onPlayerJoin(PlayerJoinEvent $event) {
		$this->getLoader()->getProvider()->addPlayer($event->getPlayer());
	}

	/**
	 * @return EssentialsPEconomy
	 */
	public function getLoader(): EssentialsPEconomy {
		return $this->loader;
	}
}