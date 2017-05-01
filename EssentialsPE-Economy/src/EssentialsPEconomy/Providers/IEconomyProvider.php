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
	public function addPlayer(Player $player, int $amount = -1): bool;

	/**
	 * Checks if the player has been registered to the database.
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerExists(Player $player): bool;
}