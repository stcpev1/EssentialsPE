<?php

namespace EssentialsPE\Commands\Chat;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class NickCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "nick");
		$this->setPermission("essentials.command.nick.use");
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
		$nick = ($n = strtolower($commandLabel[0])) === "off" || $n === "remove" || $n === "restore" || (bool) $n === false ? false : $args[0];
		$player = $sender;
		if(isset($args[1])) {
			if(!$sender->hasPermission("essentials.command.nick.other")) {
				$this->sendMessageContainer($sender, "commands.nick.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
				return true;
			}
		}
		if(!$nick) {
			SessionManager::getSession($player)->clearNick();
		} elseif(!$sender->hasPermission("essentials.colorchat")) {
			$this->sendMessageContainer($sender, "error.color-codes-permission");
			return true;
		} elseif(!SessionManager::getSession($player)->setNick($nick)) {
			$this->sendMessageContainer($sender, "commands.nick.cancelled");
			return true;
		}
		$this->sendMessageContainer($player, "commands.nick.self-" . (!$nick ? "restore" : "change"), $nick);
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.nick.other-change", $player->getName(), $player->getDisplayName());
		}
		return true;
	}
}