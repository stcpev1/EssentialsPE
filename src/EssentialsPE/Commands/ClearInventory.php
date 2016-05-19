<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class ClearInventory extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "clearinventory");
        $this->setPermission("essentials.clearinventory.use");
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
        if((!isset($args[0]) &&  !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.clearinventory.other")){
                $this->sendTranslation($sender, "commands.clearinventory.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[0]);
                return false;
            }
        }
        if(($gm = $player->getGamemode()) === Player::CREATIVE || $gm === Player::ADVENTURE){
            $gm = $this->getAPI()->getServer()->getGamemodeString($gm);
            if($player === $sender){
                $this->sendTranslation($sender, "error.gamemode-error", $gm);
            }else{
                $this->sendTranslation($sender, "error.other-gamemode-error", $player->getDisplayName(), $gm);
            }
            return false;
        }
        $player->getInventory()->clearAll();
        $this->sendTranslation($player, "commands.clearinventory.confirmation");
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.clearinventory.other-confirmation");
        }
        return true;
    }
}
