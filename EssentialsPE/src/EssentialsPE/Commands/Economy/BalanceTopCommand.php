<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class BalanceTopCommand extends EconomyCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "balancetop");
		$this->setPermission("essentials.command.balancetop");
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
		$this->sendMessageContainer($sender, "commands.balancetop.top");
		foreach($this->getEconomyProvider()->getEconomyTop() as $playerName => $balance) {
			$sender->sendMessage($playerName . " - " . TF::GREEN . $balance);
		}
		return true;
	}
}