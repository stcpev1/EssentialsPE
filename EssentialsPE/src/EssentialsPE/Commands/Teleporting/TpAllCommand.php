<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Teleporting;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TpAllCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "tpall");
		$this->setPermission("essentials.command.tpall");
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
		$this->sendMessageContainer($player, "commands.tpall.confirmation");
		$c = $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.tpall.other-confirmation", $player->getDisplayName());
		foreach($this->getLoader()->getServer()->getOnlinePlayers() as $p) {
			if($p !== $player) {
				$p->sendMessage($c);
				$p->teleport($player);
			}
		}
		return true;
	}
}