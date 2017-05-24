<?php

namespace EssentialsPE\Sessions\Providers;


use pocketmine\OfflinePlayer;
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
	 * @param OfflinePlayer $player
	 *
	 * @return bool
	 */
	public function playerDataExists(OfflinePlayer $player): bool;

	/**
	 * Returns an array containing all the session data of a player.
	 *
	 * @param OfflinePlayer $player
	 *
	 * @return array
	 */
	public function getPlayerData(OfflinePlayer $player): array;

	/**
	 * Dumps all array session values into the database.
	 *
	 * @param OfflinePlayer $player
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function storePlayerData(OfflinePlayer $player, array $data): bool;
}