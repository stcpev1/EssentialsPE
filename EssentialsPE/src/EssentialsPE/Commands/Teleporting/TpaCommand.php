<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Teleporting;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Sessions\SessionManager;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TpaCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "tpa");
		$this->setPermission("essentials.command.tpa");
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
		if(!$sender instanceof Player || count($args) !== 1) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$this->sendMessageContainer($sender, "error.player-not-found", $args[0]);
			return true;
		}
		if($player->getName() === $sender->getName()) {
			$this->sendMessageContainer($sender, "commands.tpa.self-request");
			return true;
		}
		SessionManager::getSession($player)->sendTeleportRequestFrom($sender);
		$player->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.tpa.tpto", $sender->getDisplayName()) . $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.tpa.syntax"));
		$this->sendMessageContainer($sender, "commands.tpa.confirmation", $player->getDisplayName());
		return true;
	}
}