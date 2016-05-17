<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class ItemCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "item");
        $this->setPermission("essentials.item");
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
        if(!$sender instanceof Player || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $this->sendTranslation($sender, "error.gamemode-error", $this->getAPI()->getServer()->getGamemodeString($gm));
            return false;
        }

        //Getting the item...
        $item = $this->getAPI()->getItem($item_name = array_shift($args));

        if($item->getId() === Item::AIR){
            $this->sendTranslation($sender, "commands.item.unknown", $item_name);
            return false;
        }elseif(!$sender->hasPermission("essentials.itemspawn.item-all") && !$sender->hasPermission("essentials.itemspawn.item-" . $item->getName() && !$sender->hasPermission("essentials.itemspawn.item-" . $item->getId()))){
            $this->sendTranslation($sender, "commands.item.need-permission", $item->getName());
            return false;
        }

        //Setting the amount...
        $amount = array_shift($args);
        $item->setCount(isset($amount) && is_numeric($amount) ? $amount : ($sender->hasPermission("essentials.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize()));

        //Getting other values... TODO
        /*foreach($args as $a){
            //Example
            if(stripos(strtolower($a), "color") !== false){
                $v = explode(":", $a);
                $color = $v[1];
            }
        }*/

        //Giving the item...
        $this->sendTranslation($sender, "commands.item.confirmation", $item->getCount(), ($item->getName() === "Unknown" ? $item_name : $item->getName()));
        $sender->getInventory()->setItem($sender->getInventory()->firstEmpty(), $item);
        return false;
    }
}
