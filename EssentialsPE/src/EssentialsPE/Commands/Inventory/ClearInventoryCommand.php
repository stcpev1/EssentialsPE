<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Inventory;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ClearInventoryCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "clearinventory");
		$this->setPermission("essentials.command.clearinventory.use");
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
		if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
			$this->sendUsage($sender, $commandLabel);
			return false;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.commands.clearinventory.other")) {
				$this->sendMessageContainer($sender, "commands.clearinventory.other-permission");
				return false;
			} elseif(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return false;
			}
		}
		if(($gm = $player->getGamemode()) === Player::CREATIVE || $gm === Player::ADVENTURE) {
			$gm = $this->getLoader()->getServer()->getGamemodeString($gm);
			if($player === $sender) {
				$this->sendMessageContainer($sender, "error.gamemode-error", $gm);
			} else {
				$this->sendMessageContainer($sender, "error.other-gamemode-error", $player->getDisplayName(), $gm);
			}
			return false;
		}
		$player->getInventory()->clearAll();
		$this->sendMessageContainer($player, "commands.clearinventory.confirmation");
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.clearinventory.other-confirmation");
		}
		return true;
	}
}