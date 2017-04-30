<?php

namespace EssentialsPE\Commands;

use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;

class EssentialsPECommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "essentialspe", "Displays information about EssentialsPE", "[info|reload]", ["esspe", "ess", "essentials"]);
		$this->setPermission("essentials.command.essentials");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			$this->sendPermissionMessage($sender);
			return true;
		}

		switch(strtolower($args[0])) {
			default:
			case "info":
				$sender->sendMessage(TF::YELLOW . "You're using " . TF::AQUA . "EssentialsPE " . TF::YELLOW . "v" . TF::GREEN . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
				return true;
			case "reload":
				return true;
		}
	}
}