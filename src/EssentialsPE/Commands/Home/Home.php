<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Home extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "home");
        $this->setPermission("essentials.home.use");
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
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) === 0){
            if(($list = $this->getAPI()->homesList($sender, false)) === false){
                $this->sendTranslation($sender, "commands.home.empty");
                return false;
            }
            $this->sendTranslation($sender, "commands.home.list", $list);
            return true;
        }
        if(!($home = $this->getAPI()->getHome($sender, $args[0]))){
            $this->sendTranslation($sender, "commands.home.exists", $args[0]);
            return false;
        }
        $sender->teleport($home);
        $this->sendTranslation($sender, "commands.home.confirmation", $home->getName());
        return true;
    }
} 