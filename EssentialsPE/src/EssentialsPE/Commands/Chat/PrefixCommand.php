<?php

namespace EssentialsPE\Commands\Chat;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PrefixCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "prefix");
		$this->setPermission("essentials.command.prefix.use");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if((!isset($args[1]) && !$sender instanceof Player) || (count($args) < 1 || count($args) > 2)) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$prefix = ($n = strtolower($args[0])) === "off" || $n === "remove" || $n === "restore" || (bool) $n === false ? false : $args[0];
		$player = $sender;
		if(isset($args[1])) {
			if(!$sender->hasPermission("essentials.command.prefix.other")) {
				$this->sendMessageContainer($sender, "commands.prefix.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
				return true;
			}
		}
		if(!$prefix) {
			SessionManager::getSession($player)->clearPrefix();
		} elseif(!$sender->hasPermission("essentials.colorchat")) {
			$this->sendMessageContainer($sender, "error.color-codes-permission");
			return true;
		} elseif(!SessionManager::getSession($player)->setPrefix($prefix)) {
			$this->sendMessageContainer($sender, "commands.prefix.cancelled");
			return true;
		}
		$this->sendMessageContainer($player, "commands.prefix.self-" . (!$prefix ? "restore" : "change"), $prefix);
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.prefix.other-change", $player->getName(), $player->getDisplayName());
		}
		return true;
	}
}