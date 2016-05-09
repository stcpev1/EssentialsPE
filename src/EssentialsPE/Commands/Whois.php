<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Whois extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "whois");
        $this->setPermission("essentials.whois");
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
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $this->sendTranslation($sender, "general.error.player-not-found", $args[0]);
            return false;
        }
        $data = $this->getAPI()->getPlayerInformation($player);
        if(!$sender->hasPermission("essentials.geoip.show") || $player->hasPermission("essentials.geoip.hide")){
            unset($data["location"]);
        }
        $m = $this->getAPI()->getTranslation("commands.whois.i-information");
        foreach($data as $k => $v){
            $m .= " * " . ucfirst($k) . ": $v";
        }
        $sender->sendMessage($m);
        return true;
    }
}