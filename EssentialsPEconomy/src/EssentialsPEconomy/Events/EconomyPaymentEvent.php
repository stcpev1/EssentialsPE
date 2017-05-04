<?php

namespace EssentialsPEconomy\Events;


use EssentialsPEconomy\EssentialsPEconomy;
use pocketmine\Player;

class EconomyPaymentEvent extends EconomyTransactionEvent {

	private $sender;

	public function __construct(EssentialsPEconomy $loader, Player $receiver, Player $sender, $amount) {
		parent::__construct($loader, $receiver, $amount);
		$this->sender = $sender;
	}

	/**
	 * @return Player
	 */
	public function getSender(): Player {
		return $this->sender;
	}
}