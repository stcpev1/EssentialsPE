<?php

namespace EssentialsPE\Commands\Chat;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SuffixCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "suffix");
		$this->setPermission("essentials.command.suffix.use");
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
		$suffix = ($n = strtolower($args[0])) === "off" || $n === "remove" || $n === "restore" || (bool) $n === false ? false : $args[0];
		$player = $sender;
		if(isset($args[1])) {
			if(!$sender->hasPermission("essentials.command.suffix.other")) {
				$this->sendMessageContainer($sender, "commands.suffix.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
				return true;
			}
		}
		if(!$suffix) {
			SessionManager::getSession($player)->clearSuffix();
		} elseif(!$sender->hasPermission("essentials.colorchat")) {
			$this->sendMessageContainer($sender, "error.color-codes-permission");
			return true;
		} elseif(!SessionManager::getSession($player)->setSuffix($suffix)) {
			$this->sendMessageContainer($sender, "commands.suffix.cancelled");
			return true;
		}
		$this->sendMessageContainer($player, "commands.suffix.self-" . (!$suffix ? "restore" : "change"), $suffix);
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.suffix.other-change", $player->getName(), $player->getDisplayName());
		}
		return true;
	}
}