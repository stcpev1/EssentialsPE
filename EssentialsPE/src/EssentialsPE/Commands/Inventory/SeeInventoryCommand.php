<?php

namespace EssentialsPE\Commands\Inventory;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\inventory\PlayerInventory;
use pocketmine\Player;

class SeeInventoryCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "seeinventory");
		$this->setPermission("essentials.command.seeinventory");
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
		if(count($args) !== 1 || !$sender instanceof Player) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(($player = $this->getLoader()->getServer()->getPlayer($args[0])) === null) {
			$this->sendMessageContainer($sender, "error.player-not-found");
			return true;
		}
		$contents = $player->getInventory()->getContents();
		$window = new PlayerInventory($sender);
		$window->setContents($contents);
		$sender->addWindow($window);
		$this->sendMessageContainer($sender, "commands.seeinventory.success", $player->getName());
		return true;
	}
}