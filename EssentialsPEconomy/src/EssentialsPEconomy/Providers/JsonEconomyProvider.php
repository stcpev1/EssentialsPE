<?php

namespace EssentialsPEconomy\Providers;


use EssentialsPEconomy\Loader;
use pocketmine\Player;
use pocketmine\utils\Config;

class JsonEconomyProvider extends EconomyProvider {

	private $database;
	/** @var Config $data */
	private $data;

	public function __construct(Loader $loader) {
		parent::__construct($loader);
	}

	public function prepare() {
		$this->data = new Config($this->getLoader()->getDataFolder() . "economy.json", Config::JSON);
	}

	/**
	 * @return bool
	 */
	public function closeDatabase(): bool {
		$this->save();
		return true;
	}

	public function save() {
		$balances = json_decode(file_get_contents($file = $this->getLoader()->getDataFolder() . "economy.json"), true);
		foreach($this->database as $name => $balance) {
			$balances[$name] = $balance;
		}
		file_put_contents($file, json_encode($balances));
	}

	/**
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getEconomyTop(int $limit = 10): array {
		$sortedArray = asort($this->database);
		$topList = [];
		for($i = 0; $i <= 10; $i++) {
			$topList[] = $sortedArray[$i];
		}
		return $topList;
	}

	/**
	 * @param Player $player
	 * @param int    $balance
	 *
	 * @return bool
	 */
	public function addPlayer(Player $player, int $balance = -1): bool {
		$lowerCaseName = strtolower($player->getName());
		if($this->playerExists($player)) {
			return false;
		}
		if($balance === -1) {
			$balance = $this->getLoader()->getConfiguration()->get("Default-Balance");
		}
		if($balance < $this->getLoader()->getConfiguration()->get("Minimum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't be below the minimum balance.");
		}
		if($balance > $this->getLoader()->getConfiguration()->get("Maximum-Balance")) {
			throw new \OutOfBoundsException("A Player's balance can't exceed the maximum balance.");
		}
		$this->database[$lowerCaseName] = $balance;
		return true;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerExists(Player $player): bool {
		$lowerCaseName = strtolower($player->getName());
		if(!empty($value = $this->data->get($lowerCaseName))) {
			if(!isset($this->database[$lowerCaseName])) {
				$this->database[$lowerCaseName] = $value;
			}
			return true;
		}
		return false;
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
		unset($this->database[$lowerCaseName]);
		return true;
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
		$this->database[$lowerCaseName] = $amount;
		return true;
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
		return $this->database[$lowerCaseName];
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
		$this->database[$lowerCaseName] += $amount;
		return true;
	}
}