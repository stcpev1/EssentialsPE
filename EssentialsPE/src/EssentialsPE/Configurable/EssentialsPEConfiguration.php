<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;
use pocketmine\utils\TextFormat as TF;

class EssentialsPEConfiguration {

	const CONFIGURATION_VERSION = "1.0.0";

	private $loader;
	private $configurationData = [];

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->checkConfiguration();
	}

	private function checkConfiguration() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "config.yml")) {
			$this->getLoader()->saveDefaultConfig();
		}
		$configurationData = yaml_parse_file($path);

		$this->configurationData = @[
			"Config-Version" => $configurationData["Config-Version"] ?? 0.0,
			"Auto-Update-Config" => $configurationData["Auto-Update-Config"] ?? true,
		];

		if(version_compare($this->configurationData["Config-Version"], self::CONFIGURATION_VERSION) === -1) {
			if(($autoUpdate = $this->configurationData["Auto-Update-Config"]) === true) {
				$this->updateConfig();
			}
			$this->getLoader()->getLogger()->debug(TF::YELLOW . "A new configuration version was found." . $autoUpdate ? "Updating config.yml file..." : "");
		} else {
			$this->getLoader()->getLogger()->debug(TF::GREEN . "No new configuration version found. Your configuration is up to date.");
		}
	}

	public function saveConfiguration() {
		$config = $this->getLoader()->getConfig();
		foreach($this->configurationData as $key => $datum) {
			$config->set($key, $datum);
		}
		$config->save();
	}

	public function updateConfig() {
		$this->configurationData["Configuration-Version"] = self::CONFIGURATION_VERSION;
		$this->saveConfiguration();
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}