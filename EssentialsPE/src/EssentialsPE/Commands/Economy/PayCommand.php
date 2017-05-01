<?php

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class PayCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "pay", "Pays another player from your balance", "<player> <amount>");
		$this->setPermission("essentials.command.pay");
		$this->setModule(Loader::MODULE_ECONOMY);
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			$this->sendPermissionMessage($sender);
			return true;
		}
		if(!$sender instanceof Player || count($args) !== 2) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(!($player = $this->getLoader()->getServer()->getPlayer($args[0]))) {
			$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["player-not-found"]);
			return true;
		}
		if($sender === $player) {
			$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["pay"]["self-pay"]);
			return true;
		}
		if(substr($args[1], 0, 1) === "-") {
			$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["pay"]["negative-value"]);
			return true;
		}
		$balance = $this->getLoader()->getEconomyModule()->getProvider()->getBalance($sender);
		$newBalance = $balance - (int)$args[1];
		if($balance < $args[1] || $newBalance < $this->getLoader()->getEconomyModule()->getConfiguration()->get("Minimum-Balance") || ($newBalance < 0 && !$player->hasPermission("essentials.eco.loan"))) {
			$sender->sendMessage(TF::RED . "[Error] " . $this->getMessages()->getMessages()["command"]["error"]["pay"]["not-enough-money"]);
			return true;
		}
		$sender->sendMessage(TF::YELLOW . $this->getMessages()->setComponents($this->getMessages()->getMessages()["command"]["succeed"]["pay"], [(int) $args[1], $player->getName()]));
		$this->getLoader()->getEconomyModule()->getProvider()->setBalance($sender, $newBalance);
		$this->getLoader()->getEconomyModule()->getProvider()->addToBalance($player, (int)$args[1]);
		return true;
	}
}