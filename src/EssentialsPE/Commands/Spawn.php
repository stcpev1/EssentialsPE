<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;
use pocketmine\Player;

class Spawn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "spawn");
        $this->setPermission("essentials.spawn.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.spawn.other")){
                $this->sendTranslation($sender, "commands.spawn.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $this->sendTranslation($sender, "general.error.player-not-found", $args[0]);
                return false;
            }
        }
        $this->sendTranslation($player, "commands.spawn.teleporting");
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.spawn.other-teleporting", $player->getDisplayName());
        }
        $player->teleport(Location::fromObject($this->getAPI()->getServer()->getDefaultLevel()->getSpawnLocation(), $this->getAPI()->getServer()->getDefaultLevel()));
        return true;
    }
} 
