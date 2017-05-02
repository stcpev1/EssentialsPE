<?php

namespace EssentialsPE\Commands\Chat;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class BroadcastCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "broadcast");
		$this->setPermission("essentials.command.broadcast");
		$this->setModule(Loader::MODULE_ESSENTIALS);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(count($args) < 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$sender->getServer()->broadcastMessage(TF::RED . "[Broadcast] " . TF::RESET . implode(" ", $args));
		return true;
	}
}