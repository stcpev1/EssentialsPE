<?php

namespace EssentialsPEWarps;

use EssentialsPE\Loader;
use pocketmine\plugin\PluginBase;

class EssentialsPEWarps extends PluginBase {

	/** @var Loader $essentials */
	private $essentials;

	public function onLoad() {
		$this->essentials = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
		$this->essentials->addModule(Loader::MODULE_WARPS, "EssentialsPEWarps");
	}

	/**
	 * @return Loader
	 */
	public function getEssentialsPE(): Loader {
		return $this->essentials;
	}
}