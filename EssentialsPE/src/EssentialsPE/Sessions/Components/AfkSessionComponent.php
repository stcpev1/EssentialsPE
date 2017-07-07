<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;

class AfkSessionComponent extends BaseSavedSessionComponent {

	private $isAfk = false;

	public function __construct(Loader $loader, PlayerSession $session, array $data = []) {
		parent::__construct($loader, $session);
		if(isset($data[BaseSessionProvider::IS_AFK])) {
			$this->setAfk($data[BaseSessionProvider::IS_AFK], false);
		}
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

	/**
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function switchAfk(bool $broadcast = true): bool {
		return $this->setAfk(!$this->isAfk(), $broadcast);
	}

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::IS_AFK, $this->isAfk());
	}
}