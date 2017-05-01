<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class EcoCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "eco", "Change the balance of players", "<give|take|set|reset> <player> <balance>", ["economy"]);
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
			$this->sendPermissionMessage($sender);
			return true;
		}
		switch(count($args)) {
			case 2:
			case 3:
				if(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
					$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["player-not-found"]);
					return true;
				}
				if((!isset($args[2]) && strtolower($args[0]) !== "reset") || (isset($args[2]) && !is_numeric($args[2]))) {
					$sender->sendMessage(TF::RED . "[Error] Please specify a" . (isset($args[2]) ? " valid" : "n") . " amount");
					return true;
				}
				$balance = (int)$args[2];
				switch(strtolower($args[0])) {
					case "give":
						$sender->sendMessage(TF::YELLOW /* TODO */);
						$this->getLoader()->getEconomyModule()->getProvider()->addToBalance($player, $balance);
						break;
					case "take":
						$sender->sendMessage(TF::YELLOW /* */);
						$this->getLoader()->getEconomyModule()->getProvider()->subtractFromBalance($player, $balance);
						break;
					case "set":
						$sender->sendMessage(TF::YELLOW /* */);
						$this->getLoader()->getEconomyModule()->getProvider()->setBalance($player, $balance);
						break;
					case "reset":
						$sender->sendMessage(TF::YELLOW /* */);
						$this->getLoader()->getEconomyModule()->getProvider()->setBalance($player, $this->getLoader()->getEconomyModule()->getConfiguration()->get("Default-Balance"));
						break;
				}
				break;
			default:
				$this->sendUsage($sender, $commandLabel);
				break;
		}
		return true;
	}
}