<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;

abstract class BaseSavedSessionComponent extends BaseSessionComponent {

	public function __construct(Loader $loader, PlayerSession $session) {
		parent::__construct($loader, $session);
	}

	public abstract function save();
}