<?php

namespace EssentialsPE\Economy\Providers;

use EssentialsPEconomy\Loader;
use EssentialsPEconomy\Providers\EconomyProvider;
use EssentialsPEconomy\Providers\IEconomyProvider;
use pocketmine\Player;

class MySQLEconomyProvider extends EconomyProvider implements IEconomyProvider {

	/** @var \mysqli */
	private $database;

	public function __construct(Loader $loader) {
		parent::__construct($loader);

		$this->prepare();
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
		$this->database->query("INSERT INTO Economy(Player, Balance) VALUES ('" . $lowerCaseName . "', $balance)");
		return true;
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
		if($this->database->query("DELETE FROM Economy WHERE Player = '" . $lowerCaseName . "'")) {
			return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerExists(Player $player): bool {
		$lowerCaseName = strtolower($player->getName());

		$result = $this->database->query("SELECT Balance FROM Economy WHERE Player = '" . $lowerCaseName . "'");
		return $result->num_rows !== 0;
	}

	/**
	 * @param Player $player
	 *
	 * @return int
	 */
	public function getBalance(Player $player): int {
		$lowerCaseName = strtolower($player->getName());
		if(!$this->playerExists($player)) {
			return -1;
		}
		$result = $this->database->query("SELECT Balance FROM Economy WHERE Player = '" . $lowerCaseName . "'");
		return $result->fetch_array()[0];
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
		$result = $this->database->query("UPDATE Economy SET Balance = $amount WHERE Player = '" . $lowerCaseName . "'");
		return $result;
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
		$result = $this->database->query("UPDATE Economy SET Balance = Balance + $amount WHERE Player = '" . $lowerCaseName . "'");
		return $result;
	}

	/**
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function subtractFromBalance(Player $player, int $amount): bool {
		return $this->addToBalance($player, -$amount);
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
}