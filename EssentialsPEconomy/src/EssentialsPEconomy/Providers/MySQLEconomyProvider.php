<?php

namespace EssentialsPEconomy\Providers;

use EssentialsPEconomy\Loader;
use pocketmine\Player;

class MySQLEconomyProvider extends EconomyProvider {

	/** @var \mysqli */
	private $database;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	/**
	 * @param string $string
	 *
	 * @return string
	 */
	private function escape(string $string): string {
		return $this->database->real_escape_string($string);
	}

	public function prepare() {
		$config = $this->getLoader()->getEssentialsPE()->getConfigurableData()->getConfiguration();
		$this->database = new \mysqli($config->get("MySQL.Host"), $config->get("MySQL.User"), $config->get("MySQL.Password"), $config->get("MySQL.Database"), $config->get("MySQL.Port"));
		if($this->database->connect_error !== null) {
			throw new \mysqli_sql_exception("No connection could be made to the MySQL server. " . $this->database->connect_error);
		}
		$query = "CREATE TABLE IF NOT EXISTS Economy(Player VARCHAR(20) PRIMARY KEY, Balance INT);";
		$success = $this->database->query($query);
		if(!$success) {
			throw new \mysqli_sql_exception("An error occurred when creating the main table. " . $this->database->error);
		}
	}

	/**
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getEconomyTop(int $limit = 10): array {
		$result = $this->database->query("SELECT * FROM Economy ORDER BY Balance DESC LIMIT 10");
		$return = [];
		for($i = 0; $i <= $limit; $i++) {
			if($array = $result->fetch_assoc()) {
				$return[$array[0]] = $array[1];
			}
		}
		return $return;
	}

	/**
	 * @param Player $player
	 * @param int    $balance
	 *
	 * @return bool
	 */
	public function addPlayer(Player $player, int $balance = -1): bool {
		if($balance === -1) {
			$balance = $this->getLoader()->getConfiguration()->get("Default-Balance");
		}
		$lowerCaseName = strtolower($player->getName());

		if($this->playerExists($player)) {
			return false;
		}
		$this->database->query("INSERT INTO Economy(Player, Balance) VALUES ('" . $this->escape($lowerCaseName) . "', $balance)");
		return true;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerExists(Player $player): bool {
		$lowerCaseName = strtolower($player->getName());

		$result = $this->database->query("SELECT Balance FROM Economy WHERE Player = '" . $this->escape($lowerCaseName) . "'");
		return $result->num_rows !== 0;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function removePlayer(Player $player): bool {
		$lowerCaseName = strtolower($player->getName());

		if(!$this->playerExists($player)) {
			return false;
		}
		if($this->database->query("DELETE FROM Economy WHERE Player = '" . $this->escape($lowerCaseName) . "'")) {
			return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function setBalance(Player $player, int $amount): bool {
		$lowerCaseName = strtolower($player->getName());
		if(!$this->playerExists($player)) {
			return false;
		}
		if($amount < $this->getLoader()->getConfiguration()->get("Minimum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't be below the minimum balance.");
		}
		if($amount > $this->getLoader()->getConfiguration()->get("Maximum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't exceed the maximum balance.");
		}
		$result = $this->database->query("UPDATE Economy SET Balance = $amount WHERE Player = '" . $this->escape($lowerCaseName) . "'");
		return $result;
	}

	/**
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function subtractFromBalance(Player $player, int $amount): bool {
		if($this->getBalance($player) - $amount < $this->getLoader()->getConfiguration()->get("Minimum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't be below the minimum balance.");
		}
		return $this->addToBalance($player, -$amount);
	}

	/**
	 * @param Player $player
	 *
	 * @return int|bool
	 */
	public function getBalance(Player $player) {
		$lowerCaseName = strtolower($player->getName());
		if(!$this->playerExists($player)) {
			return false;
		}
		$result = $this->database->query("SELECT Balance FROM Economy WHERE Player = '" . $this->escape($lowerCaseName) . "'");
		return $result->fetch_array()[0];
	}

	/**
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function addToBalance(Player $player, int $amount): bool {
		$lowerCaseName = strtolower($player->getName());
		if(!$this->playerExists($player)) {
			return false;
		}
		if($amount + $this->getBalance($player) > $this->getLoader()->getConfiguration()->get("Maximum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't be above the maximum balance.");
		}
		$result = $this->database->query("UPDATE Economy SET Balance = Balance + $amount WHERE Player = '" . $this->escape($lowerCaseName) . "'");
		return $result;
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
}