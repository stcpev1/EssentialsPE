<?php

namespace EssentialsPE\Sessions\Providers;

use EssentialsPE\Loader;
use pocketmine\Player;

class SQLiteSessionProvider extends BaseSessionProvider {

	/** @var \SQLite3 $database */
	private $database;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	public function prepare() {
		if(!file_exists($path = $this->getLoader()->getDataFolder() . "economy.sqlite3")) {
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
		if($this->database instanceof \mysqli) {
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

		if(!$this->playerDataExists($player)) {
			return [];
		}
		$result = $this->database->query("SELECT * FROM Sessions WHERE Player = '" . $this->escape($lowerCaseName) . "';");
		$result2 = $this->database->query("SELECT * FROM Powertools WHERE Player = '" . $this->escape($lowerCaseName) . "';");

		return array_merge($result->fetchArray(SQLITE3_ASSOC), $result2->fetchArray(SQLITE3_ASSOC));
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

		$isAfk = $slicedData[0]["IsAfk"];
		$isGod = $slicedData[0]["IsGod"];
		$isMuted = $slicedData[0]["IsMuted"];
		$mutedUntil = $slicedData[0]["MutedUntil"];
		$nick = $slicedData[0]["Nick"];
		$hasPvpEnabled = $slicedData[0]["HasPvpEnabled"];
		$hasUnlimitedEnabled = $slicedData[0]["HasUnlimitedEnable"];
		$isVanish = $slicedData[0]["IsVanish"];

		$powerToolId = $slicedData[1]["PowertoolId"];
		$powerToolCommand = $slicedData[1]["PowertoolCommand"];
		$powerToolChatMacro = $slicedData[1]["PowertoolChatMacro"];

		if(!$result = $this->database->exec("INSERT INTO Sessions(Player, IsAFK, IsGod, IsMuted, MutedUntil, Nick, HasPvpEnabled, HasUnlimitedEnabled, IsVanished) VALUES ('" . $this->escape($lowerCaseName) . "', $isAfk, $isGod, $isMuted, $mutedUntil, $nick, $hasPvpEnabled, $hasUnlimitedEnabled, $isVanish);")) {
			return false;
		}
		if(!$result = $this->database->exec("INSERT INTO Powertools(Player, PowertoolId, PowertoolCommand, PowertoolChatMacro) VALUES ($powerToolId, '" . $this->escape($powerToolCommand) . "', '" . $this->escape($powerToolChatMacro) . "');")) {
			return false;
		}
		return true;
	}
}