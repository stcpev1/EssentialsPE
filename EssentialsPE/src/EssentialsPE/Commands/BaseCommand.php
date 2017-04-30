<?php

namespace EssentialsPE\Commands;

use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

	protected $loader;

	public function __construct(Loader $loader, $name, $description = "", $usageMessage = null, $aliases = []) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->loader  = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return Loader
	 */
	public function getPlugin(): Loader {
		return $this->loader;
	}

	/**
	 * @param CommandSender $sender
	 *
	 * @return bool
	 */
	protected function checkForConsole(CommandSender $sender): bool {
		if($sender instanceof Player) {
			return false;
		}
		$sender->sendMessage(TF::RED /* TODO */);
		return true;
	}

	/**
	 * @param CommandSender $sender
	 */
	protected function sendPermissionMessage(CommandSender $sender) {
		$sender->sendMessage(TF::RED . "[Error] " . $this->getPermissionMessage());
	}

	/**
	 * @return Config
	 */
	protected function getConfig(): Config {
		return $this->getLoader()->getConfig();
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function generateCustomCommandData(Player $player): array {
		$commandData = parent::generateCustomCommandData($player);

		$commandData["overloads"]["default"]["input"]["parameters"] = CommandOverloads::getOverloads($this->getName());
		return $commandData;
	}
}