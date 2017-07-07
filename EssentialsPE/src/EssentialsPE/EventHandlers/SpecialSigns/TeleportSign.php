<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TF;

class TeleportSign extends BaseSign {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "teleportsign");
	}

	/**
	 * @param SignChangeEvent $signChangeEvent
	 */
	public function onSignChange(SignChangeEvent $signChangeEvent) {
		if(strtolower(TF::clean($signChangeEvent->getLine(0), true)) === "[teleport]") {
			if(!$signChangeEvent->getPlayer()->hasPermission("essentials.sign.create.teleport")) {
				$signChangeEvent->setCancelled();
			}
			if(!is_numeric($signChangeEvent->getLine(1))) {
				$signChangeEvent->getPlayer()->sendMessage(TF::RED . "[Error] " /* TODO */);
				$signChangeEvent->setCancelled();
			} elseif(!is_numeric($signChangeEvent->getLine(2))) {
				$signChangeEvent->getPlayer()->sendMessage(TF::RED . "[Error] " /* TODO */);
				$signChangeEvent->setCancelled();
			} elseif(!is_numeric($signChangeEvent->getLine(3))) {
				$signChangeEvent->getPlayer()->sendMessage(TF::RED . "[Error] " /* TODO */);
				$signChangeEvent->setCancelled();
			} else {
				$signChangeEvent->getPlayer()->sendMessage(TF::GREEN . "" /* TODO */);
				$signChangeEvent->setLine(0, TF::AQUA . "[Teleport]");
			}
		}
	}

	/**
	 * @param PlayerInteractEvent $interactEvent
	 */
	public function onInteract(PlayerInteractEvent $interactEvent) {
		if(!($tile = parent::onInteract($interactEvent))) {
			return;
		}
		if(TF::clean($tile->getText()[0], true) === "[Teleport]") {
			$interactEvent->setCancelled();
			if(!$interactEvent->getPlayer()->hasPermission("essentials.sign.use.teleport")) {
				$interactEvent->getPlayer()->sendMessage(TF::RED . "[Error] " /* TODO */);
			} else {
				$interactEvent->getPlayer()->teleport(new Vector3($x = $tile->getText()[1], $y = $tile->getText()[2], $z = $tile->getText()[3]));
				$interactEvent->getPlayer()->sendMessage(TF::GREEN . "Teleporting to " . TF::AQUA . $x . TF::GREEN . ", " . TF::AQUA . $y . TF::GREEN . ", " . TF::AQUA . $z);
			}
		}
	}
}