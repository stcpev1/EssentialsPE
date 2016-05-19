<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class Condense extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "condense");
        $this->setPermission("essentials.condense");
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
        if(!isset($args[0])){
            $args[0] = "inventory";
        }
        switch($args[0]){
            case "hand":
                $target = $sender->getInventory()->getItemInHand();
                break;
            case "inventory":
            case "all":
                $target = null;
                break;
            default: // Item name|id
                $target = $this->getAPI()->getItem($args[0]);
                if($target->getId() === Item::AIR){
                    $this->sendTranslation($sender, "commands.condense.unknown-item", $args[0]);
                    return false;
                }
                break;
        }
        if(!$this->getAPI()->condenseItems($sender->getInventory(), $target)){
            $this->sendTranslation($sender, "commands.condense.invalid-item", $target->getName());
        }
        $this->sendTranslation($sender, "commands.condense.confirmation", $target->getName());
        return true;
    }
}