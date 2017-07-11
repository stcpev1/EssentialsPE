<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Chat;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class IgnoreCommand extends BaseCommand {

	public function __construct(Loader $loader, $name) {
		parent::__construct($loader, $name);
		$this->setPermission("essentials.command.ignore");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {

	}
}