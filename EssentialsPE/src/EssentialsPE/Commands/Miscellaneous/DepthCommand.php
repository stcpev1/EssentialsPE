<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class DepthCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "depth");
		$this->setPermission("essentials.command.depth");
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
		$this->sendMessageContainer($sender, "commands.depth." . (($pos = $sender->getFloorY() - 63) === 0 ? "at-sea-level" : ($pos > 0 ? "above" : "below")), abs($pos));
		return true;
	}
}