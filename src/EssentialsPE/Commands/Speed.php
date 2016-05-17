<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\Player;

class Speed extends BaseCommand{
    public function __construct(BaseAPI $api){
        parent::__construct($api, "speed");
        $this->setPermission("essentials.speed");
    }

    public function execute(CommandSender $sender, $alias, array $args): bool{
        if($this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player || count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!is_numeric($args[0])){
            $this->sendTranslation($sender, "commands.speed.invalid");
            return false;
        }
        $player = $sender;
        if(isset($args[1]) && !($player = $this->getAPI()->getPlayer($args[1]))){
            $this->sendTranslation($sender, "error.player-not-found", $args[1]);
            return false;
        }
        if((int) $args[0] === 0){
            $player->removeEffect(Effect::SPEED);
        }else{
            $effect = Effect::getEffect(Effect::SPEED);
            $effect->setAmplifier($args[0]);
            $effect->setDuration(PHP_INT_MAX);
            $player->addEffect($effect);
        }
        $this->sendTranslation($sender, "commands.speed.applied", $args[0]);
        return true;
    }
}