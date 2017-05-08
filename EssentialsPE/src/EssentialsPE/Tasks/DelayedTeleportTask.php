<?php

namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\level\Position;
use pocketmine\Player;

class DelayedTeleportTask extends BaseTask {

	private $player;
	private $teleportPosition;

	public function __construct(Loader $loader, Player $player, Position $teleportPosition) {
		parent::__construct($loader);
		$this->player = &$player;
		$this->teleportPosition = $teleportPosition;
	}

	public function onRun($currentTick) {
		if($this->player->isOnline()) {
			$this->player->teleport($this->teleportPosition);
		}
	}
}