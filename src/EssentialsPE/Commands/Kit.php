<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Kit extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kit");
        $this->setPermission("essentials.kit.use");
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
        if(count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(count($args) === 0){
            if(($list = $this->getAPI()->kitList(false)) === false){
                $this->sendTranslation($sender, "commands.kit.not-kits-available");
                return false;
            }
            $this->sendTranslation($sender, "commands.kit.list-kits", $list);
            return true;
        }elseif(!isset($args[1]) && !$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!($kit = $this->getAPI()->getKit($args[0]))){
            $this->sendTranslation($sender, "commands.kit.not-exists");
            return false;
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.kit.other")){
                $this->sendTranslation($sender, "commands.kit.need-permission", $kit->getName());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[1]);
                return false;
            }
        }
        if(!$sender->hasPermission("essentials.kits.*") && !$sender->hasPermission("essentials.kits." . strtolower($kit->getName()))){
            $this->sendTranslation($sender, "commands.kit.need-permission", $kit->getName());
            return false;
        }
        $this->sendTranslation($sender, "commands.kit.get", $kit->getName());
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.kit.give", $player->getDisplayName(), $kit->getName());
        }
        return true;
    }
}