<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

class DataManager {

	private $loader;
	private $config;
	private $messages;
	private $commandSwitch;
	private $economy;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->config = new EssentialsPEConfiguration($loader);
		$this->commandSwitch = new CommandSwitch($loader);
		$this->messages = new MessagesContainer($loader);
		$this->economy = new EconomyConfiguration($loader);
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

	/**
	 * @return MessagesContainer
	 */
	public function getMessagesContainer(): MessagesContainer {
		return $this->messages;
	}

	/**
	 * @return EconomyConfiguration
	 */
	public function getEconomyConfiguration(): EconomyConfiguration {
		return $this->economy;
	}

	public function saveAll() {
		$this->getConfiguration()->saveConfiguration();
	}
}