<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\block\Sapling;
use pocketmine\command\CommandSender;
use pocketmine\level\generator\object\BigTree;
use pocketmine\Player;

class BigTreeCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "bigtree");
        $this->setPermission("essentials.bigtree");
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        #$transparent = [];
        $block = $sender->getTargetBlock(100, BaseAPI::NON_SOLID_BLOCKS);
        /*while(!$block->isSolid){
            if($block === null){
                break;
            }
            $transparent[] = $block->getID();
            $block = $sender->getTargetBlock(100, $transparent);
        }*/
        if($block === null){
            $this->sendTranslation($sender, "error.near-block");
            return false;
        }
        switch(strtolower($args[0])){
            case "jungle":
                $type = Sapling::JUNGLE;
                break;
            case "redwood":
                $type = Sapling::SPRUCE;
                break;
            case "tree":
            default:
                $type = Sapling::OAK;
                break;
        }
        $tree = new BigTree();
        $tree->placeObject($sender->getLevel(), $block->getFloorX(), ($block->getFloorY() + 1), $block->getFloorZ(), $type);
        return true;
    }
} 