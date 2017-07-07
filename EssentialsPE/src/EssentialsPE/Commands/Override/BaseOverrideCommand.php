<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Override;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;

abstract class BaseOverrideCommand extends BaseCommand {

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader, $name);
		$commandMap = $loader->getServer()->getCommandMap();
		$command = $commandMap->getCommand($name);
		$command->setLabel($name . "_disabled");
		$command->unregister($commandMap);
	}
}