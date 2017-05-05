<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class SuicideCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "suicide");
		$this->setPermission("essentials.command.suicide");
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
		if(!$sender instanceof Player || count($args) !== 0) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$this->getLoader()->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, ($sender->getHealth())));
		if($ev->isCancelled()) {
			return true;
		}
		$sender->setLastDamageCause($ev);
		$sender->setHealth(0);
		$this->sendMessageContainer($sender, "commands.suicide.message");
		return true;
	}
}