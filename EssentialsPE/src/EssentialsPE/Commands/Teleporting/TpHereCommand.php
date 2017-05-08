<?php

namespace EssentialsPE\Commands\Teleporting;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TpHereCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "tphere");
		$this->setPermission("essentials.command.tphere");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player || count($args) !== 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
			return true;
		}
		$this->sendMessageContainer($sender, "commands.tphere.confirmation", $player->getDisplayName());
		$this->sendMessageContainer($player, "commands.tphere.other-confirmation...", $sender->getDisplayName());
		$player->teleport($sender);
		return true;
	}
}