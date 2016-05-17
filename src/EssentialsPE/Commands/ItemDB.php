<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ItemDB extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "itemdb");
        $this->setPermission("essentials.itemdb");
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
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        $t = "commands.itemdb.i-" . ($this->getAPI()->isRepairable($item) ? "durability" : "metadata");
        $a = $item->getDamage();
        if(isset($args[0])){
            switch(strtolower($args[0])){
                case "name":
                    $t = "commands.itemdb.i-name";
                    $a = $item->getName();
                    break;
                case "id":
                    $t = "commands.itemdb.i-id";
                    $a = $item->getId();
                    break;
                case "durability":
                case "dura":
                case "metadata":
                case "meta":
                default:
                    break;
            }
        }
        $this->sendTranslation($sender, $t, $a);
        return true;
    }
} 