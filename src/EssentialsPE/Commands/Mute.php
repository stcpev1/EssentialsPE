<?php

namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Mute extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "mute");
        $this->setPermission("essentials.mute.use");
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
        if(!($player = $this->getAPI()->getPlayer($n = array_shift($args)))){
            $this->sendTranslation($sender, "error.player-not-found", $n);
            return false;
        }
        if($player->hasPermission("essentials.mute.exempt") && !$this->getAPI()->isMuted($player)){
            $this->sendTranslation($sender, "commands.mute.exempt", $player->getDisplayName());
            return false;
        }
        /** @var \DateTime $date */
        $date = null;
        if(!is_bool($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $date = $info[0];
        }
        $this->getAPI()->switchMute($player, $date, true);
        $this->sendTranslation($sender, "commands.mute.other-" . ($this->getAPI()->isMuted($player) ? "muted" : "unmuted"), ($date === null ?
            $this->getAPI()->getTranslation("commands.mute.mute-until", $date->format("l, F j, Y"), $date->format("h:ia"))
            : $this->getAPI()->getTranslation("commands.mute.mute-forever"))
        );
        return true;
    }
}