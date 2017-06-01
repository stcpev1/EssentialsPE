<?php

namespace EssentialsPE\Commands\Warps;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class WarpCommand extends BaseWarpCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "warp");
		$this->setPermission("essentials.command.warp.use");
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
		if(count($args) === 0) {
			if(empty($list = $this->getWarps()->getWarpList())) {
				$this->sendMessageContainer($sender, "commands.warp.no-warps-available");
				return true;
			}
			$this->sendMessageContainer($sender, "commands.warp.list-warps", $list);
			return true;
		}
		if(!$this->getWarps()->warpExists($args[0])) {
			$this->sendMessageContainer($sender, "commands.warp.not-exists", $args[0]);
			return false;
		}
		if(!isset($args[1]) && !$sender instanceof Player) {
			$this->sendUsage($sender, $commandLabel);
			return false;
		}
		$warp = $this->getWarps()->getWarp($args[0]);
		$player = $sender;
		if(isset($args[1])) {
			if(!$sender->hasPermission("essentials.command.warp.other")) {
				$this->sendMessageContainer($sender, "commands.warp.other-permission");
				return false;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
				return false;

			}
		}
		if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission($warp->getPermission())) {
			$this->sendMessageContainer($sender, "commands.warp.need-permission", $args[0]);
			return false;
		}
		$warp->teleportTo($player);
		$this->sendMessageContainer($player, "commands.warp.self-confirmation", $warp->getName());
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.warp.other-confirmation", $player->getDisplayName(), $warp->getName());
		}
		return true;
	}
}