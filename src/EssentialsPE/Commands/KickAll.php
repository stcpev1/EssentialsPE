<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class KickAll extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kickall");
        $this->setPermission("essentials.kickall");
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
        if(($count = count($this->getAPI()->getServer()->getOnlinePlayers())) < 1 || ($sender instanceof Player && $count < 2)){
            $this->sendTranslation($sender, "commands.kickall.empty-server");
            return false;
        }
        $reason = $this->getAPI()->getTranslation("commands.kickall.reason", (count($args) < 1 ? ["commands.kickall.unknown-reason"] : implode(" ", $args)));
        foreach($this->getAPI()->getServer()->getOnlinePlayers() as $p){
            if($p != $sender){
                $p->kick($reason, false);
            }
        }
        $this->sendTranslation($sender, "commands.kickall.done");
        return true;
    }
}
