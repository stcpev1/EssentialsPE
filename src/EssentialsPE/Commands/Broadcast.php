<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Broadcast extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "broadcast");
        $this->setPermission("essentials.broadcast");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $this->getAPI()->getServer()->broadcastMessage($this->getAPI()->getTranslation("commands.broadcast.syntax", implode(" ", $args)));
        return true;
    }
}
