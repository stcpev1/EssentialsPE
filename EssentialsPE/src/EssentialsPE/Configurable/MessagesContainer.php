<?php

declare(strict_types = 1);

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;
use EssentialsPE\Utils\ChatUtils;
use pocketmine\utils\Config;

class MessagesContainer extends ConfigurableDataHolder {

	/** @var Config $messages */
	private $messages;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param string   $message
	 * @param string[] ...$replacements
	 *
	 * @return string
	 */
	public function getMessage(string $message, ...$replacements) {
		$result = $this->messages->getNested($message, $message);
		if(is_array($result)) {
			return $result;
		}
		if(count($replacements) > 0) {
			for($i = 0; $i < count($replacements); $i++) {
				$a = $replacements[$i];
				if(is_array($a)) {
					$a = $this->getMessage(array_shift($a), ...$a);
				}
				$result = str_replace("{" . $i . "}", $a, $result);
			}
		}
		if($message === "general.error.color-codes-permission") {
			return $result;
		}
		return ChatUtils::colorMessage($result, $this->getMessage("general.error.color-codes-permission"));
	}

	protected function check() {
		if(!file_exists($file = $this->getLoader()->getDataFolder() . "messages.yml")) {
			$this->getLoader()->saveResource("messages.yml");
		}
		$this->messages = new Config($this->getLoader()->getDataFolder() . "messages.yml");
	}
}