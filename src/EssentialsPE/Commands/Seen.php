<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Seen extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "seen");
        $this->setPermission("essentials.seen");
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
        if(($player = $this->getAPI()->getOfflinePlayer($args[0])) instanceof Player){
            $this->sendTranslation($sender, "commands.seen.is-online", $player->getDisplayName());
            return true;
        }
        if(!is_numeric($player->getLastPlayed())){
            $this->sendTranslation($sender, "commands.seen.unknown-player", $args[0]);
            return false;
        }
        /**
         * a = am/pm
         * i = Minutes
         * h = Hour (12 hours format with leading zeros)
         * l = Day name
         * j = Day number (1 - 30/31)
         * F = Month name
         * Y = Year in 4 digits (Ex: 1999)
         */
        $this->sendTranslation($sender, "commands.seen.last-seen", $player->getDisplayName(), date("l, F j, Y", ($t = $player->getLastPlayed() / 1000)), date("h:ia", $t));
        return true;
    }
}
