<?php

namespace EssentialsPEWarps;

use EssentialsPE\Loader;
use EssentialsPEWarps\Providers\BaseProvider;
use EssentialsPEWarps\Providers\JsonWarpProvider;
use EssentialsPEWarps\Providers\MySQLWarpProvider;
use EssentialsPEWarps\Providers\SQLiteWarpProvider;
use EssentialsPEWarps\Providers\YamlWarpProvider;
use pocketmine\plugin\PluginBase;

class EssentialsPEWarps extends PluginBase {

	/** @var Loader $essentials */
	private $essentials;
	private $provider;

	public function onLoad() {
		$this->essentials = $this->getServer()->getPluginManager()->getPlugin("EssentialsPE");
		$this->essentials->addModule(Loader::MODULE_WARPS, "EssentialsPEWarps");
	}

	public function onEnable() {
		$this->selectProvider();
	}

	/**
	 * @return BaseProvider
	 */
	public function selectProvider(): BaseProvider {
		switch(strtolower($this->getEssentialsPE()->getProvider())) {
			case "yaml":
			case "yml":
				$this->provider = new YamlWarpProvider($this);
				break;
			case "json":
				$this->provider = new JsonWarpProvider($this);
				break;
			case "mysql":
				$this->provider = new MySQLWarpProvider($this);
				break;
			default:
			case "sqlite":
			case "sqlite3":
				$this->provider = new SQLiteWarpProvider($this);
				break;
		}
		return $this->provider;
	}

	/**
	 * @return Loader
	 */
	public function getEssentialsPE(): Loader {
		return $this->essentials;
	}

	/**
	 * @return BaseProvider
	 */
	public function getProvider(): BaseProvider {
		return $this->provider;
	}
}