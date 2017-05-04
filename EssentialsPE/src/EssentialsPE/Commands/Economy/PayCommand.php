<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Loader;
use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PayCommand extends EconomyCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "pay");
		$this->setPermission("essentials.command.pay");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player || count($args) !== 2) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$this->sendMessageContainer($sender, "error.player-not-found");
			return true;
		}
		$economyModule = $this->getLoader()->getModule(Loader::MODULE_ECONOMY);
		if(!$economyModule instanceof EssentialsPEconomy) {
			return false;
		}
		if(($args[1] = (int)$args[1]) < 1) {
			$this->sendMessageContainer($sender, "error.invalid-amount");
			return true;
		}
		$balance = $this->getEconomyProvider()->getBalance($sender);
		$newBalance = $balance - $args[1];
		if($balance < $args[1] || $newBalance < $economyModule->getConfiguration()->get("Minimum-Balance") || ($newBalance < 0 && !$player->hasPermission("essentials.eco.loan"))) {
			$this->sendMessageContainer($sender, "commands.pay.profit");
			return true;
		}
		if(($newTargetBalance = $this->getEconomyProvider()->getBalance($player) + $args[1]) > $economyModule->getConfiguration()->get("Maximum-Balance")) {
			$this->sendMessageContainer($sender, "commands.pay.excessive");
			return true;
		}
		$this->sendMessageContainer($sender, "commands.pay.confirmation", $this->getEconomyProvider()->getCurrencySymbol() . $args[1], $player->getDisplayName());
		$this->getEconomyProvider()->setBalance($sender, $newBalance);
		$this->getEconomyProvider()->addToBalance($player, $args[1]);
		return true;
	}
}