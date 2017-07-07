<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\EventHandlers\BaseEventHandler;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Event;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat as TF;

abstract class BaseSign extends BaseEventHandler {

	private $name;
	private $module = Loader::MODULE_ESSENTIALS;

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader);
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getModule(): int {
		return $this->module;
	}

	/**
	 * @param int $module
	 */
	public function setModule(int $module) {
		$this->module = $module;
	}

	/**
	 * @param PlayerInteractEvent $event
	 *
	 * @return bool|Tile
	 */
	public function checkSign(PlayerInteractEvent $event) {
		$tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
		if($tile instanceof Sign) {
			return $tile;
		}
		return false;
	}

	/**
	 * @param Event $event
	 *
	 * @return bool
	 */
	public function testSignType(Event $event) {
		if($event instanceof PlayerInteractEvent) {
			$tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
			if($tile instanceof Sign) {
				if(TF::clean($tile->getText()[0], true) === "[" . $this->getName() . "]") {
					return true;
				}
			}
			return false;
		} elseif($event instanceof SignChangeEvent) {
			if(TF::clean(strtolower($event->getLine(0)), true) === "[" . strtolower($this->getName()) . "]") {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param Event $event
	 *
	 * @return bool
	 */
	public function testPermission(Event $event) {
		if($event instanceof PlayerInteractEvent) {
			if($event->getPlayer()->hasPermission("essentials.sign.use." . strtolower($this->getName()))) {
				return true;
			}
		} elseif($event instanceof SignChangeEvent) {
			if($event->getPlayer()->hasPermission("essentials.sign.create." . strtolower($this->getName()))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param PlayerInteractEvent $interactEvent
	 *
	 * @return null|Sign
	 */
	public function onInteract(PlayerInteractEvent $interactEvent) {
		$tile = $interactEvent->getBlock()->getLevel()->getTile(new Vector3($interactEvent->getBlock()->getFloorX(), $interactEvent->getBlock()->getFloorY(), $interactEvent->getBlock()->getFloorZ()));
		if(!$tile instanceof Sign) {
			return null;
		}
		return $tile;
	}

	/**
	 * @param SignChangeEvent $signChangeEvent
	 */
	public abstract function onSignChange(SignChangeEvent $signChangeEvent);
}