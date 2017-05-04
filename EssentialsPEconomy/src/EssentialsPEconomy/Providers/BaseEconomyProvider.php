<?php

namespace EssentialsPEconomy\Providers;

use EssentialsPEconomy\EssentialsPEconomy;

abstract class BaseEconomyProvider implements IEconomyProvider {

	protected $loader;

	public function __construct(EssentialsPEconomy $loader) {
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
	 * @return EssentialsPEconomy
	 */
	public function getLoader(): EssentialsPEconomy {
		return $this->loader;
	}
}