<?php

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerEventHandler extends BaseEventHandler {

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param PlayerJoinEvent $event
	 */
	public function onJoin(PlayerJoinEvent $event) {
		$this->getLoader()->getSessionManager()->createSession($event->getPlayer());
	}

	/**
	 * @param PlayerQuitEvent $event
	 */
	public function onQuit(PlayerQuitEvent $event) {
		$this->getLoader()->getSessionManager()->deleteSession($event->getPlayer());
	}
}