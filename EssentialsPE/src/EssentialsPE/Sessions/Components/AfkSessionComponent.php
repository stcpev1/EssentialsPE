<?php

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSessionComponent;
use EssentialsPE\Sessions\PlayerSession;

class AfkSessionComponent extends BaseSessionComponent {

	private $isAfk = false;

	public function __construct(Loader $loader, PlayerSession $session) {
		parent::__construct($loader, $session);
	}

	/**
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function switchAfk(bool $broadcast = true): bool {
		return $this->setAfk(!$this->isAfk(), $broadcast);
	}

	/**
	 * @param bool $value
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function setAfk(bool $value = true, bool $broadcast = true): bool {
		$this->isAfk = $value;
		$this->getPlayer()->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.afk.self-" . ($this->isAfk() ? "enable" : "disable")));
		if($broadcast) {
			$this->broadcastAfkStatus();
		}
		return true;
	}

	/**
	 * @return bool
	 */
	public function isAfk(): bool {
		return $this->isAfk;
	}

	private function broadcastAfkStatus() {
		if(!$this->getLoader()->getConfigurableData()->getConfiguration()->get("Afk.Broadcast")) {
			return;
		}
		$message = $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.afk.other-" . ($this->isAfk() ? "enable" : "disable"), $this->getPlayer()->getDisplayName());
		$this->getLoader()->getLogger()->info($message);
		foreach($this->getLoader()->getServer()->getOnlinePlayers() as $player) {
			if($player !== $this->getPlayer()) {
				$player->sendMessage($message);
			}
		}
	}
}