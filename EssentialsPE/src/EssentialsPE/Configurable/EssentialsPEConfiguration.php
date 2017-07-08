<?php

declare(strict_types = 1);

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;
use pocketmine\utils\TextFormat as TF;

class EssentialsPEConfiguration extends ConfigurableDataHolder {

	const CONFIGURATION_VERSION = "1.0.0";

	/** @var array */
	private $configurationData = [];

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get(string $key) {
		if(!isset($this->configurationData[$key])) {
			return null;
		}
		return $this->configurationData[$key];
	}

	protected function check() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "config.yml")) {
			$this->getLoader()->saveDefaultConfig();
		}
		$config = yaml_parse_file($path);

		$this->configurationData = @[
			"Config-Version" => $config["Config-Version"] ?? 0.0,
			"Auto-Update-Config" => $config["Auto-Update-Config"] ?? true,
			"Provider" => $config["Provider"] ?? "SQLite",
			"MySQL.Host" => $config["MySQL"]["Host"] ?? "127.0.0.1",
			"MySQL.User" => $config["MySQL"]["User"] ?? "Admin",
			"MySQL.Password" => $config["MySQL"]["Password"] ?? "Admin",
			"MySQL.Database" => $config["MySQL"]["Database"] ?? "EssentialsPE",
			"MySQL.Port" => $config["MySQL"]["Port"] ?? 3306,
			"Afk.Broadcast" => $config["Afk"]["Broadcast"] ?? true,
			"Afk.Auto-Kick" => $config["Afk"]["Auto-Kick"] ?? false,
			"Afk.Auto-Kick-Idling" => $config["Afk"]["Auto-Kick-Idling"] ?? false,
			"Afk.Kick-Time" => $config["Afk"]["Kick-Time"] ?? 300,
			"Teleporting.Tpa-Valid-Time" => $config["Teleporting"]["Tpa-Valid-Time"] ?? 30,
			"Teleporting.Delay" => $config["Teleporting"]["Delay"] ?? true,
			"Teleporting.Delay-Time" => $config["Teleporting"]["Delay-Time"] ?? 3,
			"Chat.Nick-Symbol" => $config["Chat"]["Nick-Symbol"] ?? "&9&l~&r"
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

	public function updateConfig() {
		$this->configurationData["Config-Version"] = self::CONFIGURATION_VERSION;
		$this->saveConfiguration();
	}

	public function saveConfiguration() {
		$config = $this->getLoader()->getConfig();
		foreach($this->configurationData as $key => $datum) {
			$config->setNested($key, $datum);
		}
		$config->save();
	}
}