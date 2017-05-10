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
			return false;
		}
		$nick = ($n = strtolower($commandLabel[0])) === "off" || $n === "remove" || $n === "restore" || (bool) $n === false ? false : $args[0];
		$player = $sender;
		if(isset($args[1])) {
			if(!$sender->hasPermission("essentials.command.nick.other")) {
				$this->sendMessageContainer($sender, "commands.nick.other-permission");
				return false;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
				return false;
			}
		}
		if(!$nick) {
			SessionManager::getSession($player)->clearNick();
		} elseif(!$sender->hasPermission("essentials.colorchat")) {
			$this->sendTranslation($sender, "error.color-codes-permission");
			return false;
		} elseif(!$this->getAPI()->setNick($player, $nick)) {
			$this->sendTranslation($sender, "commands.nick.cancelled");
			return false;
		}
		$this->sendTranslation($player, "commands.nick.self-" . (!$nick ? "restore" : "change"), $nick);
		if($player !== $sender) {
			$this->sendTranslation($sender, "commands.nick.other-change", $player->getName(), $player->getDisplayName());
		}
		return true;
	}
}