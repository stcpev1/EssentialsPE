<?php

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSessionComponent;
use EssentialsPE\Sessions\PlayerSession;

class GodSessionComponent extends BaseSessionComponent {

	private $isGod = false;

	public function __construct(Loader $loader, PlayerSession $session, bool $isGod = false) {
		parent::__construct($loader, $session);
		$this->setGod($isGod);
	}

	/**
	 * @param bool $value
	 *
	 * @return bool
	 */
	public function setGod(bool $value = true): bool {
		if($this->isGod() === $value) {
			return false;
		}
		$this->isGod = $value;
		return true;
	}

	/**
	 * @return bool
	 */
	public function isGod(): bool {
		return $this->isGod;
	}

	/**
	 * @return bool
	 */
	public function switchGod(): bool {
		$this->setGod(!$this->isGod());
		return true;
	}
}