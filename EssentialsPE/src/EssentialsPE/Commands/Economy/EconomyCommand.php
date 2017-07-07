<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Economy;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPEconomy\EssentialsPEconomy;
use EssentialsPEconomy\Providers\BaseEconomyProvider;

abstract class EconomyCommand extends BaseCommand {

	public function __construct(Loader $loader, $name) {
		parent::__construct($loader, $name);
		$this->setModule(Loader::MODULE_ECONOMY);
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