<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class GetPosCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "getpos");
		$this->setPermission("essentials.command.getpos.use");
		$this->setModule(Loader::MODULE_ESSENTIALS);
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
		if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.getpos.other")) {
				$this->sendMessageContainer($sender, "commands.getpos.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		$this->sendMessageContainer($sender, "commands.getpos." . ($player === $sender ? "self" : "other") . "-location", $player->getLevel()->getName(), $player->getX(), $player->getY(), $player->getZ(), $player->getDisplayName());
		return true;
	}
}