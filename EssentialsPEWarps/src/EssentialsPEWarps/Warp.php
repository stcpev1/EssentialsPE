<?php

namespace EssentialsPEWarps;

use pocketmine\level\Location;
use pocketmine\Player;

class Warp {

	private $name;
	private $location;

	public function __construct(string $name, Location $location) {
		$this->name = $name;
		$this->location = $location;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param Player $player
	 */
	public function teleportTo(Player $player) {
		$player->teleport($this->getLocation());
	}

	/**
	 * @return Location
	 */
	public function getLocation(): Location {
		return $this->location;
	}

	/**
	 * @return string
	 */
	public function getPermission(): string {
		return "essentials.warps." . $this->name;
	}
}