<?php
namespace EssentialsPE;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Commands\CommandOverloads;
use EssentialsPE\Commands\EssentialsPECommand;
use EssentialsPE\Commands\Miscellaneous\BreakCommand;
use EssentialsPE\Configurable\DataManager;
use EssentialsPE\EventHandlers\SpecialSigns\SignBreak;
use EssentialsPE\EventHandlers\SpecialSigns\TeleportSign;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	const MODULE_ESSENTIALS = 0;

	private $configurableData;
	private $installedModules = [];

	public function onLoad() {
		$this->addModule(self::MODULE_ESSENTIALS);

		CommandOverloads::initialize();
		$this->registerCommands();
		$this->registerEventHandlers();
	}

	public function onEnable() {
		if(!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder());
		}
		$this->configurableData = new DataManager($this);
	}

	public function onDisable() {
		$this->getConfigurableData()->saveAll();
	}

	public function registerCommands() {
		$essentialsCommands = [
			"essentialspe" => new EssentialsPECommand($this),
			"break" => new BreakCommand($this)
		];
		foreach($essentialsCommands as $fallBack => $essentialsCommand) {
			if(in_array($fallBack, $this->getConfigurableData()->getCommandSwitch()->getAvailableCommands())) {
				if($essentialsCommand instanceof BaseCommand) {
					if($this->isModuleLoaded($essentialsCommand->getModule())) {
						$this->getServer()->getCommandMap()->register($fallBack, $essentialsCommand);
					}
				}
			}
		}
	}

	public function registerEventHandlers() {
		$essentialsEventHandlers = [
			new SignBreak($this),
			new TeleportSign($this)
		];
		foreach($essentialsEventHandlers as $essentialsEventHandler) {
			$this->getServer()->getPluginManager()->registerEvents($essentialsEventHandler, $this);
		}
	}

	/**
	 * @return DataManager
	 */
	public function getConfigurableData(): DataManager {
		return $this->configurableData;
	}

	/**
	 * @return array
	 */
	public function getInstalledModules(): array {
		return $this->installedModules;
	}

	/**
	 * @param int $moduleId
	 */
	public function addModule(int $moduleId) {
		$this->installedModules[$moduleId] = true;
	}

	/**
	 * @param int $moduleId
	 *
	 * @return bool
	 */
	public function isModuleLoaded(int $moduleId): bool {
		if($this->getInstalledModules()[$moduleId] === true) {
			return true;
		}
		return false;
	}
}
