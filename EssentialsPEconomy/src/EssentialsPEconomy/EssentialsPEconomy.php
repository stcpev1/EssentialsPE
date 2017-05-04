<?php

namespace EssentialsPEconomy;

use EssentialsPE\Loader;
use EssentialsPEconomy\EventHandlers\JoinHandler;
use EssentialsPEconomy\Providers\EconomyProvider;
use EssentialsPEconomy\Providers\JsonEconomyProvider;
use EssentialsPEconomy\Providers\MySQLEconomyProvider;
use EssentialsPEconomy\Providers\SQLiteEconomyProvider;
use EssentialsPEconomy\Providers\YamlEconomyProvider;
use pocketmine\plugin\PluginBase;

class EssentialsPEconomy extends PluginBase {

	/** @var Loader $essentials */
	private $essentials;
	private $configuration;
	private $provider;

	public function onLoad() {
		$this->essentials = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
		$this->essentials->addModule(Loader::MODULE_ECONOMY, "EssentialsPEconomy");
	}

	public function onEnable() {
		$this->configuration = new EconomyConfiguration($this);
		$this->selectProvider();

		$this->getServer()->getPluginManager()->registerEvents(new JoinHandler($this), $this);
	}

	/**
	 * @return EconomyProvider
	 */
	public function selectProvider(): EconomyProvider {
		switch(strtolower($this->getEssentialsPE()->getConfigurableData()->getConfiguration()->get("Provider"))) {
			default:
			case "mysql":
				$this->provider = new MySQLEconomyProvider($this);
				break;
			case "yaml":
				$this->provider = new YamlEconomyProvider($this);
				break;
			case "json":
				$this->provider = new JsonEconomyProvider($this);
				break;
			case "sqlite":
				$this->provider = new SQLiteEconomyProvider($this);
				break;
		}
		return $this->provider;
	}

	/**
	 * @return Loader
	 */
	public function getEssentialsPE(): Loader {
		return $this->essentials;
	}

	/**
	 * @return EconomyConfiguration
	 */
	public function getConfiguration(): EconomyConfiguration {
		return $this->configuration;
	}

	public function onDisable() {
		$this->getProvider()->closeDatabase();
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