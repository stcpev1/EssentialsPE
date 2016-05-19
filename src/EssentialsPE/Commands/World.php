<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class World extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "world");
        $this->setPermission("essentials.world");
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
        }
        if(!$sender->hasPermission("essentials.worlds.*") && !$sender->hasPermission("essentials.worlds." . strtolower($args[0]))){
            $this->sendTranslation($sender, "commands.world.need-permission", $args[0]);
            return false;
        }
        if(!$this->getAPI()->getServer()->isLevelGenerated($args[0])){
            $this->sendTranslation($sender, "commands.world.not-exists", $args[0]);
            return false;
        }elseif(!$this->getAPI()->getServer()->isLevelLoaded($args[0])){
            $this->sendTranslation($sender, "commands.world.loading-world", $args[0]);
            if(!$this->getAPI()->getServer()->loadLevel($args[0])){
                $this->sendTranslation($sender, "commands.world.load-error", $args[0]);
                return false;
            }
        }
        $this->sendTranslation($sender, "commands.world.teleport", $args[0]);
        $sender->teleport($this->getAPI()->getServer()->getLevelByName($args[0])->getSpawnLocation(), 0, 0);
        return true;
    }
} 
