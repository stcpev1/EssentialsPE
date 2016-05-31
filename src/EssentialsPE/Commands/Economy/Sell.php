<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class Sell extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sell");
        $this->setPermission("essentials.sell");
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
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $this->sendTranslation($sender, "error.gamemode-error", $this->getAPI()->getServer()->getGamemodeString($gm));
            return false;
        }
        if(strtolower($args[0]) === "hand"){
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() === Item::AIR){
                $this->sendTranslation($sender, "commands.sell.empty-hand");
                return false;
            }
        }else{
            $item = $this->getAPI()->getItem($args[0]);
            if($item->getId() === Item::AIR){
                $this->sendTranslation($sender, "error.unknown-item");
                return false;
            }
        }
        if(!$sender->getInventory()->contains($item)){
            $this->sendTranslation($sender, "commands.sell.not-owned", $item->getName());
            return false;
        }
        if(isset($args[1]) && !is_numeric($args[1])){
            $this->sendTranslation($sender, "error.invalid-amount");
            return false;
        }

        $amount = $this->getAPI()->sellPlayerItem($sender, $item, (isset($args[1]) ? $args[1] : null));
        if(!$amount){
            $this->sendTranslation($sender, "commands.worth.unknown", $item->getName());
            return false;
        }elseif($amount === -1){
            $this->sendTranslation($sender, "commands.sell.amount-owned");
            return false;
        }
        $profit = $this->getAPI()->getCurrencySymbol();
        if(is_array($amount)){
            $profit .= $amount[0] * $amount[1];
            $amount = $amount[0];
        }else{
            $profit .= $amount;
        }
        $this->sendTranslation($sender, "commands.sell.confirmation", $amount, $this->getAPI()->getCurrencySymbol() . $profit);
        return true;
    }
}