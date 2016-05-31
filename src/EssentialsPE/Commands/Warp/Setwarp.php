<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Setwarp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setwarp");
        $this->setPermission("essentials.setwarp");
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
        if(($existed = $this->getAPI()->warpExists($args[0])) && !$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $this->sendTranslation($sender, "commands.setwarp.update-permission", $args[0]);
            return false;
        }
        if(!$this->getAPI()->setWarp($args[0], $sender->getPosition(), $sender->getYaw(), $sender->getPitch())){
            $this->sendTranslation($sender, "error.invalid-name");
            return false;
        }
        $this->sendTranslation($sender, "commands.setwarp." . ($existed ? "updated" : "created"), $args[0]);
        return true;
    }
} 