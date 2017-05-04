<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TopCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "top");
		$this->setPermission("essentials.command.top");
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
		if(!$sender instanceof Player || count($args) !== 0) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$this->sendMessageContainer($sender, "general.teleport-confirmation");
		$sender->teleport($sender->x, $sender->level->getHighestBlockAt($sender->x, $sender->z) + 1, $sender->z);
		return true;
	}
}