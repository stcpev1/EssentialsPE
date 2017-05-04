<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class PingCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "ping");
		$this->setPermission("essentials.command.ping");
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
		$this->sendMessageContainer($sender, "commands.ping.pong");
		return true;
	}
}