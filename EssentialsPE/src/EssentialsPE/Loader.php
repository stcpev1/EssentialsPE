<?php
namespace EssentialsPE;

use EssentialsPE\Commands\CommandOverloads;
use EssentialsPE\Commands\EssentialsPECommand;
use EssentialsPE\Configurable\ConfigurableDataHolder;
use EssentialsPE\EventHandlers\SpecialSigns\SignBreak;
use EssentialsPE\EventHandlers\SpecialSigns\TeleportSign;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	private $configurableData;

	public function onLoad() {
		CommandOverloads::initialize();
		$this->registerCommands();
		$this->registerEventHandlers();
	}

	public function onEnable() {
		if(!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder());
		}
		$this->configurableData = new ConfigurableDataHolder($this);
	}

	public function onDisable() {
		$this->getConfigurableData()->saveAll();
	}

	public function registerCommands() {
		$essentialsCommands = [
			"essentialspe" => new EssentialsPECommand($this)
		];
		foreach($essentialsCommands as $fallBack => $essentialsCommand) {
			if(in_array($fallBack, $this->getConfigurableData()->getCommandSwitch()->getAvailableCommands())) {
				$this->getServer()->getCommandMap()->register($fallBack, $essentialsCommand);
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
	 * @return ConfigurableDataHolder
	 */
	public function getConfigurableData(): ConfigurableDataHolder {
		return $this->configurableData;
	}
}
