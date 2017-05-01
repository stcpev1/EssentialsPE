<?php

namespace EssentialsPE\Configurable;

use EssentialsPEconomy\Loader;
use pocketmine\utils\TextFormat as TF;

class EconomyConfiguration {

	const ECONOMY_CONFIGURATION_VERSION = "1.0.0";

	private $ecoConfigurationData = [];
	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->check();
	}

	private function check() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "config.yml")) {
			$this->getLoader()->saveDefaultConfig();
		}
		$configurationData = yaml_parse_file($path);

		$this->ecoConfigurationData = @[
			"Economy-Config-Version" => $configurationData["Economy-Config-Version"] ?? 0.0,
			"Auto-Update-Config" => $configurationData["Auto-Update-Config"] ?? true,
			"Economy-Provider" => $configurationData["Economy-Provider"] ?? "MySQL",
			"Currency-Symbol" => $configurationData["Currency-Symbol"] ?? '$',
			"Minimum-Balance" => $configurationData["Minimum-Balance"] ?? 0,
			"Maximum-Balance" => $configurationData["Maximum-Balance"] ?? 10000000,
			"Default-Balance" => $configurationData["Default-Balance"] ?? 0
		];

		if(version_compare($this->ecoConfigurationData["Economy-Config-Version"], self::ECONOMY_CONFIGURATION_VERSION) === -1) {
			if(($autoUpdate = $this->ecoConfigurationData["Auto-Update-Config"]) === true) {
				$this->updateEcoConfig();
			}
			$this->getLoader()->getLogger()->debug(TF::YELLOW . "A new economy configuration version was found." . $autoUpdate ? "Updating economy.yml file..." : "");
		} else {
			$this->getLoader()->getLogger()->debug(TF::GREEN . "No new economy configuration version found. Your economy configuration is up to date.");
		}
	}

	public function saveEcoConfiguration() {
		$config = $this->getLoader()->getConfig();
		foreach($this->ecoConfigurationData as $key => $datum) {
			$config->setNested($key, $datum);
		}
		$config->save();
	}

	public function updateEcoConfig() {
		$this->ecoConfigurationData["Economy-Config-Version"] = self::ECONOMY_CONFIGURATION_VERSION;
		$this->saveEcoConfiguration();
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get(string $key) {
		if(!isset($this->ecoConfigurationData[$key])) {
			return null;
		}
		return $this->ecoConfigurationData[$key];
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}