<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Warps;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class DelWarpCommand extends BaseWarpCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "delwarp");
		$this->setPermission("essentials.command.delwarp");
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
		if(count($args) !== 1) {
			$this->sendUsage($sender, $commandLabel);
			return false;
		}
		if(!$this->getWarps()->warpExists($args[0])) {
			$this->sendMessageContainer($sender, "commands.warp.not-exists");
			return false;
		}
		if(!$sender->hasPermission("essentials.command.delwarp.*") && !$sender->hasPermission("essentials.command.delwarp." . $args[0])) {
			$this->sendMessageContainer($sender, "commands.delwarp.need-permission");
			return false;
		}
		$this->getWarps()->deleteWarp($args[0]);
		$this->sendMessageContainer($sender, "commands.delwarp.confirmation", $args[0]);
		return true;
	}
}