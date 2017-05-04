<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Loader;
use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\command\CommandSender;

class EcoCommand extends EconomyCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "eco");
		$this->setPermission("essentials.command.eco");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return mixed
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(count($args) < 2 || count($args) > 3) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
			$this->sendMessageContainer($sender, "error.player-not-found");
			return true;
		}
		if((!isset($args[2]) && strtolower($args[0]) !== "reset") || (isset($args[2]) && !is_numeric($args[2]))) {
			$this->sendMessageContainer($sender, "error.invalid-amount");
			return true;
		}
		$balance = (int)$args[2];
		$economyModule = $this->getLoader()->getModule(Loader::MODULE_ECONOMY);
		if(!$economyModule instanceof EssentialsPEconomy) {
			return false;
		}
		switch(strtolower($args[0])) {
			case "give":
			case "add":
				$this->sendMessageContainer($sender, "commands.balance.add", $balance);
				if($this->getEconomyProvider()->addToBalance($player, $balance) === false) {
					$this->sendMessageContainer($sender, "commands.balance.above-limit");
				}
				break;
			case "reset":
				$this->sendMessageContainer($sender, "commands.balance.reset");
				$this->getEconomyProvider()->setBalance($player, $economyModule->getConfiguration()->get("Default-Balance"));
				break;
			case "set":
				$this->sendMessageContainer($sender, "commands.balance.set", $balance);
				if($this->getEconomyProvider()->setBalance($player, $balance) === false) {
					$this->sendMessageContainer($sender, "commands.balance.below-limit");
				}
				break;
			case "take":
				$this->sendMessageContainer($sender, "commands.balance.take", $balance);
				if($this->getEconomyProvider()->subtractFromBalance($player, $balance) === false) {
					$this->sendMessageContainer($sender, "commands.balance.out-limit");
				}
				break;
		}
		return true;
	}
}