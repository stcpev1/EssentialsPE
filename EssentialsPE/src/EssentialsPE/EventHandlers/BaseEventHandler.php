<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use pocketmine\event\Listener;
use pocketmine\Player;

abstract class BaseEventHandler implements Listener {

	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @param Player $sender
	 * @param string $message
	 * @param array  ...$replacements
	 */
	public function sendMessageContainer(Player $player, string $message, ...$replacements) {
		$player->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage($message, ...$replacements));
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}