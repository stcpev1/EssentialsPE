<?php

namespace EssentialsPEWarps\Providers;

use EssentialsPEWarps\EssentialsPEWarps;

abstract class BaseProvider implements IWarpProvider {

	protected $loader;

	public function __construct(EssentialsPEWarps $loader) {
		$this->loader = $loader;

		$this->prepare();
	}

	/**
	 * @return bool
	 */
	public abstract function prepare(): bool;

	/**
	 * @return EssentialsPEWarps
	 */
	public function getLoader(): EssentialsPEWarps {
		return $this->loader;
	}

	/**
	 * @return string
	 */
	public function getWarpList(): string {
		$warps = [];
		foreach($this->getAllWarps() as $warp) {
			$warps[] = $warp["Name"];
		}
		return implode(" ", $warps);
	}

	/**
	 * @return bool
	 */
	public abstract function close(): bool;
}