<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TPHere extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tphere");
        $this->setPermission("essentials.tphere");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $this->sendTranslation($sender, "error.player-not-found", $args[0]);
            return false;
        }
        $this->sendTranslation($sender, "commands.tphere.confirmation", $player->getDisplayName());
        $this->sendTranslation($player, "commands.tphere.other-confirmation...", $sender->getDisplayName());
        $player->teleport($sender);
        return true;
    }
} 