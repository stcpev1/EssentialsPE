<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class GodCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "god");
		$this->setPermission("essentials.command.god.use");
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
			if(!$sender->hasPermission("essentials.command.god.other")) {
				$this->sendMessageContainer($sender, "commands.god.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		SessionManager::getSession($player)->switchGod();
		$this->sendMessageContainer($player, "commands.god.self-" . ($t = SessionManager::getSession($player)->isGod() ? "enabled" : "disabled"));
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.god.other-" . $t, $player->getDisplayName());
		}
		return true;
	}
}