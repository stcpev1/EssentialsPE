<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class FeedCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "feed");
		$this->setPermission("essentials.command.feed.use");
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
		if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$player = $sender;
		if(isset($args[0]) && !($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$sender->sendMessage(TF::RED . "[Error] Player not found");
			return false;
		}
		if($player->getName() !== $sender->getName() && !$sender->hasPermission("essentials.feed.other")) {
			$sender->sendMessage(TF::RED . $this->getPermissionMessage());
			return false;
		}
		$player->setFood(20);
		$player->getLevel()->addParticle(new HappyVillagerParticle($player->add(0, 2), 4));
		$this->sendMessageContainer($player, "commands.feed.confirmation");
		if($player !== $sender) {
			$this->sendMessageContainer($sender, "commands.feed.other-confirmation", $player->getDisplayName());
		}
		return true;
	}
}