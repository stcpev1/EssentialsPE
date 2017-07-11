<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use pocketmine\IPlayer;
use pocketmine\OfflinePlayer;

class MuteSessionComponent extends BaseSavedSessionComponent {

	/** @var bool */
	private $isMuted = false;
	/** @var \DateTime */
	private $mutedUntil;
	/** @var string[] */
	private $ignoredPlayers = [];

	public function __construct(Loader $loader, PlayerSession $session, array $data = []) {
		parent::__construct($loader, $session);
		// TODO
	}

	/**
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function switchMute(\DateTime $expires = null, bool $notify = true): bool {
		return $this->setMuted(!$this->isMuted(), $expires, $notify);
	}

	/**
	 * @param bool           $value
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function setMuted(bool $value = true, \DateTime $expires = null, bool $notify = true): bool {
		if($this->isMuted() !== $value) {
			$this->isMuted = true;
			$this->mutedUntil = $expires;
			if($notify && $this->getPlayer()->hasPermission("essentials.mute.notify")) {
				$this->getPlayer()->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.mute.self-" . ($this->isMuted() ? "muted" : "unmuted"), ($expires === null ?
					$this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.mute.mute-forever")
					: $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("commands.mute.mute-until", $expires->format("l, F j, Y"), $expires->format("h:ia")))));
			}
			return true;
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->isMuted;
	}

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::IS_MUTED, $this->isMuted());
		$this->getSession()->addToSavedData(BaseSessionProvider::MUTED_UNTIL, $this->getMutedUntil());
		$this->getSession()->addToSavedData(BaseSessionProvider::IGNORED_PLAYERS, $this->getIgnoredPlayers());
	}

	/**
	 * @return bool|null|\DateTime
	 */
	public function getMutedUntil() {
		if(!$this->isMuted()) {
			return false;
		}
		return $this->mutedUntil;
	}

	/**
	 * @param IPlayer        $player
	 * @param bool           $value
	 * @param \DateTime|null $expires
	 *
	 * @return bool
	 */
	public function setIgnored(IPlayer $player, bool $value = true, \DateTime $expires = null): bool {

	}

	/**
	 * @return IPlayer[]
	 */
	public function getIgnoredPlayers(): array {
		$players = [];
		foreach($this->ignoredPlayers as $playerName) {
			if(($player = $this->getSession()->getLoader()->getServer()->getPlayer($playerName)) === null) {
				$players[] = new OfflinePlayer($this->getSession()->getLoader()->getServer(), $playerName);
			} else {
				$players[] = $player;
			}
		}
		return $players;
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return bool
	 */
	public function isIgnored(IPlayer $player): bool {
		if(in_array($player->getName(), $this->ignoredPlayers)) {
			return true;
		}
		return false;
	}
}