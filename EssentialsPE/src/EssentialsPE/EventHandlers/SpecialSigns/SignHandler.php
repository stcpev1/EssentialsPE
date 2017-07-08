<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\EventHandlers\BaseEventHandler;
use EssentialsPE\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat as TF;

class SignHandler extends BaseEventHandler {

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @priority HIGH
	 */
	public function onBreak(BlockBreakEvent $event) {
		$tile = $event->getBlock()->getLevel()->getTile($event->getBlock());
		if($tile instanceof Sign) {
			if(!$this->isSpecialSign($tile)) {
				return;
			}
			$signType = strtolower(trim(TF::clean($tile->getText()[0], true), "[]"));
			if(!$event->getPlayer()->hasPermission("essentials.sign.break." . $signType)) {
				$event->setCancelled();
				$event->getPlayer()->sendMessage(TF::RED /* TODO */);
			}
		}
	}

	/**
	 * @param SignChangeEvent $event
	 */
	public function onSignChange(SignChangeEvent $event) {
		$tile = $event->getBlock()->getLevel()->getTile($event->getBlock());
		foreach($this->getLoader()->getSignManager()->getRegisteredSigns() as $sign) {
			if("[" . $sign->getName() . "]" === strtolower(TF::clean($event->getLine(0), true))) {
				if($tile instanceof Sign) {
					if(!$event->getPlayer()->hasPermission("essentials.sign.create." . $sign->getName())) {
						return;
					}
					$tile->namedtag->essentialsSS = new CompoundTag("essentialsSS", [
						new StringTag("1", $event->getLine(1)),
						new StringTag("2", $event->getLine(2)),
						new StringTag("3", $event->getLine(3))
					]);
					if(!$sign->create($event, $tile)) {
						unset($tile->namedtag->essentialsSS);
						return;
					}
					$event->setLine(0, TF::AQUA . "[" . ucfirst($sign->getName()) . "]");
				}
			}
		}
	}

	/**
	 * @param PlayerInteractEvent $event
	 */
	public function onInteract(PlayerInteractEvent $event) {
		$tile = $event->getBlock()->getLevel()->getTile($event->getBlock());
		if(!$tile instanceof Sign) {
			return;
		}
		foreach($this->getLoader()->getSignManager()->getRegisteredSigns() as $sign) {
			if("[" . $sign->getName() . "]" === strtolower(TF::clean($tile->getText()[0], true))) {
				if(!$this->isSpecialSign($tile)) {
					return;
				}
				if(!$event->getPlayer()->hasPermission("essentials.sign.use." . $sign->getName())) {
					return;
				}
				if(!$sign->tap($event, $tile)) {
					return;
				}
			}
		}
	}

	/**
	 * @param Sign $sign
	 *
	 * @return bool
	 */
	public function isSpecialSign(Sign $sign) {
		return isset($sign->namedtag->essentialsSS);
	}
}