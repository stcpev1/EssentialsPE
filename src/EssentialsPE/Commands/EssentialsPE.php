<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class EssentialsPE extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "essentialspe");
        $this->setPermission("essentials.essentials");
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
        if(count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!isset($args[0])){
            $args[0] = "version";
        }
        switch(strtolower($args[0])){
            case "update":
                if(!$sender->hasPermission("essentials.update.use")){
                    $sender->sendMessage($this->getPermissionMessage());
                    return false;
                }
                $a = strtolower($args[1]);
                if(!(isset($args[1]) && ($a === "check" || $a === "install"))){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(!$this->getAPI()->fetchEssentialsPEUpdate($a === "install")){
                    $this->sendTranslation($sender, "general.updater.working");
                }
                break;
            case "version":
                $this->sendTranslation($sender, "general.version", $this->getPlugin()->getDescription()->getVersion());
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
