<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WorldCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "world");
		$this->setPermission("essentials.command.world");
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
		if(!$sender instanceof Player || count($args) !== 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!$sender->hasPermission("essentials.worlds.*") && !$sender->hasPermission("essentials.worlds." . strtolower($args[0]))) {
			$this->sendMessageContainer($sender, "commands.world.need-permission", $args[0]);
			return true;
		}
		if(!$this->getLoader()->getServer()->isLevelGenerated($args[0])) {
			$this->sendMessageContainer($sender, "commands.world.not-exists", $args[0]);
			return true;
		} elseif(!$this->getLoader()->getServer()->isLevelLoaded($args[0])) {
			$this->sendMessageContainer($sender, "commands.world.loading-world", $args[0]);
			if(!$this->getLoader()->getServer()->loadLevel($args[0])) {
				$this->sendMessageContainer($sender, "commands.world.load-error", $args[0]);
				return true;
			}
		}
		$this->sendMessageContainer($sender, "commands.world.teleport", $args[0]);
		$sender->teleport($this->getLoader()->getServer()->getLevelByName($args[0])->getSpawnLocation(), 0, 0);
		return true;
	}
}