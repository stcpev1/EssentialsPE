<?php

namespace EssentialsPE\Commands\Warps;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SetWarpCommand extends BaseWarpCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "setwarp");
		$this->setPermission("essentials.command.setwarp");
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
			return false;
		}
		if(($existed = $this->getWarps()->warpExists($args[0])) && !$sender->hasPermission("essentials.command.setwarp.override.*") && !$sender->hasPermission("essentials.command.setwarp.override." . $args[0])) {
			$this->sendMessageContainer($sender, "commands.setwarp.update-permission", $args[0]);
			return false;
		}
		if(!$this->getWarps()->createWarp($args[0], $sender->getLocation())) {
			$this->sendMessageContainer($sender, "error.invalid-name");
			return false;
		}
		$this->sendMessageContainer($sender, "commands.setwarp." . ($existed ? "updated" : "created"), $args[0]);
		return true;
	}
}