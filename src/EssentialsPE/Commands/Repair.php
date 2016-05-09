<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Repair extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "repair");
        $this->setPermission("essentials.repair.use");
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
        if(!isset($args[0])){
            $args[0] = "hand";
        }
        $a = strtolower($args[0]);
        if($a !== "all" || $a !== "hand" || $a !== "armor" || $a !== "inventory"){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if($a === "hand"){
            if(!$this->getAPI()->isRepairable($sender->getInventory()->getItemInHand())){
                $this->sendTranslation($sender, "commands.repair.invalid-item");
                return false;
            }
            $sender->getInventory()->getItemInHand()->setDamage(0);
            $this->sendTranslation($sender, "commands.repair.confirm-individual");
        }else{
            $all = $a === "all";
            if(($all && $sender->hasPermission("essentials.repair.inventory")) || $a === "inventory"){
                if(!$sender->hasPermission("essentials.repair.all") && !$sender->hasPermission("essentials.repair.inventory")){
                    $this->sendTranslation($sender, "commands.repair.inventory.permission");
                    return false;
                }
                foreach($sender->getInventory()->getContents() as $item){
                    if($this->getAPI()->isRepairable($item)){
                        $item->setDamage(0);
                    }
                }
                $this->sendTranslation($sender, "commands.repair.confirm-inventory");
            }
            if(($all && $sender->hasPermission("essentials.repair.armor")) || $a === "armor"){
                if(!$sender->hasPermission("essentials.repair.all") && !$sender->hasPermission("essentials.repair.armor")){
                    $this->sendTranslation($sender, "armor-permission");
                    return false;
                }
                foreach($sender->getInventory()->getArmorContents() as $item){
                    if($this->getAPI()->isRepairable($item)){
                        $item->setDamage(0);
                    }
                }
                $this->sendTranslation($sender, "commands.repair.confirm-armor");
            }
        }
        return true;
    }
}
