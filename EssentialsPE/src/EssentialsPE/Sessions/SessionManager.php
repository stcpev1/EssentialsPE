<?php

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use EssentialsPE\Sessions\Providers\SQLiteSessionProvider;
use pocketmine\Player;

class SessionManager {

	private static $session;
	private static $loader;
	private $provider;

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
	 *
	 * @return PlayerSession
	 */
	public function createSession(Player $player): PlayerSession {
		if(self::hasSession($player)) {
			return self::getSession($player);
		}
		self::$session[$player->getName()] = new PlayerSession(self::getLoader(), $player);
		return self::getSession($player);
		// TODO: Add session 'importing' from database.
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

	/**
	 * @return BaseSessionProvider
	 */
	public function selectProvider(): BaseSessionProvider {
		switch(strtolower(self::getLoader()->getConfigurableData()->getConfiguration()->get("Provider"))) {
			/*case "mysql":
				$this->provider = new MySQLEconomyProvider($this);
				break;
			case "yaml":
				$this->provider = new YamlEconomyProvider($this);
				break;
			case "json":
				$this->provider = new JsonEconomyProvider($this);
				break;*/
			default:
			case "sqlite":
				$this->provider = new SQLiteSessionProvider(self::getLoader());
				break;
		}
		return $this->provider;
	}

	/**
	 * @return BaseSessionProvider
	 */
	public function getProvider(): BaseSessionProvider {
		return $this->provider;
	}
}