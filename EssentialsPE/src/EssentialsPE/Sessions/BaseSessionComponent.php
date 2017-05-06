<?php

namespace EssentialsPE\Sessions;


use EssentialsPE\Loader;
use pocketmine\Player;

class BaseSessionComponent {

	private $loader;
	private $session;

	public function __construct(Loader $loader, PlayerSession $session) {
		$this->loader = $loader;
		$this->session = $session;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return PlayerSession
	 */
	public function getSession(): PlayerSession {
		return $this->session;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->session->getPlayer();
	}
}