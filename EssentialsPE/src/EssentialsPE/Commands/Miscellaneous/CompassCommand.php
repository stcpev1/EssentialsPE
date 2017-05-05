<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class CompassCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "compass");
		$this->setPermission("essentials.command.compass");
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
		$directions = ["south", "west", "north", "east"];
		$this->sendMessageContainer($sender,
			"commands.compass." .
			(isset($directions[$sender->getDirection()]) ? "direction" : "unknown-direction"),
			["commands.compass." . $directions[$sender->getDirection()]]
		);
		return true;
	}
}