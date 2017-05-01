<?php

namespace EssentialsPEconomy\Providers;

use EssentialsPEconomy\Loader;

abstract class EconomyProvider {

	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}