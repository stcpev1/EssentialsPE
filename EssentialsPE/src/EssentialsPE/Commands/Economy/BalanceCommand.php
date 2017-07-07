<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BalanceCommand extends EconomyCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "balance");
		$this->setPermission("essentials.command.balance.use");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.command.balance.other")) {
				$sender->sendMessage($this->getPermissionMessage());
				return true;
			} elseif(!$player = $this->getLoader()->getServer()->getPlayer($args[0])) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		$this->sendMessageContainer($sender, "commands.balance." . ($player === $sender ? "self" : "other"), $this->getEconomyProvider()->getCurrencySymbol() . $this->getEconomyProvider()->getBalance($player), $player->getDisplayName());
		return true;
	}
}