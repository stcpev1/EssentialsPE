<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\particle\HeartParticle;
use pocketmine\Player;

class HealCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "heal");
		$this->setPermission("essentials.command.heal.use");
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
			return true;
		}
		$player = $sender;
		if(isset($args[0]) && !($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
			return true;
		}
		if($player !== $sender && !$sender->hasPermission("essentials.feed.other")) {
			return false;
		}
		$player->heal($player->getMaxHealth(), new EntityRegainHealthEvent($player, $player->getMaxHealth() - $player->getHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
		$player->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4));
		$this->sendMessageContainer($player, "commands.heal.confirmation");
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.heal.other-confirmation", $player->getDisplayName());
		}
		return true;
	}
}