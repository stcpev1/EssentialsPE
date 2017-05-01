<?php

namespace EssentialsPE\Economy;

use EssentialsPE\Loader;

abstract class Provider {

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