<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BreakCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "break");
        $this->setPermission("essentials.break.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($block = $sender->getTargetBlock(100, [Block::AIR])) === null){
            $this->sendTranslation($sender, "error.near.block");
            return false;
        }elseif($block->getId() === Block::BEDROCK && !$sender->hasPermission("essentials.break.bedrock")){
            $this->sendTranslation($sender, "commands.break.bedrock-permission");
            return false;
        }
        $sender->getLevel()->setBlock($block, new Air(), true, true);
        return true;
    }
} 