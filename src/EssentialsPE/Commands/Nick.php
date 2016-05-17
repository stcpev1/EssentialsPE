<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Nick extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "nick");
        $this->setPermission("essentials.nick.use");
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
        if((!isset($args[1]) && !$sender instanceof Player) || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $nick = ($n = strtolower($alias[0])) === "off" || $n === "remove" || $n === "restore" || (bool) $n === false ? false : $args[0];
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("essentials.nick.other")){
                $this->sendTranslation($sender, "commands.nick.other-permission");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $this->sendTranslation($sender, "error.player-not-found", $args[1]);
                return false;
            }
        }
        if(!$nick){
            $this->getAPI()->removeNick($player);
        }elseif(!$sender->hasPermission("essentials.colorchat")){
            $this->sendTranslation($sender, "error.color-codes-permission");
            return false;
        }elseif(!$this->getAPI()->setNick($player, $nick)){
            $this->sendTranslation($sender, "commands.nick.cancelled");
            return false;
        }
        $this->sendTranslation($player, "commands.nick.self-" . (!$nick ? "restore" : "change"), $nick);
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.nick.other-change", $player->getName(), $player->getDisplayName());
        }
        return true;
    }
}
