<?php

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;

class MuteSessionComponent extends BaseSavedSessionComponent {

	private $isMuted = false;
	private $mutedUntil;

	public function __construct(Loader $loader, PlayerSession $session, bool $isMuted = false, \DateTime $mutedUntil = null) {
		parent::__construct($loader, $session);
		$this->setMuted($isMuted, $mutedUntil, false);
	}

	/**
	 * @param bool           $value
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function setMuted(bool $value = true, \DateTime $expires = null, bool $notify = true) {
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

	/**
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function switchMute(\DateTime $expires = null, bool $notify = true) {
		return $this->setMuted(!$this->isMuted(), $expires, $notify);
	}

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::IS_MUTED, $this->isMuted());
		$this->getSession()->addToSavedData(BaseSessionProvider::MUTED_UNTIL, $this->getMutedUntil());
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
}