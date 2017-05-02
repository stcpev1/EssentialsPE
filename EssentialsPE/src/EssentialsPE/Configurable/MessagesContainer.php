<?php

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;
use pocketmine\Player;
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
		return $this->colorMessage($result);
	}

	/**
	 * Return a colored message replacing every color code (&a = §a)
	 *
	 * @param string      $message
	 * @param Player|null $player
	 * @param bool        $force
	 *
	 * @return bool|string
	 */
	public function colorMessage(string $message, Player $player = null, bool $force = false) {
		$message = preg_replace_callback(
			"/(\\\&|\&)[0-9a-fk-or]/",
			function(array $matches) {
				return str_replace("\\§", "&", str_replace("&", "§", $matches[0]));
			},
			$message
		);
		if(strpos($message, "§") !== false && ($player instanceof Player) && !$player->hasPermission("essentials.chat.color") && $force !== true) {
			$player->sendMessage($this->getMessage("general.error.color-codes-permission"));
			return false;
		}
		return $message;
	}

	protected function check() {
		if(!file_exists($file = $this->getLoader()->getDataFolder() . "messages.yml")) {
			$this->getLoader()->saveResource("messages.yml");
		}
		$this->messages = new Config($this->getLoader()->getDataFolder() . "messages.yml");
	}
}