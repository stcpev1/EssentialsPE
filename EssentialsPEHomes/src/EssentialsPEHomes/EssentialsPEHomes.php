<?php

namespace EssentialsPEHomes;

use EssentialsPE\Loader;
use pocketmine\plugin\PluginBase;

class EssentialsPEHomes extends PluginBase {

	/** @var Loader $essentials */
	private $essentials;

	public function onLoad() {
		$this->essentials = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
		$this->essentials->addModule(Loader::MODULE_HOMES, "EssentialsPEHomes");
	}

	/**
	 * @return Loader
	 */
	public function getEssentialsPE(): Loader {
		return $this->essentials;
	}
}