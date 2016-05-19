<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Back extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "back");
        $this->setPermission("essentials.back.use");
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
        if(!($pos = $this->getAPI()->getLastPlayerPosition($sender))){
            $this->sendTranslation($sender, "commands.back.not-available");
            return false;
        }
        $this->sendTranslation($sender, "general.teleport-confirmation");
        $sender->teleport($pos);
        return true;
    }
} 