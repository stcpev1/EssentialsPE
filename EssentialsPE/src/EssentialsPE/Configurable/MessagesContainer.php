<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

class MessagesContainer extends ConfigurableDataHolder {

	private $messages = [];

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	protected function check() {
		if(!file_exists($file = $this->getLoader()->getDataFolder() . "messages.yml")) {
			$this->getLoader()->saveResource("messages.yml");
		}
		$this->messages = yaml_parse_file($file);
	}

	/**
	 * @return array
	 */
	public function getMessages(): array {
		return $this->messages;
	}

	/**
	 * @param string $message
	 * @param array  $replacementStrings
	 *
	 * @return string
	 */
	public function setComponents(string $message, array $replacementStrings): string {
		foreach($replacementStrings as $key => $string) {
			$message = str_replace("%p" . $key, $string, $message);
		}
		return $message;
	}
}