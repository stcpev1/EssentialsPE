<?php

namespace EssentialsPEconomy\Events;

use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\Player;

class EconomyTransactionEvent extends BaseEvent {

	public static $handlerList = null;

	private $amount;
	private $receiver;

	public function __construct(EssentialsPEconomy $loader, Player $receiver, int $amount) {
		parent::__construct($loader);
		$this->receiver = $receiver;
		$this->amount = $amount;
	}

	/**
	 * @return Player
	 */
	public function getReceiver(): Player {
		return $this->receiver;
	}

	/**
	 * @return int
	 */
	public function getAmount(): int {
		return $this->amount;
	}

	/**
	 * @param int $amount
	 */
	public function setAmount(int $amount) {
		$this->amount = $amount;
	}
}