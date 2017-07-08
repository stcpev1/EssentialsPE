<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use EssentialsPE\Sessions\Providers\SQLiteSessionProvider;
use pocketmine\IPlayer;

class SessionManager {

	/** @var PlayerSession[] */
	private static $session = [];
	/** @var Loader */
	private static $loader;
	/** @var BaseSessionProvider */
	private $provider;

	public function __construct(Loader $loader) {
		self::$loader = $loader;

		$this->selectProvider();
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
	 * @return Loader
	 */
	public static function getLoader(): Loader {
		return self::$loader;
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return PlayerSession
	 */
	public function createSession(IPlayer $player): PlayerSession {
		if(self::hasSession($player)) {
			return self::getSession($player);
		}
		self::$session[$player->getName()] = new PlayerSession(self::getLoader(), $player);
		return self::getSession($player);
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return bool
	 */
	public static function hasSession(IPlayer $player): bool {
		return isset(self::$session[$player->getName()]);
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return PlayerSession
	 */
	public static function getSession(IPlayer $player): PlayerSession {
		return self::$session[$player->getName()];
	}

	/**
	 * @param IPlayer $player
	 *
	 * @return bool
	 */
	public function deleteSession(IPlayer $player): bool {
		if(!$this->hasSession($player)) {
			return false;
		}
		unset(self::$session[$player->getName()]);
		return true;
	}

	/**
	 * @return BaseSessionProvider
	 */
	public function getProvider(): BaseSessionProvider {
		return $this->provider;
	}
}