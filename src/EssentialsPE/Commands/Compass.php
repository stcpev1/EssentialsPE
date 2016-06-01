<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Compass extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "compass");
        $this->setPermission("essentials.compass");
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
        $direction= ["south", "west", "north", "east"];
        $this->sendTranslation($sender,
            "commands.compass." .
                (isset($direction[$sender->getDirection()]) ? "direction"  : "unknown-direction"),
            ["commands.compass." . $direction[$sender->getDirection()]]
        );
        return true;
    }
}
