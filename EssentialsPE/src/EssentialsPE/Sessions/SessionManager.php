<?php

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use pocketmine\Player;

class SessionManager {

	private static $session;
	private static $loader;

	public function __construct(Loader $loader) {
		self::$loader = $loader;
	}

	public static function getSession(Player $player): PlayerSession {
		if(!self::hasSession($player)) {
			return self::createSession($player);
		}
		return self::$session[$player->getName()];
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public static function hasSession(Player $player): bool {
		return isset(self::$session[$player->getName()]);
	}

	/**
	 * @param Player $player
	 * @param array  $values
	 *
	 * @return PlayerSession
	 */
	public static function createSession(Player $player, array $values = []): PlayerSession {
		if(self::hasSession($player)) {
			return self::getSession($player);
		}
		self::$session[$player->getName()] = new PlayerSession(self::getLoader(), $player, $values);
		return self::getSession($player);
	}

	/**
	 * @return Loader
	 */
	public static function getLoader(): Loader {
		return self::$loader;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function deleteSession(Player $player): bool {
		if(!$this->hasSession($player)) {
			return false;
		}
		unset(self::$session[$player->getName()]);
		return true;
	}
}