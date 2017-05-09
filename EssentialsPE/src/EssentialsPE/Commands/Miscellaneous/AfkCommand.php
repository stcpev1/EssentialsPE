<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class AfkCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "afk");
		$this->setPermission("essentials.command.afk.use");
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
		if(!isset($args[0]) && !$sender instanceof Player) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.command.afk.other")) {
				$this->sendMessageContainer($sender, "commands.afk.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		SessionManager::getSession($player)->switchAfk($this->getLoader()->getConfigurableData()->getConfiguration()->get("Afk.Broadcast"));
		return true;
	}
}