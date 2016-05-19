<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Warp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "warp");
        $this->setPermission("essentials.warp.use");
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
        if(count($args) === 0){
            if(($list = $this->getAPI()->warpList(false)) === false){
                $this->sendTranslation($sender, "commands.warp.no-warps-available");
                return false;
            }
            $this->sendTranslation($sender, "commands.warp.list-warps", $list);
            return true;
        }
        if(!($warp = $this->getAPI()->getWarp($args[0]))){
            $this->sendTranslation($sender, "commands.warp.not-exists", $args[0]);
            return false;
        }
        if(!isset($args[1]) && !$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.warp.other")){
                $this->sendTranslation($sender, "commands.warp.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[1]);
                return false;
            }
        }
        if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps.$args[0]")){
            $this->sendTranslation($sender, "commands.warp.need-permission", $args[0]);
            return false;
        }
        $player->teleport($warp);
        $this->sendTranslation($player, "commands.warp.self-confirmation", $warp->getName());
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.warp.other-confirmation", $player->getDisplayName(), $warp->getName());
        }
        return true;
    }
} 
