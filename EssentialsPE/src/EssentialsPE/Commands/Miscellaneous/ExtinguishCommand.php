<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ExtinguishCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "extinguish");
		$this->setPermission("essentials.command.extinguish.use");
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
			if(!$sender->hasPermission("essentials.extinguish.other")) {
				$this->sendMessageContainer($sender, "commands.extinguish.other-permission");
				return true;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return true;
			}
		}
		$player->extinguish();
		$this->sendMessageContainer($player, "commands.extinguish.self");
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.extinguish.other", $player->getDisplayName());
		}
		return true;
	}
}