<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SetSpawnCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "setspawn");
		$this->setPermission("essentials.command.setspawn");
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
		if(!$sender instanceof Player || count($args) != 0) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$sender->getLevel()->setSpawnLocation($sender);
		$this->getLoader()->getServer()->setDefaultLevel($sender->getLevel());
		$this->sendMessageContainer($sender, "commands.setspawn.confirmation");
		$this->getLoader()->getLogger()->info($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.setspawn.console-confirmation", $sender->getLevel()->getName(), $sender->getName()));
		return true;
	}
}