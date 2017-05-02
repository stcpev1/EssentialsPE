<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BalanceCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "balance");
		$this->setPermission("essentials.command.balance.use");
		$this->setModule(Loader::MODULE_ECONOMY);
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.balance.other")) {
				$sender->sendMessage($this->getPermissionMessage());
				return true;
			} elseif(!$player = $this->getLoader()->getServer()->getPlayer($args[0])) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		$this->sendMessageContainer($sender, "commands.balance." . ($player === $sender ? "self" : "other"), $this->getLoader()->getEconomyModule()->getProvider()->getCurrencySymbol() . $this->getLoader()->getEconomyModule()->getProvider()->getBalance($player), $player->getDisplayName());
		return true;
	}
}