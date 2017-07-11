<?php

declare(strict_types = 1);

namespace EssentialsPE;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Commands\Chat\BroadcastCommand;
use EssentialsPE\Commands\Chat\NickCommand;
use EssentialsPE\Commands\Chat\PrefixCommand;
use EssentialsPE\Commands\Chat\SuffixCommand;
use EssentialsPE\Commands\CommandOverloads;
use EssentialsPE\Commands\Economy\BalanceCommand;
use EssentialsPE\Commands\Economy\BalanceTopCommand;
use EssentialsPE\Commands\Economy\EcoCommand;
use EssentialsPE\Commands\Economy\PayCommand;
use EssentialsPE\Commands\EssentialsPECommand;
use EssentialsPE\Commands\Inventory\ClearInventoryCommand;
use EssentialsPE\Commands\Inventory\SeeInventoryCommand;
use EssentialsPE\Commands\Miscellaneous\AfkCommand;
use EssentialsPE\Commands\Miscellaneous\BreakCommand;
use EssentialsPE\Commands\Miscellaneous\CompassCommand;
use EssentialsPE\Commands\Miscellaneous\DepthCommand;
use EssentialsPE\Commands\Miscellaneous\ExtinguishCommand;
use EssentialsPE\Commands\Miscellaneous\FeedCommand;
use EssentialsPE\Commands\Miscellaneous\GetPosCommand;
use EssentialsPE\Commands\Miscellaneous\GodCommand;
use EssentialsPE\Commands\Miscellaneous\HealCommand;
use EssentialsPE\Commands\Miscellaneous\PingCommand;
use EssentialsPE\Commands\Miscellaneous\SetSpawnCommand;
use EssentialsPE\Commands\Miscellaneous\SpeedCommand;
use EssentialsPE\Commands\Miscellaneous\SudoCommand;
use EssentialsPE\Commands\Miscellaneous\SuicideCommand;
use EssentialsPE\Commands\Miscellaneous\TopCommand;
use EssentialsPE\Commands\Miscellaneous\WorldCommand;
use EssentialsPE\Commands\Teleporting\TpAcceptCommand;
use EssentialsPE\Commands\Teleporting\TpaCommand;
use EssentialsPE\Commands\Teleporting\TpaHereCommand;
use EssentialsPE\Commands\Teleporting\TpAllCommand;
use EssentialsPE\Commands\Teleporting\TpDenyCommand;
use EssentialsPE\Commands\Teleporting\TpHereCommand;
use EssentialsPE\Commands\Warps\DelWarpCommand;
use EssentialsPE\Commands\Warps\SetWarpCommand;
use EssentialsPE\Commands\Warps\WarpCommand;
use EssentialsPE\Configurable\DataManager;
use EssentialsPE\EventHandlers\BaseEventHandler;
use EssentialsPE\EventHandlers\PlayerEventHandler;
use EssentialsPE\EventHandlers\SpecialSigns\Others\GamemodeSign;
use EssentialsPE\EventHandlers\SpecialSigns\SignHandler;
use EssentialsPE\EventHandlers\SpecialSigns\SignManager;
use EssentialsPE\EventHandlers\SpecialSigns\Teleportation\TeleportSign;
use EssentialsPE\Sessions\SessionManager;
use MongoDB\Driver\Exception\DuplicateKeyException;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;
use spoondetector\SpoonDetector;

class Loader extends PluginBase {

	const MODULE_ESSENTIALS = 0;
	const MODULE_ECONOMY = 1;
	const MODULE_WARPS = 2;
	const MODULE_HOMES = 3;
	const MODULE_MAIL = 4;

	/** @var DataManager */
	private $configurableData;
	/** @var string[] */
	private $installedModules = [];
	/** @var SessionManager */
	private $sessionManager;
	/** @var SignManager */
	private $signManager;

	public function onLoad() {
		$this->addModule(self::MODULE_ESSENTIALS, "EssentialsPE");

		CommandOverloads::initialize();
	}

	/**
	 * Adds a module to EssentialsPE.
	 * The module name should be equal to the name in the plugin.yml to access it using $this->getModule();
	 *
	 * @param int    $moduleId
	 * @param string $moduleName
	 */
	public function addModule(int $moduleId, $moduleName = "EssentialsPEModule") {
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

		if(!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder());
		}
		SpoonDetector::printSpoon($this);
		$this->getLogger()->info(TF::AQUA . "EssentialsPE modules loaded:");
		if(!empty($this->getInstalledModules())) {
			foreach($this->getInstalledModules() as $moduleId => $moduleName) {
				$this->getLogger()->info(TF::GOLD . "[" . $moduleId . "] " . TF::GREEN . $moduleName);
			}
		}
		$this->registerCommands();
		$this->registerEventHandlers();
		$this->sessionManager = new SessionManager($this);
	}

	public function registerCommands() {
		$essentialsCommands = [
			// Miscellaneous Commands
			new CompassCommand($this),
			new DepthCommand($this),
			new EssentialsPECommand($this),
			new ExtinguishCommand($this),
			new FeedCommand($this),
			new GetPosCommand($this),
			new BreakCommand($this),
			new HealCommand($this),
			new PingCommand($this),
			new SetSpawnCommand($this),
			new SpeedCommand($this),
			new SudoCommand($this),
			new SuicideCommand($this),
			new TopCommand($this),
			new WorldCommand($this),
			new GodCommand($this),
			new AfkCommand($this),

			// Economy Commands
			new PayCommand($this),
			new BalanceCommand($this),
			new EcoCommand($this),
			new BalanceTopCommand($this),

			// Teleporting commands
			new TpAcceptCommand($this),
			new TpaCommand($this),
			new TpaHereCommand($this),
			new TpAllCommand($this),
			new TpDenyCommand($this),
			new TpHereCommand($this),

			// Chat Commands
			new NickCommand($this),
			new BroadcastCommand($this),
			new PrefixCommand($this),
			new SuffixCommand($this),

			// Warp Commands
			new WarpCommand($this),
			new SetWarpCommand($this),
			new DelWarpCommand($this),

			// Inventory Commands
			new SeeInventoryCommand($this),
			new ClearInventoryCommand($this)
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
		$this->signManager = new SignManager($this);
		$essentialsSpecialSigns = [
			new GamemodeSign($this),
			new TeleportSign($this)
		];
		foreach($essentialsSpecialSigns as $essentialsSign) {
			if($essentialsSign instanceof BaseEventHandler) {
				if(in_array($essentialsSign->getName() . "sign", $this->getConfigurableData()->getCommandSwitch()->getAvailableCommands())) {
					if($this->isModuleLoaded($essentialsSign->getModule())) {
						$this->getSignManager()->registerSign($essentialsSign);
					}
				}
			}
		}
		$essentialsEventHandlers = [
			new PlayerEventHandler($this),
			new SignHandler($this)
		];
		foreach($essentialsEventHandlers as $essentialsHandler) {
			$this->getServer()->getPluginManager()->registerEvents($essentialsHandler, $this);
		}
	}

	/**
	 * @return SignManager
	 */
	public function getSignManager(): SignManager {
		return $this->signManager;
	}

	public function onDisable() {
		$this->getConfigurableData()->saveAll();
	}

	/**
	 * @param int $moduleId
	 *
	 * @return Plugin
	 */
	public function getModule(int $moduleId) {
		$moduleName = $this->getInstalledModules()[$moduleId];
		if(($module = $this->getServer()->getPluginManager()->getPlugin($moduleName)) !== null) {
			return $module;
		}
		throw new \InvalidArgumentException("A module with the ID " . $moduleId . " and name " . $moduleName . " could not be found.");
	}

	/**
	 * @return SessionManager
	 */
	public function getSessionManager(): SessionManager {
		return $this->sessionManager;
	}

	/**
	 * @return string
	 */
	public function getProvider(): string {
		return (string) $this->getConfigurableData()->getConfiguration()->get("Provider");
	}
}
