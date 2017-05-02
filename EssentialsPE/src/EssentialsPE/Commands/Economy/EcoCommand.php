<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class EcoCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "eco");
		$this->setPermission("essentials.command.eco");
		$this->setModule(Loader::MODULE_ECONOMY);
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
		switch(strtolower($args[0])) {
			case "give":
			case "add":
				$this->sendMessageContainer($sender, "commands.balance.add", $balance);
				$this->getLoader()->getEconomyModule()->getProvider()->addToBalance($player, $balance);
				break;
			case "reset":
				$this->sendMessageContainer($sender, "commands.balance.reset");
				$this->getLoader()->getEconomyModule()->getProvider()->setBalance($player, $this->getLoader()->getEconomyModule()->getConfiguration()->get("Default-Balance"));
				break;
			case "set":
				$this->sendMessageContainer($sender, "commands.balance.set");
				$this->getLoader()->getEconomyModule()->getProvider()->setBalance($player, $balance);
				break;
			case "take":
				$this->sendMessageContainer($sender, "commands.balance.take", $balance);
				$this->getLoader()->getEconomyModule()->getProvider()->addToBalance($player, -$balance);
				break;
		}
		return true;
	}
}