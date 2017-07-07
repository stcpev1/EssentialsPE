<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Warps;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPEconomy\EssentialsPEconomy;
use EssentialsPEWarps\Providers\BaseProvider;

abstract class BaseWarpCommand extends BaseCommand {

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader, $name);
		$this->setModule(Loader::MODULE_WARPS);
	}

	/**
	 * @return BaseProvider
	 */
	public function getWarps(): BaseProvider {
		$plugin = $this->getLoader()->getModule(Loader::MODULE_WARPS);
		if($plugin instanceof EssentialsPEconomy) {
			return $plugin->getProvider();
		}
		return null;
	}
}