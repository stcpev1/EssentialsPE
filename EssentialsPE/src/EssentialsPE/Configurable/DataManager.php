<?php

declare(strict_types = 1);

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

class DataManager {

	/** @var Loader */
	private $loader;
	/** @var EssentialsPEConfiguration */
	private $config;
	/** @var MessagesContainer */
	private $messages;
	/** @var CommandSwitch */
	private $commandSwitch;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->config = new EssentialsPEConfiguration($loader);
		$this->commandSwitch = new CommandSwitch($loader);
		$this->messages = new MessagesContainer($loader);
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
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

	public function saveAll() {
		$this->getConfiguration()->saveConfiguration();
	}

	/**
	 * @return EssentialsPEConfiguration
	 */
	public function getConfiguration(): EssentialsPEConfiguration {
		return $this->config;
	}

}