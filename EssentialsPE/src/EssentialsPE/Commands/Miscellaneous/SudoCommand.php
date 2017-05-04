<?php

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;

class SudoCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "sudo");
		$this->setPermission("essentials.command.sudo");
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
		if(count($args) < 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($name = array_shift($args)))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $name);
			return true;
		} elseif($player->hasPermission("essentials.sudo.exempt")) {
			$this->sendMessageContainer($sender, "commands.sudo.exempt-sudo");
			return true;
		}
		$cmd = implode(" ", $args);
		if(substr($cmd, 0, 2) === "c:") {
			$this->getLoader()->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, $m = substr($cmd, 2)));
			if(!$ev->isCancelled()) {
				$this->sendMessageContainer($sender, "commands.sudo.sending-message", $m, $player->getDisplayName());
				$this->getLoader()->getServer()->broadcastMessage(\sprintf($ev->getFormat(), $ev->getPlayer()->getDisplayName(), $ev->getMessage()), $ev->getRecipients());
			}
		} else {
			$this->getLoader()->getServer()->dispatchCommand($player, $cmd);
			$this->sendMessageContainer($sender, "commands.sudo.sending-command", $cmd, $player->getDisplayName());
		}
		return true;
	}
}