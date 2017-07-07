<?php

declare(strict_types = 1);

namespace EssentialsPE\Configurable;

use EssentialsPE\Loader;

abstract class ConfigurableDataHolder {

	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;

		$this->check();
	}

	protected abstract function check();

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}