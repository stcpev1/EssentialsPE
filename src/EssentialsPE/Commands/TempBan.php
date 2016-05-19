<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TempBan extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tempban");
        $this->setPermission("essentials.tempban");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $name = array_shift($args);
        if(!($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $this->sendTranslation($sender, "commands.tempban.invalid-time");
            return false;
        }
        /** @var \DateTime $date */
        $date = $info[0];
        $reason = (trim($info[1]) !== "" ? "\n" . $this->getAPI()->getTranslation("commands.tempban.reason", $info[1]) : "");
        $day = $date->format("l, F j, Y");
        $time = $date->format("h:ia");
        if(($player = $this->getAPI()->getPlayer($name)) instanceof Player){
            if($player->hasPermission("essentials.ban.exempt")){
                $this->sendTranslation($sender, "commands.tempban.ban-exempt", $player->getDisplayName());
                return false;
            }else{
                $player->kick($this->getAPI()->getTranslation("commands.tempban.banned-until", $day, $time) . $reason);
            }
        }
        $this->getAPI()->getServer()->getNameBans()->addBan(($name = $player instanceof Player ? $player->getName() : $name), (trim($reason) !== "" ? $reason : null), $date, "EssentialsPE");
        $this->broadcastCommandMessage($sender, $this->getAPI()->getTranslation("commands.tempban.broadcast", $name, $day, $time) . $reason);
        return true;
    }
}
