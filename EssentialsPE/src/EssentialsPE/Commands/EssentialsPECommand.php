<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class EssentialsPECommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "essentialspe");
		$this->setPermission("essentials.command.essentialspe");
		$this->setModule(Loader::MODULE_ESSENTIALS);
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

		switch(strtolower($args[0])) {
			default:
			case "version":
				$this->sendMessageContainer($sender, "general.version", $this->getPlugin()->getDescription()->getVersion());
				break;
		}
		return true;
	}
}