<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns\Teleportation;

use EssentialsPE\EventHandlers\SpecialSigns\BaseSign;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;

class TeleportSign extends BaseSign {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "teleport");
	}

	/**
	 * @param SignChangeEvent $event
	 * @param Sign            $sign
	 *
	 * @return bool
	 */
	public function create(SignChangeEvent $event, Sign $sign): bool {
		list($x, $y, $z) = $sign->namedtag->essentialsSS;
		if(!is_numeric($x) || !is_numeric($y) || !is_numeric($z)) {
			return false;
		}
		return true;
	}

	/**
	 * @param PlayerInteractEvent $event
	 * @param Sign                $sign
	 *
	 * @return bool
	 */
	public function tap(PlayerInteractEvent $event, Sign $sign): bool {
		list($x, $y, $z) = $sign->namedtag->essentialsSS;
		$event->getPlayer()->teleport(new Vector3((int) $x, (int) $y, (int) $z));
		return true;
	}
}