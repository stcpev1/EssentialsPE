<?php

namespace EssentialsPEconomy\Providers;

use EssentialsPEconomy\Loader;

abstract class EconomyProvider implements IEconomyProvider {

	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
		$this->prepare();
	}

	/**
	 * Returns the currency symbol configured in the config.yml.
	 *
	 * @return string
	 */
	public function getCurrencySymbol(): string {
		return $this->getLoader()->getConfiguration()->get("Currency-Symbol");
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}