<?php

namespace EssentialsPEWarps\Providers;

use EssentialsPEWarps\Warp;
use pocketmine\level\Location;

interface IWarpProvider {

	/**
	 * @param string   $name
	 * @param Location $location
	 *
	 * @return bool
	 */
	public function createWarp(string $name, Location $location): bool;

	/**
	 * @param string $name
	 *
	 * @return Warp
	 */
	public function getWarp(string $name): Warp;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function warpExists(string $name): bool;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function deleteWarp(string $name): bool;

	/**
	 * @return Warp[]
	 */
	public function getAllWarps(): array;
}