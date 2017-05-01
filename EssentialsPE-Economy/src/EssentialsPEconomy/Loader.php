<?php

namespace EssentialsPEconomy;

use EssentialsPE\Configurable\EconomyConfiguration;
use EssentialsPE\Economy\Providers\MySQLEconomyProvider;
use EssentialsPEconomy\Providers\EconomyProvider;
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

	public function selectProvider(): EconomyProvider {
		switch($this->getConfiguration()->get("Economy-Provider")) {
			default:
			case "MySQL":
				$this->provider = new MySQLEconomyProvider($this);
				break;
		}
		return $this->provider;
	}

	/**
	 * Returns the currency symbol configured in the config.yml.
	 *
	 * @return string
	 */
	public function getCurrencySymbol(): string {
		return $this->getConfiguration()->get("Currency-Symbol");
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

	public function getProvider(): EconomyProvider {
		return $this->provider;
	}
}