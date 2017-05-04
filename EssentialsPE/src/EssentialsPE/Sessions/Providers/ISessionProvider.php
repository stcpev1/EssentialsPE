<?php

namespace EssentialsPE\Sessions\Providers;


use pocketmine\Player;

interface ISessionProvider {

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
	 * Saves the database. Not required for all databases.
	 */
	public function save();

	/**
	 * Checks whether the given player has a session available.
	 *
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function playerDataExists(Player $player): bool;

	/**
	 * Returns an array containing all the session data of a player.
	 *
	 * @param Player $player
	 *
	 * @return array
	 */
	public function getPlayerData(Player $player): array;

	/**
	 * Dumps all array session values into the database.
	 *
	 * @param Player $player
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function storePlayerData(Player $player, array $data): bool;
}