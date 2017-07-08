<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\Loader;

class SignManager {

	/** @var Loader */
	private $loader;
	/** @var BaseSign[] */
	private $signs = [];

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @param BaseSign $sign
	 *
	 * @return bool
	 */
	public function registerSign(BaseSign $sign): bool {
		if($this->isSignRegistered($sign)) {
			return false;
		}
		$this->signs[$sign->getName()] = $sign;
		return true;
	}

	/**
	 * @param BaseSign $sign
	 *
	 * @return bool
	 */
	public function isSignRegistered(BaseSign $sign): bool {
		return isset($this->signs[$sign->getName()]);
	}

	/**
	 * @return BaseSign[]
	 */
	public function getRegisteredSigns(): array {
		return $this->signs;
	}

	/**
	 * @param string $name
	 *
	 * @return BaseSign|null
	 */
	public function getSpecialSignFromString(string $name) {
		foreach($this->signs as $key => $sign) {
			if(strtolower($key) === strtolower($name)) {
				return $sign;
			}
		}
		return null;
	}
}