<?php
namespace EssentialsPE;

use EssentialsPE\Commands\CommandOverloads;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	public function onEnable() {
		if(!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder());
		}
		CommandOverloads::initialize();
	}

	public function onDisable() {

	}

	public function registerCommands() {
		$essentialsCommands = [

		];
	}

	public function registerEventHandlers() {
		$essentialsEventHandlers = [

		];
	}


}
