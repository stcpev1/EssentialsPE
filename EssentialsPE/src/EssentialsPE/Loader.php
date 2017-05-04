<?php

namespace EssentialsPE;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Commands\Chat\BroadcastCommand;
use EssentialsPE\Commands\CommandOverloads;
use EssentialsPE\Commands\Economy\BalanceCommand;
use EssentialsPE\Commands\Economy\BalanceTopCommand;
use EssentialsPE\Commands\Economy\EcoCommand;
use EssentialsPE\Commands\Economy\PayCommand;
use EssentialsPE\Commands\EssentialsPECommand;
use EssentialsPE\Commands\Miscellaneous\BreakCommand;
use EssentialsPE\Commands\Miscellaneous\CompassCommand;
use EssentialsPE\Commands\Miscellaneous\DepthCommand;
use EssentialsPE\Commands\Miscellaneous\ExtinguishCommand;
use EssentialsPE\Commands\Miscellaneous\FeedCommand;
use EssentialsPE\Commands\Miscellaneous\GetPosCommand;
use EssentialsPE\Commands\Miscellaneous\HealCommand;
use EssentialsPE\Commands\Miscellaneous\PingCommand;
use EssentialsPE\Commands\Miscellaneous\SetSpawnCommand;
use EssentialsPE\Commands\Miscellaneous\SpeedCommand;
use EssentialsPE\Commands\Miscellaneous\SudoCommand;
use EssentialsPE\Commands\Miscellaneous\SuicideCommand;
use EssentialsPE\Commands\Miscellaneous\TopCommand;
use EssentialsPE\Commands\Miscellaneous\WorldCommand;
use EssentialsPE\Configurable\DataManager;
use EssentialsPE\EventHandlers\SpecialSigns\SignBreak;
use EssentialsPE\EventHandlers\SpecialSigns\TeleportSign;
use MongoDB\Driver\Exception\DuplicateKeyException;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class Loader extends PluginBase {

	const MODULE_ESSENTIALS = 0;
	const MODULE_ECONOMY = 1;
	const MODULE_WARPS = 2;

	private $configurableData;
	private $installedModules = [];

	public function onLoad() {
		$this->addModule(self::MODULE_ESSENTIALS, "EssentialsPE");

		CommandOverloads::initialize();
	}

	/**
	 * Adds a module to EssentialsPE. The module name should be equal to the name specified in the plugin.yml of your module for correct initialization.
	 *
	 * @param int    $moduleId
	 * @param string $moduleName
	 */
	public function addModule(int $moduleId, $moduleName = "EssentialsModule") {
		if($this->isModuleLoaded($moduleId)) {
			throw new DuplicateKeyException("EssentialsPE modules with the same ID can't be loaded together.");
		}
		$this->installedModules[$moduleId] = $moduleName;
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

	/**
	 * @param int $moduleId
	 */
	public function disableModule(int $moduleId) {
		if($moduleId === 0) {
			throw new \InvalidArgumentException("Disabling the main module of EssentialsPE is NOT allowed.");
		}
		unset($this->installedModules[$moduleId]);
	}

	public function onEnable() {
		$this->configurableData = new DataManager($this);

		$this->registerCommands();
		$this->registerEventHandlers();
		$this->getLogger()->info(TF::AQUA . "EssentialsPE modules loaded:");
		if(!empty($this->getInstalledModules())) {
			foreach($this->getInstalledModules() as $moduleId => $moduleName) {
				$this->getLogger()->info(TF::GOLD . "[" . $moduleId . "] " . TF::GREEN . $moduleName);
			}
		}
	}

	public function registerCommands() {
		$essentialsCommands = [
			new BreakCommand($this),
			new BroadcastCommand($this),
			new CompassCommand($this),
			new DepthCommand($this),
			new EssentialsPECommand($this),
			new ExtinguishCommand($this),
			new FeedCommand($this),
			new GetPosCommand($this),
			new HealCommand($this),
			new PingCommand($this),
			new SetSpawnCommand($this),
			new SpeedCommand($this),
			new SudoCommand($this),
			new SuicideCommand($this),
			new TopCommand($this),
			new WorldCommand($this),

			// Economy Commands
			new PayCommand($this),
			new BalanceCommand($this),
			new EcoCommand($this),
			new BalanceTopCommand($this)
		];
		foreach($essentialsCommands as $essentialsCommand) {
			if($essentialsCommand instanceof BaseCommand) {
				if(in_array($essentialsCommand->getName(), $this->getConfigurableData()->getCommandSwitch()->getAvailableCommands())) {
					if($this->isModuleLoaded($essentialsCommand->getModule())) {
						$this->getServer()->getCommandMap()->register($essentialsCommand->getName(), $essentialsCommand);
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

	public function registerEventHandlers() {
		$essentialsEventHandlers = [
			new SignBreak($this),
			new TeleportSign($this)
		];
		foreach($essentialsEventHandlers as $essentialsEventHandler) {
			$this->getServer()->getPluginManager()->registerEvents($essentialsEventHandler, $this);
		}
	}

	public function onDisable() {
		$this->getConfigurableData()->saveAll();
	}

	/**
	 * @param int $moduleId
	 *
	 * @return \pocketmine\plugin\Plugin
	 */
	public function getModule(int $moduleId) {
		$moduleName = $this->getInstalledModules()[$moduleId];
		if(($module = $this->getServer()->getPluginManager()->getPlugin($moduleName)) !== null) {
			return $module;
		}
		throw new \InvalidArgumentException("A module with the given ID -> Name combination could not be found.");
	}
}
