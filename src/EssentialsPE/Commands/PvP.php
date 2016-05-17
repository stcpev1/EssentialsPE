<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class PvP extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "pvp");
        $this->setPermission("essentials.pvp");
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
        if(!$sender instanceof Player || count($args) != 1 || !((($s = strtolower($args[0])) === "on" || (bool) $s || $s === "enable") || ($s === "off" || !((bool) $s)) || $s === "disable")){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $this->getAPI()->setPvP($sender, $s);
        $this->sendTranslation($sender, "commands.pvp." . ($s ? "enabled" : "disabled"));
        return true;
    }
}
