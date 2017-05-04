<?php

namespace EssentialsPE\Sessions\Providers;

use EssentialsPE\Loader;

abstract class BaseSessionProvider implements ISessionProvider {

	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
		$this->prepare();
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}