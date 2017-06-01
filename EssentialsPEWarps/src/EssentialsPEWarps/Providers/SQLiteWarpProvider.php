<?php

namespace EssentialsPEWarps\Providers;

use EssentialsPE\Utils\ChatUtils;
use EssentialsPEWarps\EssentialsPEWarps;
use EssentialsPEWarps\Warp;
use pocketmine\level\Location;

class SQLiteWarpProvider extends BaseProvider {

	/** @var \SQLite3 */
	private $database;

	public function __construct(EssentialsPEWarps $loader) {
		parent::__construct($loader);
	}

	/**
	 * @return bool
	 */
	public function prepare(): bool {
		if(!file_exists($file = $this->getLoader()->getDataFolder() . "warps.sqlite3")) {
			file_put_contents($file, "");
		}
		$this->database = new \SQLite3($file);
		$query = "CREATE TABLE IF NOT EXISTS Warps(
					  Name VARCHAR(32) PRIMARY KEY,
					  X INT,
					  Y INT,
					  Z INT,
					  Level VARCHAR(32),
					  Yaw INT,
					  Pitch INT
					  )";
		return $this->database->exec($query);
	}

	/**
	 * @return bool
	 */
	public function close(): bool {
		if($this->database instanceof \SQLite3) {
			$this->database->close();
			return true;
		}
		return false;
	}

	/**
	 * @param string   $name
	 * @param Location $location
	 *
	 * @return bool
	 */
	public function createWarp(string $name, Location $location): bool {
		if(ChatUtils::validateName($name) === false) {
			return false;
		}
		if($this->warpExists($name)) {
			return $this->updateWarp($name, $location);
		}
		$level = $location->getLevel()->getName();
		$query = "INSERT INTO Warps(Name, X, Y, Z, Level, Yaw, Pitch) VALUES ('" . $this->escape($name) . "', $location->x, $location->y, $location->z, '" . $this->escape($level) . "', $location->yaw, $location->pitch)";
		return $this->database->exec($query);
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function warpExists(string $name): bool {
		if(ChatUtils::validateName($name) === false) {
			return false;
		}
		$query = "SELECT * FROM Warps WHERE Name = '" . $this->escape($name) . "'";
		return !empty($this->database->query($query)->fetchArray(SQLITE3_ASSOC));
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
	 * @param string   $name
	 * @param Location $location
	 *
	 * @return bool
	 */
	public function updateWarp(string $name, Location $location): bool {
		if(ChatUtils::validateName($name) === false) {
			return false;
		}
		$level = $location->getLevel()->getName();
		$query = "UPDATE Warps SET 
					X = $location->x,
					Y = $location->y,
					Z = $location->z,
					Level = '" . $this->escape($level) . "',
					Yaw = $location->yaw,
					Pitch = $location->pitch
					WHERE Name = '" . $this->escape($name) . "'";
		return $this->database->exec($query);
	}

	/**
	 * @param string $name
	 *
	 * @return Warp
	 */
	public function getWarp(string $name): Warp {
		if(ChatUtils::validateName($name) === false) {
			return null;
		}
		if(!$this->warpExists($name)) {
			return null;
		}
		$query = "SELECT * FROM Warps WHERE Name = '" . $this->escape($name) . "'";
		$data = $this->database->query($query)->fetchArray(SQLITE3_ASSOC);
		if(!$this->getLoader()->getServer()->isLevelLoaded($data["Level"])) {
			$this->getLoader()->getServer()->loadLevel($data["Level"]);
		}
		$location = new Location($data["X"], $data["Y"], $data["Z"], $data["Yaw"], $data["Pitch"], $this->getLoader()->getServer()->getLevelByName($data["Level"]));
		return new Warp($name, $location);
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function deleteWarp(string $name): bool {
		if(ChatUtils::validateName($name) === false) {
			return null;
		}
		$query = "DELETE FROM Warps WHERE Name = '" . $this->escape($name) . "'";
		return $this->database->exec($query);
	}

	/**
	 * @return array
	 */
	public function getAllWarps(): array {
		$query = "SELECT * FROM Warps";
		return $this->database->query($query)->fetchArray(SQLITE3_ASSOC);
	}
}