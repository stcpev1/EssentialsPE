<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\level\particle\HeartParticle;
use pocketmine\Player;

class Heal extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "heal");
        $this->setPermission("essentials.heal.use");
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
        $player->heal($player->getMaxHealth(), new EntityRegainHealthEvent($player, $player->getMaxHealth() - $player->getHealth(), EntityRegainHealthEvent::CAUSE_CUSTOM));
        $player->getLevel()->addParticle(new HeartParticle($player->add(0, 2), 4)); // TODO: Configuration for minimized particles
        $this->sendTranslation($sender, "commands.heal.confirmation");
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.heal.other-confirmation", $player->getDisplayName());
        }
        return true;
    }
}
