<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

class CommandSwitch extends ConfigurableDataHolder {

	private $availableCommands = [];
	private $disabledCommands = [];

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	protected function check() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "commands.yml")) {
			$this->getLoader()->saveResource("commands.yml");
		}
		$commands = yaml_parse_file($path);

		foreach($commands as $command => $enabled) {
			if($enabled === true) {
				$this->availableCommands[] = $command;
			} else {
				$this->disabledCommands[] = $command;
			}
		}
	}

	/**
	 * @return array
	 */
	public function getAvailableCommands(): array {
		return $this->availableCommands;
	}

	/**
	 * @return array
	 */
	public function getDisabledCommands(): array {
		return $this->disabledCommands;
	}
}