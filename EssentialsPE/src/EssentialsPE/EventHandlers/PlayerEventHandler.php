<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use EssentialsPE\Tasks\DelayedTeleportTask;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

class PlayerEventHandler extends BaseEventHandler {

	/** @var bool[] */
	private $teleportScheduled = [];

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param PlayerLoginEvent $event
	 */
	public function onLogin(PlayerLoginEvent $event) {
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
			if(isset($this->teleportScheduled[$player->getName()])) {
				unset($this->teleportScheduled[$player->getName()]);
				return;
			}
			$delay = $this->getLoader()->getConfigurableData()->getConfiguration()->get("Teleporting.Delay-Time");
			$this->teleportScheduled[$player->getName()] = true;
			$this->getLoader()->getServer()->getScheduler()->scheduleDelayedTask(new DelayedTeleportTask($this->getLoader(), $player, $event->getFrom(), $event->getTo()), (int) $delay * 20);
			$player->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.teleport-delay", $delay));

			$event->setCancelled();
		}
	}
}