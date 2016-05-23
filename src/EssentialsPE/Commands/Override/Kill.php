<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseOverrideCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;

class Kill extends BaseOverrideCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "kill");
        $this->setPermission("essentials.kill.use");
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
        if(!$sender instanceof Player && count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.kill.other")){
                $this->sendTranslation($sender, "commands.kill.other-permission");
                return false;
            }
            if(!($player = $this->getAPI()->getPlayer($args[0])) instanceof Player){
                $this->sendTranslation($sender, "error.player-not-found", $args[0]);
                return false;
            }
        }
        if($this->getAPI()->isGod($player)){
            $this->sendTranslation($sender, "commands.kill.exempt", $player->getDisplayName());
            return false;
        }
        $this->getAPI()->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, ($player->getHealth())));
        if($ev->isCancelled()){
            return true;
        }
        $player->setLastDamageCause($ev);
        $player->setHealth(0);
        return true;
    }
} 