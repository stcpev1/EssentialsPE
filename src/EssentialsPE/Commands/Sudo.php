<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class Sudo extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sudo");
        $this->setPermission("essentials.sudo.use");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($name = array_shift($args)))){
            $this->sendMessage($sender, "general.error.player-not-found", $name);
            return false;
        }elseif($player->hasPermission("essentials.sudo.exempt")){
            $this->sendMessage($sender, "commands.sudo.exempt-sudo");
            return false;
        }

        $cmd = implode(" ", $args);
        if(substr($cmd, 0, 2) === "c:"){
            $this->getAPI()->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, $m = substr($cmd, 2)));
            if(!$ev->isCancelled()){
                $this->sendMessage($sender, "commands.sudo.sending-message", $m, $player->getDisplayName());
                $this->getAPI()->getServer()->broadcastMessage(\sprintf($ev->getFormat(), $ev->getPlayer()->getDisplayName(), $ev->getMessage()), $ev->getRecipients());
            }
        }else{
            $this->getAPI()->getServer()->dispatchCommand($player, $cmd);
            $this->sendMessage($sender, "commands.sudo.sending-command", $cmd, $player->getDisplayName());
        }
        return true;
    }
} 
