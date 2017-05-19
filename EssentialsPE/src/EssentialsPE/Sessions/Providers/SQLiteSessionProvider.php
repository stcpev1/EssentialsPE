<?php

namespace EssentialsPE\Sessions\Providers;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Providers\BaseSessionProvider as Provider;
use pocketmine\Player;

class SQLiteSessionProvider extends BaseSessionProvider {

	/** @var \SQLite3 $database */
	private $database;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	public function prepare() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "sessions.sqlite3")) {
			file_put_contents($path, "");
		}
		$this->database = new \SQLite3($path);
		$query = "CREATE TABLE IF NOT EXISTS Sessions(
			Player VARCHAR(20) PRIMARY KEY,
			IsAfk BOOLEAN,
			IsGod BOOLEAN,
			IsMuted BOOLEAN,
			MutedUntil NOT NULL,
			Nick VARCHAR(20),
			HasPvpEnabled BOOLEAN,
			HasUnlimitedEnabled BOOLEAN,
			IsVanished BOOLEAN
			);";
		$this->database->exec($query);
		$query = "CREATE TABLE IF NOT EXISTS Powertools(
			Player VARCHAR(20),
			ItemId INTEGER,
			PowertoolCommand TEXT,
			PowertoolChatMacro TEXT,
			PRIMARY KEY(Player, ItemId)
			);";
		$this->database->exec($query);
	}

	/**
	 * @return bool
	 */
	public function closeDatabase(): bool {
		if($this->database instanceof \SQLite3) {
			$this->database->close();
			return true;
		}
		return false;
	}

	public function save() {

	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function getPlayerData(Player $player): array {
		$lowerCaseName = strtolower($player->getName());
		$data1 = [];
		$data2 = [];

		if(!$this->playerDataExists($player)) {
			return [];
		}
		$result = $this->database->query("SELECT * FROM Sessions WHERE Player = '" . $this->escape($lowerCaseName) . "';");
		$result2 = $this->database->query("SELECT * FROM Powertools WHERE Player = '" . $this->escape($lowerCaseName) . "';");
		if(is_array($return = $result->fetchArray(SQLITE3_ASSOC))) {
			$data1 = $return;
		}
		if(is_array($return2 = $result2->fetchArray(SQLITE3_ASSOC))) {
			$data2 = $return2;
		}
		$data = array_merge($data1, $data2);
		return $data;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerDataExists(Player $player): bool {
		$lowerCaseName = strtolower($player->getName());

		$result = $this->database->query("SELECT * FROM Sessions WHERE Player = '" . $this->escape($lowerCaseName) . "';");
		return empty((array)$result);
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	private function escape(string $string): string {
		return \SQLite3::escapeString($string);
	}

	/**
	 * @param Player $player
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function storePlayerData(Player $player, array $data): bool {
		$lowerCaseName = strtolower($player->getName());
		$slicedData = array_slice($data, 8);

		$isAfk = $slicedData[0][Provider::IS_AFK];
		$isGod = $slicedData[0][Provider::IS_GOD];
		$isMuted = $slicedData[0][Provider::IS_MUTED];
		$mutedUntil = $slicedData[0][Provider::MUTED_UNTIL];
		$nick = $slicedData[0][Provider::NICK];
		$hasPvpEnabled = $slicedData[0][Provider::HAS_PVP_ENABLED];
		$hasUnlimitedEnabled = $slicedData[0][Provider::HAS_UNLIMITED_ENABLED];
		$isVanished = $slicedData[0][Provider::IS_VANISHED];

		$powerToolId = $slicedData[1][Provider::POWERTOOL_ID];
		$powerToolCommand = $slicedData[1][Provider::POWERTOOL_COMMAND];
		$powerToolChatMacro = $slicedData[1][Provider::POWERTOOL_CHAT_MACRO];

		if(!$result = $this->database->exec("INSERT INTO Sessions(Player, IsAFK, IsGod, IsMuted, MutedUntil, Nick, HasPvpEnabled, HasUnlimitedEnabled, IsVanished) VALUES ('" . $this->escape($lowerCaseName) . "', $isAfk, $isGod, $isMuted, $mutedUntil, $nick, $hasPvpEnabled, $hasUnlimitedEnabled, $isVanished);")) {
			return false;
		}
		if(!$result = $this->database->exec("INSERT INTO Powertools(Player, PowertoolId, PowertoolCommand, PowertoolChatMacro) VALUES ($powerToolId, '" . $this->escape($powerToolCommand) . "', '" . $this->escape($powerToolChatMacro) . "');")) {
			return false;
		}
		return true;
	}
}