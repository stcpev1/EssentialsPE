<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class BurnCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "burn");
		$this->setPermission("essentials.command.burn");
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
		if(count($args) !== 2) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
			return true;
		}
		if(!is_numeric($time = $args[1]) or (int)$time < 0) {
			$this->sendMessageContainer($sender, "commands.burn.invalid-time");
			return true;
		}
		$player->setOnFire($time);
		$this->sendMessageContainer($sender, "commands.burn.confirmation", $player->getDisplayName());
		return true;
	}
}