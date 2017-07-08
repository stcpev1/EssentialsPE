<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use pocketmine\Player;

class TeleportRequestSessionComponent extends BaseSessionComponent {

	const MODE_TELEPORT_TO = 0;
	const MODE_TELEPORT_HERE = 1;

	/** @var array */
	private $receivedRequests = [];
	/** @var array */
	private $latestRequest = [];

	public function __construct(Loader $loader, PlayerSession $session) {
		parent::__construct($loader, $session);
	}

	/**
	 * @param Player $player
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public function sendTeleportRequestFrom(Player $player, int $mode = self::MODE_TELEPORT_TO): bool {
		$this->receivedRequests[$player->getName()] = [
			"mode" => $mode,
			"time" => time()
		];
		$this->setLatestRequest([
			$player->getName() => [
				"mode" => $mode,
				"time" => time()
			]
		]);
		return true;
	}

	/**
	 * @param array $request
	 */
	private function setLatestRequest(array $request) {
		$this->latestRequest = $request;
	}

	/**
	 * @return array
	 */
	public function getLatestRequest(): array {
		return $this->latestRequest;
	}

	/**
	 * @return bool
	 */
	public function hasARequest(): bool {
		if(empty($this->receivedRequests)) {
			return false;
		}
		return true;
	}

	/**
	 * @return bool
	 */
	public function acceptLatestRequest(): bool {
		if(empty($this->latestRequest)) {
			return false;
		}
		$player = null;
		foreach($this->latestRequest as $playerName => $value) {
			$player = $this->getLoader()->getServer()->getPlayer($playerName);
		}
		return $this->acceptTeleportRequest($player);
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function acceptTeleportRequest(Player $player): bool {
		if(!$this->hasAValidRequestFrom($player)) {
			return false;
		}
		if($this->getRequestFrom($player)["mode"] === 1) {
			$this->getPlayer()->teleport($player);
		} else {
			$player->teleport($this->getPlayer());
		}
		$this->removeRequest($player);
		return true;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function hasAValidRequestFrom(Player $player): bool {
		if(!$this->hasARequestFrom($player)) {
			return false;
		}
		if(time() - $this->receivedRequests[$player->getName()]["time"] > $this->getLoader()->getConfigurableData()->getConfiguration()->get("Teleporting.Tpa-Valid-Time")) {
			return false;
		}
		return true;
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function hasARequestFrom(Player $player): bool {
		if(isset($this->receivedRequests[$player->getName()])) {
			return true;
		}
		return false;
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function getRequestFrom(Player $player): array {
		return $this->receivedRequests[$player->getName()];
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function removeRequest(Player $player): bool {
		unset($this->receivedRequests[$player->getName()]);
		return true;
	}
}