<?php

namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DelayedTeleportTask extends BaseTask {

	private $player;
	private $originalPosition;
	private $teleportPosition;

	public function __construct(Loader $loader, Player $player, Position $originalPosition, Position $teleportPosition) {
		parent::__construct($loader);
		$this->player = &$player;
		$this->originalPosition = $originalPosition;
		$this->teleportPosition = $teleportPosition;
	}

	public function onRun($currentTick) {
		if($this->player->isOnline()) {
			if($this->player->getPosition()->equals($this->originalPosition)) {
				$this->player->teleport($this->teleportPosition);
			} else {
				$this->player->sendMessage($this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.teleport-cancel"));
				$this->player->teleport($this->player);
			}
		}
	}
}