<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;
use pocketmine\utils\TextFormat as TF;

class EconomyConfiguration extends ConfigurableDataHolder {

	const ECONOMY_CONFIGURATION_VERSION = "1.0.0";

	private $ecoConfigurationData = [];

	public function __construct(Loader $loader) {
		parent::__construct($loader);

		$this->check();
	}

	protected function check() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "economy\\config.yml")) {
			$this->getLoader()->saveResource("economy.yml");
		}
		$configurationData = yaml_parse_file($path);

		$this->ecoConfigurationData = @[
			"Economy-Config-Version" => $configurationData["Config-Version"] ?? 0.0,
			"Auto-Update-Config" => $configurationData["Auto-Update-Config"] ?? true,
			"Economy-Provider" => $configurationData["Economy-Provider"] ?? "SQLite3",
			"MySQL.Host" => $configurationData["MySQL"]["Host"] ?? "127.0.0.1",
			"MySQL.User" => $configurationData["MySQL"]["User"] ?? "Admin",
			"MySQL.Password" => $configurationData["MySQL"]["Password"] ?? "Admin",
			"MySQL.Database" => $configurationData["MySQL"]["Database"] ?? "EssentialsPE",
			"MySQL.Port" => $configurationData["MySQL"]["Port"] ?? 3306,
			"Currency-Symbol" => $configurationData["Currency-Symbol"] ?? '$',
			"Minimum-Balance" => $configurationData["Minimum-Balance"] ?? 0,
			"Maximum-Balance" => $configurationData["Maximum-Balance"] ?? 10000000
		];

		if(version_compare($this->ecoConfigurationData["Economy-Config-Version"], self::ECONOMY_CONFIGURATION_VERSION) === -1) {
			if(($autoUpdate = $this->ecoConfigurationData["Auto-Update-Config"]) === true) {
				$this->updateConfig();
			}
			$this->getLoader()->getLogger()->debug(TF::YELLOW . "A new economy configuration version was found." . $autoUpdate ? "Updating economy.yml file..." : "");
		} else {
			$this->getLoader()->getLogger()->debug(TF::GREEN . "No new economy configuration version found. Your economy configuration is up to date.");
		}
	}

	public function saveConfiguration() {
		$config = $this->getLoader()->getConfig();
		foreach($this->ecoConfigurationData as $key => $datum) {
			$config->setNested($key, $datum);
		}
		$config->save();
	}

	public function updateConfig() {
		$this->ecoConfigurationData["Configuration-Version"] = self::ECONOMY_CONFIGURATION_VERSION;
		$this->saveConfiguration();
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
}