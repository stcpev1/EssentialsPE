<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

class ConfigurableDataHolder {

	private $loader;
	private $config;
	private $commandSwitch;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->config = new EssentialsPEConfiguration($loader);
		$this->commandSwitch = new CommandSwitch($loader);
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return EssentialsPEConfiguration
	 */
	public function getConfiguration(): EssentialsPEConfiguration {
		return $this->config;
	}

	/**
	 * @return CommandSwitch
	 */
	public function getCommandSwitch(): CommandSwitch {
		return $this->commandSwitch;
	}

	public function saveAll() {
		$this->getConfiguration()->saveConfiguration();
	}
}