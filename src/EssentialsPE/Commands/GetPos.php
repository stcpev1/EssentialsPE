<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class GetPos extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "getpos");
        $this->setPermission("essentials.getpos.use");
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
            if(!$sender->hasPermission("essentials.getpos.other")){
                $this->sendTranslation($sender, "commands.getpos.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[0]);
                return false;
            }
        }
        $this->sendTranslation($sender, "commands.getpos." . ($player === $sender ? "self" : "other") . "-location", $player->getLevel()->getName(), $player->getX(), $player->getY(), $player->getZ(), $player->getDisplayName());
        return true;
    }
}
