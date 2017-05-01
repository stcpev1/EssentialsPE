<?php

namespace EssentialsPEWarps;

use pocketmine\level\Position;
use pocketmine\Player;

class Warp {

	private $name;
	private $position;

	public function __construct(string $name, Position $position) {
		$this->name = $name;
		$this->position = $position;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return Position
	 */
	public function getPosition(): Position {
		return $this->position;
	}

	/**
	 * @param Player $player
	 */
	public function teleportTo(Player $player) {
		$player->teleport($this->getPosition());
	}
}