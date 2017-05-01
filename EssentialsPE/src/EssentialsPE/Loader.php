<?php

namespace EssentialsPE;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Commands\CommandOverloads;
use EssentialsPE\Commands\Economy\BalanceCommand;
use EssentialsPE\Commands\Economy\EcoCommand;
use EssentialsPE\Commands\Economy\PayCommand;
use EssentialsPE\Commands\EssentialsPECommand;
use EssentialsPE\Commands\Miscellaneous\BreakCommand;
use EssentialsPE\Configurable\DataManager;
use EssentialsPE\EventHandlers\SpecialSigns\SignBreak;
use EssentialsPE\EventHandlers\SpecialSigns\TeleportSign;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	const MODULE_ESSENTIALS = 0;
	const MODULE_ECONOMY = 1;
	const MODULE_WARPS = 2;

	private $configurableData;
	private $installedModules = [];

	public function onLoad() {
		$this->addModule(self::MODULE_ESSENTIALS);

		CommandOverloads::initialize();
	}

	/**
	 * @param int $moduleId
	 */
	public function addModule(int $moduleId) {
		$this->installedModules[$moduleId] = true;
	}

	public function registerCommands() {
		$essentialsCommands = [
			"essentialspe" => new EssentialsPECommand($this),
			"break" => new BreakCommand($this),
			"pay" => new PayCommand($this),
			"balance" => new BalanceCommand($this),
			"eco" => new EcoCommand($this)
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

	/**
	 * @return DataManager
	 */
	public function getConfigurableData(): DataManager {
		return $this->configurableData;
	}

	/**
	 * @param int $moduleId
	 *
	 * @return bool
	 */
	public function isModuleLoaded(int $moduleId): bool {
		if(isset($this->getInstalledModules()[$moduleId])) {
			return true;
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getInstalledModules(): array {
		return $this->installedModules;
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

	public function onEnable() {
		$this->configurableData = new DataManager($this);

		$this->registerCommands();
		$this->registerEventHandlers();
	}

	public function onDisable() {
		$this->getConfigurableData()->saveAll();
	}

	/**
	 * Returns an instance of the EssentialsPEconomy module if enabled, otherwise false.
	 *
	 * @return bool|\EssentialsPEconomy\Loader
	 */
	public function getEconomyModule() {
		if($this->isModuleLoaded(self::MODULE_ECONOMY)) {
			if($economy = $this->getServer()->getPluginManager()->getPlugin("EssentialsPEconomy") instanceof \EssentialsPEconomy\Loader) {
				return $economy;
			}
		}
		return false;
	}

	/**
	 * Returns an instance of the EssentialsPEWarps module if enabled, otherwise false.
	 *
	 * @return bool|\EssentialsPEWarps\Loader
	 */
	public function getWarpsModule() {
		if($this->isModuleLoaded(self::MODULE_WARPS)) {
			if($warps = $this->getServer()->getPluginManager()->getPlugin("EssentialsPEWarps") instanceof \EssentialsPEWarps\Loader) {
				return $warps;
			}
		}
		return false;
	}
}
