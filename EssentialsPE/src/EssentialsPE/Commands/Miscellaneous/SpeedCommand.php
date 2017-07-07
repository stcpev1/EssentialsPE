<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\Player;

class SpeedCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "speed");
		$this->setPermission("essentials.command.speed.use");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if($this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player || count($args) < 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!is_numeric($args[0])) {
			$this->sendMessageContainer($sender, "commands.speed.invalid");
			return true;
		}
		$player = $sender;
		if(isset($args[1]) && !($player = $this->getLoader()->getServer()->getPlayer($args[1]))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $args[1]);
			return true;
		}
		if($player !== $sender && !$sender->hasPermission("essentials.command.speed.other")) {
			return false;
		}

		if((int) $args[0] === 0) {
			$player->removeEffect(Effect::SPEED);
		} else {
			$effect = Effect::getEffect(Effect::SPEED);
			$effect->setAmplifier((int) $args[0]);
			$effect->setDuration(PHP_INT_MAX);
			$player->addEffect($effect);
		}
		$this->sendMessageContainer($sender, "commands.speed.applied", $args[0]);
		return true;
	}
}