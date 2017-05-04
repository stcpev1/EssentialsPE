<?php

namespace EssentialsPE\Sessions;

use pocketmine\Player;

class PlayerSession {

	private $player;

	// Saved by provider
	private $isAfk = false;
	private $isGod = false;
	private $isMuted = false;
	private $hasUnlimitedEnabled = false;
	private $hasPvpEnabled = true;
	private $mutedUntil = null;
	private $nick = null;

	// Reset on restart

	public function __construct(Player $player) {
		$this->player = $player;
	}
}