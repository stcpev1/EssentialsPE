<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;

class GodSessionComponent extends BaseSavedSessionComponent {

	/** @var bool */
	private $isGod = false;

	public function __construct(Loader $loader, PlayerSession $session, array $data = []) {
		parent::__construct($loader, $session);
		if(isset($data[BaseSessionProvider::IS_GOD])) {
			$this->setGod($data[BaseSessionProvider::IS_GOD]);
		}
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

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::IS_GOD, $this->isGod());
	}
}