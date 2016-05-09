<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Unlimited extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "unlimited");
        $this->setPermission("essentials.unlimited.use");
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
            if(!$sender->hasPermission("essentials.unlimited.other")){
                $this->sendMessage($sender, "commands.unlimited.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $this->sendMessage($sender, "general.error.player-not-found", $args[0]);
                return false;
            }
        }
        if(($gm = $player->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $gm = $this->getAPI()->getServer()->getGamemodeString($gm);
            if($player === $sender){
                $this->sendMessage($sender, "commands.unlimited.gamemode-error", $gm);
            }else{
                $this->sendMessage($sender, "commands.unlimited.other-gamemode-error", $player->getDisplayName(), $gm);
            }
            return false;
        }
        $this->getAPI()->switchUnlimited($player);
        $player->sendMessage(TextFormat::GREEN . "Unlimited placing of blocks ");
        $this->sendMessage($player, "commands.unlimited.self-" . ($s = $this->getAPI()->isUnlimitedEnabled($player) ? "enabled" : "disabled"));
        if($player !== $sender){
            $this->sendMessage($sender, "commands.unlimited.other-" . $s, $player->getName());
        }
        return true;
    }
} 