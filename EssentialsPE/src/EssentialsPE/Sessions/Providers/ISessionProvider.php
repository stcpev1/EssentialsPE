<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Providers;

use pocketmine\IPlayer;

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
	 * @param IPlayer $player
	 *
	 * @return bool
	 */
	public function playerDataExists(IPlayer $player): bool;

	/**
	 * Returns an array containing all the session data of a player.
	 *
	 * @param IPlayer $player
	 *
	 * @return array
	 */
	public function getPlayerData(IPlayer $player): array;

	/**
	 * Dumps all array session values into the database.
	 *
	 * @param IPlayer $player
	 * @param array   $data
	 *
	 * @return bool
	 */
	public function storePlayerData(IPlayer $player, array $data): bool;
}