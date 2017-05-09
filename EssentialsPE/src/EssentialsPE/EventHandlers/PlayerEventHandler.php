<?php

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use EssentialsPE\Tasks\DelayedTeleportTask;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

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
		SessionManager::getSession($event->getPlayer())->saveData();
		$this->getLoader()->getSessionManager()->deleteSession($event->getPlayer());
	}

	/**
	 * @param EntityTeleportEvent $event
	 */
	public function onTeleport(EntityTeleportEvent $event) {
		$player = $event->getEntity();
		if($player instanceof Player) {
			if($this->getLoader()->getConfigurableData()->getConfiguration()->get("Teleporting.Delay") !== true) {
				return;
			}
			$delay = $this->getLoader()->getConfigurableData()->getConfiguration()->get("Teleporting.Delay-Time");
			$this->getLoader()->getServer()->getScheduler()->scheduleDelayedTask(new DelayedTeleportTask($this->getLoader(), $player, $event->getTo()), $delay);
			$player->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.teleport-delay", $delay));
		}
	}
}