<?php

namespace EssentialsPEconomy;

use EssentialsPE\Configurable\EconomyConfiguration;
use EssentialsPE\Economy\Providers\MySQLEconomyProvider;
use EssentialsPEconomy\Providers\EconomyProvider;
use EssentialsPEconomy\Providers\YamlEconomyProvider;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	/** @var \EssentialsPE\Loader $essentials */
	private $essentials;
	private $configuration;
	private $provider;

	public function onLoad() {
		$this->essentials = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
		$this->essentials->addModule(\EssentialsPE\Loader::MODULE_ECONOMY);
	}

	public function onEnable() {
		if(!is_dir($this->getDataFolder())) {
			mkdir($this->getDataFolder());
		}
		$this->configuration = new EconomyConfiguration($this);
		$this->selectProvider();
	}

	public function onDisable() {
		$this->getProvider()->closeDatabase();
	}

	/**
	 * @return EconomyProvider
	 */
	public function selectProvider(): EconomyProvider {
		switch(strtolower($this->getConfiguration()->get("Economy-Provider"))) {
			default:
			case "mysql":
				$this->provider = new MySQLEconomyProvider($this);
				break;
			case "yaml":
				$this->provider = new YamlEconomyProvider($this);
				break;
		}
		return $this->provider;
	}

	/**
	 * @return EconomyConfiguration
	 */
	public function getConfiguration(): EconomyConfiguration {
		return $this->configuration;
	}

	/**
	 * @return \EssentialsPE\Loader
	 */
	public function getEssentialsPE(): \EssentialsPE\Loader {
		return $this->essentials;
	}

	/**
	 * Returns the provider, required to access the API of the plugin.
	 *
	 * @return EconomyProvider
	 */
	public function getProvider(): EconomyProvider {
		return $this->provider;
	}
}