<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Antioch extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "antioch");
        $this->setPermission("essentials.antioch");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->antioch($sender)){
            $this->sendTranslation($sender, "commands.antioch.no-spot");
            return false;
        }
        $this->sendTranslation($sender, "commands.antioch.confirmation");
        return true;
    }
}