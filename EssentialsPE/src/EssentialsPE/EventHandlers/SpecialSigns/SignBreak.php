<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat as TF;

class SignBreak implements Listener {

	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @param BlockBreakEvent $breakEvent
	 *
	 * @priority HIGH
	 */
	public function onBreak(BlockBreakEvent $breakEvent) {
		$tile = $breakEvent->getBlock()->getLevel()->getTile(new Vector3($breakEvent->getBlock()->getFloorX(), $breakEvent->getBlock()->getFloorY(), $breakEvent->getBlock()->getFloorZ()));
		if($tile instanceof Sign) {
			$key = ["Free", "Gamemode", "Heal", "Kit", "Repair", "Time", "Teleport", "Warp", "Balance", "Buy", "Sell", "BalanceTop"];
			foreach($key as $k) {
				if(TF::clean($tile->getText()[0], true) === "[" . $k . "]" && !$breakEvent->getPlayer()->hasPermission("essentials.sign.break." . strtolower($k))) {
					$breakEvent->setCancelled();
					$breakEvent->getPlayer()->sendMessage(TF::RED /* TODO */);
					break;
				}
			}
		}
	}
}