<?php

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use EssentialsPE\Sessions\Providers\SQLiteSessionProvider;
use pocketmine\OfflinePlayer;
use pocketmine\Player;
use pocketmine\Server;

class SessionManager {

	private static $session;
	private static $loader;
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
	 * @param $player
	 *
	 * @return PlayerSession
	 */
	public function createSession($player): PlayerSession {
		if($player instanceof Player || is_string($player) || $player instanceof OfflinePlayer) {
			$player = new OfflinePlayer(Server::getInstance(), is_string($player) ? $player : $player->getName());
		}
		if(self::hasSession($player)) {
			return self::getSession($player);
		}
		self::$session[$player->getName()] = new PlayerSession(self::getLoader(), $player);
		return self::getSession($player);
	}

	/**
	 * @param OfflinePlayer $player
	 *
	 * @return bool
	 */
	public static function hasSession(OfflinePlayer $player): bool {
		return isset(self::$session[$player->getName()]);
	}

	/**
	 * @param $player
	 *
	 * @return PlayerSession
	 */
	public static function getSession($player): PlayerSession {
		if($player instanceof Player || is_string($player) || $player instanceof OfflinePlayer) {
			$player = new OfflinePlayer(Server::getInstance(), is_string($player) ? $player : $player->getName());
		}
		return self::$session[$player->getName()];
	}

	/**
	 * @param OfflinePlayer $player
	 *
	 * @return bool
	 */
	public function deleteSession(OfflinePlayer $player): bool {
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