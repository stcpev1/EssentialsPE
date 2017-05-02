<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PayCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "pay");
		$this->setPermission("essentials.command.pay");
		$this->setModule(Loader::MODULE_ECONOMY);
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
		if(($args[1] = (int)$args[1]) < 1) {
			$this->sendMessageContainer($sender, "error.invalid-amount");
			return true;
		}
		$balance = $this->getLoader()->getEconomyModule()->getProvider()->getBalance($sender);
		$newBalance = $balance - $args[1];
		if($balance < $args[1] || $newBalance < $this->getLoader()->getEconomyModule()->getConfiguration()->get("Minimum-Balance") || ($newBalance < 0 && !$player->hasPermission("essentials.eco.loan"))) {
			$this->sendMessageContainer($sender, "commands.pay.profit");
			return true;
		}
		$this->sendMessageContainer($sender, "commands.pay.confirmation", $this->getLoader()->getEconomyModule()->getProvider()->getCurrencySymbol() . $args[1], $player->getDisplayName());
		$this->getLoader()->getEconomyModule()->getProvider()->setBalance($sender, $newBalance);
		$this->getLoader()->getEconomyModule()->getProvider()->addToBalance($player, $args[1]);
		return true;
	}
}