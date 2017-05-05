<?php

namespace EssentialsPE\Commands\Override;


use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class KillCommand extends BaseOverrideCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "kill");
		$this->setPermission("essentials.command.kill.use");
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
		if(!$sender instanceof Player && count($args) !== 1) {
			$this->sendUsage($sender, $commandLabel);
			return false;
		}
		$player = $sender;
		if(isset($args[0])) {
			if(!$sender->hasPermission("essentials.kill.other")) {
				$this->sendMessageContainer($sender, "commands.kill.other-permission");
				return false;
			}
			if(!($player = $this->getLoader()->getServer()->getPlayer($args[0])) instanceof Player) {
				$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
				return false;
			}
		}
		/*if($this->getAPI()->isGod($player)){
			$this->sendTranslation($sender, "commands.kill.exempt", $player->getDisplayName()); TODO: Uncomment this once sessions are re-implemented
			return false;
		}*/
		$this->getLoader()->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, ($player->getHealth())));
		if($ev->isCancelled()) {
			return true;
		}
		$player->setLastDamageCause($ev);
		$player->setHealth(0);
		return true;
	}
}