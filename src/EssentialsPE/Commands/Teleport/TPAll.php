<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TPAll extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpall");
        $this->setPermission("essentials.tpall");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $this->sendTranslation($sender, "error.player-not-found", $args[0]);
            return false;
        }
        $this->sendTranslation($player, "commands.tpall.confirmation");
        $c = $this->getAPI()->getTranslation("commands.tpall.other-confirmation", $player->getDisplayName());
        foreach($this->getAPI()->getServer()->getOnlinePlayers() as $p){
            if($p !== $player){
                $p->sendMessage($c);
                $p->teleport($player);
            }
        }
        return true;
    }
} 