<?php

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Components\AfkSessionComponent;
use EssentialsPE\Sessions\Components\GodSessionComponent;
use EssentialsPE\Sessions\Components\MuteSessionComponent;
use pocketmine\Player;

class PlayerSession {

	private $player;
	private $loader;

	private $afkComponent;
	private $godComponent;

	private $hasUnlimitedEnabled = false;
	private $hasPvpEnabled = true;
	private $nick = null;

	private $lastMovement = null;
	private $lastPosition = null;
	private $quickReply = null;
	private $requestTo = false;
	private $requestToAction = false;
	private $noPacket = false;

	public function __construct(Loader $loader, Player $player, array $values = []) {
		$this->player = $player;
		$this->loader = $loader;

		$this->afkComponent = new AfkSessionComponent($loader, $this);
		$this->godComponent = new GodSessionComponent($loader, $this);
		$this->muteComponent = new MuteSessionComponent($loader, $this);
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	/**
	 * @param bool $value
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function setAfk(bool $value = true, bool $broadcast = true): bool {
		return $this->afkComponent->setAfk($value, $broadcast);
	}

	/**
	 * @return bool
	 */
	public function isAfk(): bool {
		return $this->afkComponent->isAfk();
	}

	/**
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function switchAfk(bool $broadcast = true): bool {
		return $this->afkComponent->switchAfk($broadcast);
	}

	/**
	 * @param bool $value
	 *
	 * @return bool
	 */
	public function setGod(bool $value = true): bool {
		return $this->godComponent->setGod($value);
	}

	/**
	 * @return bool
	 */
	public function isGod(): bool {
		return $this->godComponent->isGod();
	}

	/**
	 * @return bool
	 */
	public function switchGod(): bool {
		return $this->godComponent->switchGod();
	}
}