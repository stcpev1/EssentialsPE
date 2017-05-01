<?php

namespace EssentialsPEconomy\Providers;

use pocketmine\Player;

interface IEconomyProvider {

	/**
	 * Prepares the database for usage.
	 */
	public function prepare();

	/**
	 * Closes a database if running and returns true if so, otherwise false.
	 *
	 * @return bool
	 */
	public function closeDatabase(): bool;

	/**
	 * Registers a user to the database with the default money, or different if $amount is specified.
	 *
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function addPlayer(Player $player, int $balance = -1): bool;

	/**
	 * Removes the user from the database, requiring the player to be added again.
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function removePlayer(Player $player): bool;

	/**
	 * Checks if the player has been registered to the database.
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerExists(Player $player): bool;

	/**
	 * Returns the current balance of a player. This number can be negative. Returns false if the action fails.
	 *
	 * @param Player $player
	 *
	 * @return int|bool
	 */
	public function getBalance(Player $player);

	/**
	 * Sets the balance of a player to the given amount, if the amount is higher than the Minimum balance and lower than the Maximum balance.
	 *
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function setBalance(Player $player, int $amount): bool;

	/**
	 * Adds an amount to the player balance, if the total balance is below the maximum balance.
	 *
	 * @param Player $player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function addToBalance(Player $player, int $amount): bool;

	/**
	 * Subtracts an amount from the player balance, if the total balance is above the minimum balance.
	 *
	 * @param Player $Player
	 * @param int    $amount
	 *
	 * @return bool
	 */
	public function subtractFromBalance(Player $player, int $amount): bool;

	/**
	 * Saves the database. Not required for all databases.
	 */
	public function save();
}