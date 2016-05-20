<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TPDeny extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpdeny");
        $this->setPermission("essentials.tpdeny");
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($request = $this->getAPI()->hasARequest($sender))){
            $this->sendTranslation($sender, "commands.tpa.no-requests");
            return false;
        }
        switch(count($args)){
            case 0:
                if(!($player = $this->getAPI()->getPlayer(($name = $this->getAPI()->getLatestRequest($sender))))){
                    $this->sendTranslation($sender, "commands.tpa.not-available", $name);
                    return false;
                }
                break;
            case 1:
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $this->sendTranslation($sender, "error.player-not-found", $args[0]);
                    return false;
                }
                if(!($request = $this->getAPI()->hasARequestFrom($sender, $player))){
                    $this->sendTranslation($sender, "commands.tpa.no-requests-from", $player->getDisplayName());
                    return false;
                }
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        $this->sendTranslation($player, "commands.tpdeny.other-confirmation", $sender->getDisplayName());
        $this->sendTranslation($sender, "commands.tpdeny.confirmation", $player->getDisplayName());
        $this->getAPI()->removeTPRequest($player, $sender);
        return true;
    }
} 