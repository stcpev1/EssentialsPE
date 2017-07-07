<?php

declare(strict_types = 1);

namespace EssentialsPE\Commands\Miscellaneous;

use EssentialsPE\Commands\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BreakCommand extends BaseCommand {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "break");
		$this->setPermission("essentials.command.break.use");
	}

	/**
	 * @param CommandSender $sender
	 * @param string        $commandLabel
	 * @param array         $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
		if(!$sender instanceof Player || count($args) !== 0) {
			$this->sendUsage($sender, $commandLabel);
			return true;
		}
		if(($block = $sender->getTargetBlock(100, [Block::AIR])) === null) {
			$this->sendMessageContainer($sender, "error.near.block");
			return true;
		} elseif($block->getId() === Block::BEDROCK && !$sender->hasPermission("essentials.command.break.bedrock")) {
			$this->sendMessageContainer($sender, "commands.break.bedrock-permission");
			return true;
		}
		$sender->getLevel()->setBlock($block, Block::get(Block::AIR), true, true);
		return true;
	}
}