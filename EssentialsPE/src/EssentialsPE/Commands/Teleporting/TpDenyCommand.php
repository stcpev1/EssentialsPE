<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Teleporting;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TpDenyCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "tpdeny");
		$this->setPermission("essentials.command.tpdeny");
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
		if(!$sender instanceof Player) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		$session = SessionManager::getSession($sender);
		if(!($request = $session->hasARequest())) {
			$this->sendMessageContainer($sender, "commands.tpa.no-requests");
			return true;
		}
		switch(count($args)) {
			case 0:
				$playerName = null;
				foreach($session->getLatestRequest() as $name => $request) {
					$playerName = $name;
				}
				if(!($player = $this->getLoader()->getServer()->getPlayer($playerName))) {
					$this->sendMessageContainer($sender, "commands.tpa.not-available", $playerName);
					return true;
				}
				if(!$session->hasAValidRequestFrom($player)) {
					$this->sendMessageContainer($sender, "commands.tpa.no-requests-from", $player->getDisplayName());
					return true;
				}
				break;
			case 1:
				if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
					$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
					return true;
				}
				if(!$session->hasAValidRequestFrom($player)) {
					$this->sendMessageContainer($sender, "commands.tpa.no-requests-from", $player->getDisplayName());
					return true;
				}
				break;
			default:
				$this->sendUsage($sender, $commandLabel);
				return true;
				break;
		}
		$session->removeRequest($player);
		$this->sendMessageContainer($player, "commands.tpdeny.other-notice", $sender->getDisplayName());
		$this->sendMessageContainer($sender, "commands.tpdeny.confirmation", $player->getDisplayName());
		return true;
	}
}