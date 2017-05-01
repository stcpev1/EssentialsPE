<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class BalanceCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "balance", "Displays your current balance", ["bal", "money", "currency"]);
		$this->setPermission("essentials.command.balance.use");
		$this->setModule(Loader::MODULE_ECONOMY);
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			$this->sendPermissionMessage($sender);
			return true;
		}
		if(!isset($args[0]) && !$sender instanceof Player) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.command.balance.other")) {
				$this->sendPermissionMessage($sender);
				return true;
			} elseif(!$player = $this->getLoader()->getServer()->getPlayer($args[0])) {
				$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["player-not-found"]);
				return true;
			}
		}
		$sender->sendMessage(TF::AQUA . ($player === $sender ? "Your current balance is " : $player->getDisplayName() . TF::AQUA . " has ") . TF::YELLOW . $this->getLoader()->getEconomyModule()->getProvider()->getCurrencySymbol() . $this->getLoader()->getEconomyModule()->getProvider()->getBalance($player));
		$this->getLoader()->getEconomyModule()->getProvider()->getEconomyTop();
		return true;
	}
}