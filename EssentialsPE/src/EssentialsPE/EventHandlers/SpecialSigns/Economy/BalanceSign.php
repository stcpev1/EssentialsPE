<?php

namespace EssentialsPE\EventHandlers\SpecialSigns\Economy;

use EssentialsPE\EventHandlers\SpecialSigns\BaseSign;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat as TF;

class BalanceSign extends EconomySign {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "Balance");
		$this->setModule(Loader::MODULE_ECONOMY);
	}

	/**
	 * @param PlayerInteractEvent $interactEvent
	 */
	public function onInteract(PlayerInteractEvent $interactEvent) {
		$player = $interactEvent->getPlayer();
		if(!$sign = $this->checkSign($interactEvent)) {
			return;
		}
		if(!$this->testSignType($interactEvent)) {
			return;
		}
		$interactEvent->setCancelled();
		if(!$this->testPermission($interactEvent)) {
			$this->sendMessageContainer($player, "error.sign-need-permission", "use");
		} else {
			$this->sendMessageContainer($player, "commands.balance.self", $this->getEconomyProvider()->getCurrencySymbol() . $this->getEconomyProvider()->getBalance($player));
		}
	}

	/**
	 * @param SignChangeEvent $signChangeEvent
	 */
	public function onSignChange(SignChangeEvent $signChangeEvent) {
		$player = $signChangeEvent->getPlayer();
		if(!$this->testSignType($signChangeEvent)) {
			return;
		}
		if(!$this->testPermission($signChangeEvent)) {
			$this->sendMessageContainer($player, "error.sign-need-permission", "create");
			$signChangeEvent->setCancelled();
		} else {
			$this->sendMessageContainer($player, "general.sign-success", $this->getName());
			$signChangeEvent->setLine(0, TF::AQUA . "[" . $this->getname() . "]");
		}
	}
}