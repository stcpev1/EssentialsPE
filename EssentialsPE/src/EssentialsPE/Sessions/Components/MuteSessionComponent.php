<?php

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSessionComponent;
use EssentialsPE\Sessions\PlayerSession;

class MuteSessionComponent extends BaseSessionComponent {

	private $isMuted = false;
	private $mutedUntil;

	public function __construct(Loader $loader, PlayerSession $session) {
		parent::__construct($loader, $session);
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
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->isMuted;
	}

	/**
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 */
	public function switchMute(\DateTime $expires = null, bool $notify = true) {
		$this->setMuted(!$this->isMuted(), $expires, $notify);
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
		}
		return true;
	}
}