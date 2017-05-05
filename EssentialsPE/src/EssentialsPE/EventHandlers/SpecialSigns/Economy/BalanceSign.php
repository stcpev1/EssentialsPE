<?php

namespace EssentialsPE\EventHandlers\SpecialSigns\Economy;

use EssentialsPE\EventHandlers\SpecialSigns\BaseSign;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;

class BalanceSign extends BaseSign {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "balancesign");
	}

	/**
	 * @param PlayerInteractEvent $interactEvent
	 */
	public function onInteract(PlayerInteractEvent $interactEvent) {

	}

	/**
	 * @param SignChangeEvent $signChangeEvent
	 */
	public function onSignChange(SignChangeEvent $signChangeEvent) {

	}
}