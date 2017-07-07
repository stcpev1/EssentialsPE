<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns\Economy;

use EssentialsPE\EventHandlers\SpecialSigns\BaseSign;
use EssentialsPE\Loader;
use EssentialsPEconomy\EssentialsPEconomy;
use EssentialsPEconomy\Providers\BaseEconomyProvider;

abstract class EconomySign extends BaseSign {

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader, $name);
	}

	/**
	 * @return BaseEconomyProvider
	 */
	protected function getEconomyProvider(): BaseEconomyProvider {
		$module = $this->getLoader()->getModule(Loader::MODULE_ECONOMY);
		if($module instanceof EssentialsPEconomy) {
			return $module->getProvider();
		}
		return null;
	}
}