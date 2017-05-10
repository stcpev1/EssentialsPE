<?php

namespace EssentialsPE\Commands;

use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

	protected $loader;
	protected $module = Loader::MODULE_ESSENTIALS;
	protected $consoleUsable;
	protected $consoleUsageMessage;

	/**
	 * @param Loader $loader
	 * @param string $name
	 */
	public function __construct(Loader $loader, string $name) {
		$this->loader = $loader;
		$t = $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands." . $name);
		parent::__construct($t["name"], $t["description"], $t["usage"], $t["alias"] ?? []);
		$this->consoleUsable = $t["console-usage"] !== false;
		if(is_bool($t["console-usage"])) {
			$this->consoleUsageMessage = (!$t["console-usage"] ? $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("error.run-in-game", $this->getName()) : parent::getUsage());
		} else {
			$this->consoleUsageMessage = $t["console-usage"];
		}
		$this->setPermissionMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("error.need-permission"));
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
	 * Function to give different type of usages, switching from "Console" and "Player" executors of a command.
	 * This function can be overridden to fit any command needs...
	 *
	 * @param CommandSender $sender
	 * @param string        $alias
	 */
	public function sendUsage(CommandSender $sender, string $alias) {
		$sender->sendMessage(str_replace($this->getName(), $alias, $this->isUsableByConsole() ?
			$this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("error.command-usage", $alias, ($sender instanceof Player ? $this->getUsage() : $this->consoleUsageMessage)) :
			$this->consoleUsageMessage
		));
	}

	/**
	 * @return bool
	 */
	protected function isUsableByConsole(): bool {
		return $this->consoleUsable;
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $message
	 * @param array         ...$replacements
	 */
	public function sendMessageContainer(CommandSender $sender, string $message, ...$replacements) {
		$sender->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage($message, ...$replacements));
	}

	/**
	 * @param CommandSender $sender
	 */
	protected function sendPermissionMessage(CommandSender $sender) {
		$sender->sendMessage(TF::RED . "[Error] " . $this->getPermissionMessage());
	}
}