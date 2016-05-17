<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class Hat extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "hat");
        $this->setPermission("essentials.hat");
    }

    public function execute(CommandSender $sender, $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $remove = false;
        if(isset($args[0])){
            if($args[0] === "remove"){
                $remove = true;
            }else{
                $this->sendUsage($sender, $alias);
                return false;
            }
        }
        $new = Item::get(Item::AIR);
        $old = $sender->getInventory()->getHelmet();
        $slot = $sender->getInventory()->canAddItem($old) ? $sender->getInventory()->firstEmpty() : null;
        if(!$remove){
            $new = $sender->getInventory()->getItemInHand();
            if($new->getId() === Item::AIR){
                $this->sendTranslation($sender, "commands.hat.invalid-item");
                return false;
            }
            $slot = $sender->getInventory()->getHeldItemSlot();
        }
        $sender->getInventory()->setHelmet($new);
        if($slot !== null){
            $sender->getInventory()->setItem($slot, $old);
        }
        $this->sendTranslation($sender, "commands.hat." . ($new->getId() === Item::AIR ? "removed" : "added"));
        return true;
    }
}