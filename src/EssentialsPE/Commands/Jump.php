<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Jump extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "jump");
        $this->setPermission("essentials.jump");
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
        $block = $sender->getTargetBlock(100, BaseAPI::NON_SOLID_BLOCKS);
        if($block === null){
            $this->sendTranslation($sender, "error.near-block");
            return false;
        }
        if(!$sender->getLevel()->getBlock($block->add(0, 2))->isSolid()){
            $sender->teleport($block->add(0, 1));
            return true;
        }
        switch($side = $sender->getDirection()){
            case 0:
            case 1:
                $side += 3;
                break;
            case 3:
                $side += 2;
                break;
            default:
                break;
        }
        if(!$block->getSide($side)->isSolid()){
            $sender->teleport($block);
        }
        return true;
    }
}
