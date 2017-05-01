<?php

namespace EssentialsPE\Commands;

use EssentialsPE\Configurable\MessagesContainer;
use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

	protected $loader;
	protected $module;

	public function __construct(Loader $loader, $name, $description = "", $usageMessage = null, $aliases = []) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getPlugin(): Loader {
		return $this->loader;
	}

	/**
	 * @return int
	 */
	public function getModule(): int {
		return $this->module;
	}

	/**
	 * @param int $moduleId
	 */
	protected function setModule(int $moduleId) {
		$this->module = $moduleId;
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

	/**
	 * @param CommandSender $sender
	 */
	protected function sendPermissionMessage(CommandSender $sender) {
		$sender->sendMessage(TF::RED . "[Error] " . $this->getPermissionMessage());
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $alias
	 */
	protected function sendUsage(CommandSender $sender, string $alias) {
		if(!$sender instanceof Player) {
			$sender->sendMessage(TF::RED . "[Error] "/* TODO */);
			return;
		}
		$sender->sendMessage(TF::RED . "[Usage] /" . $alias . " " . $this->getUsage());
	}

	/**
	 * @return Config
	 */
	protected function getConfig(): Config {
		return $this->getLoader()->getConfig();
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return MessagesContainer
	 */
	public function getMessages(): MessagesContainer {
		return $this->getLoader()->getConfigurableData()->getMessagesContainer();
	}
}