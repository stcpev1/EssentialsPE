<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class DelWarp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "delwarp");
        $this->setPermission("essentials.delwarp");
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
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->warpExists($args[0])){
            $this->sendTranslation($sender, "commands.warp.not-exists");
            return false;
        }
        if(!$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $this->sendTranslation($sender, "commands.delwarp.need-permission");
            return false;
        }
        $this->getAPI()->removeWarp($args[0]);
        $this->sendTranslation($sender, "commands.delwarp.confirmation", $args[0]);
        return true;
    }
} 