<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class SetWorth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setworth");
        $this->setPermission("essentials.setworth");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!is_numeric($args[0]) || (int) $args[0] < 0){
            $this->sendTranslation($sender, "commands.setworth.invalid");
            return false;
        }elseif(($id = $sender->getInventory()->getItemInHand()->getId()) === Item::AIR){
            $this->sendTranslation($sender, "commands.worth.empty-hand");
            return false;
        }
        $this->sendTranslation($sender, "commands.setworth.confirmation");
        $this->getAPI()->setItemWorth($id, (int) $args[0]);
        return true;
    }
}