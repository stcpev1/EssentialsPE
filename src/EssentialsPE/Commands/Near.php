<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Near extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "near");
        $this->setPermission("essentials.near.use");
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
        if((!isset($args[0]) || !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.near.other")){
                $this->sendTranslation($sender, "commands.near.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[0]);
                return false;
            }
        }
        $who = $player === $sender ? "you" : $player->getDisplayName();
        if(count($near = $this->getAPI()->getNearPlayers($player)) < 1){
            $this->sendTranslation($sender, "commands.near." . ($who = ($player === $sender ? "self" : "other")) . "-nobody", $player->getDisplayName());
        }else{
            $m = array_shift($near);
            foreach($near as $p){
                $m .= $this->getAPI()->getTranslation("commands.near.list-syntax", $p->getDisplayName());
            }
            $this->sendTranslation($sender, "command.near." . $who . "-list", count($near), $m, $player->getDisplayName());
        }
        return true;
    }
} 