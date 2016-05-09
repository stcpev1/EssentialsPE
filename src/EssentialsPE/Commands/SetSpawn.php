<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetSpawn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setspawn");
        $this->setPermission("essentials.setspawn");
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
        if(!$sender instanceof Player || count($args) != 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $sender->getLevel()->setSpawnLocation($sender);
        $sender->getServer()->setDefaultLevel($sender->getLevel());
        $sender->sendMessage(TextFormat::YELLOW . "Server's spawn point changed!");
        $this->sendTranslation($sender, "commands.setspawn.confirmation");
        $this->getLogger()->info($this->getAPI()->getTranslation("commands.setspawn.console-confirmation", $sender->getLevel()->getName(), $sender->getName()));
        return true;
    }
}
