<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TPA extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpa");
        $this->setPermission("essentials.tpa");
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
        if($player->getName() === $sender->getName()){
            $this->sendTranslation($sender, "commands.tpa.self-request");
            return false;
        }
        $this->getAPI()->requestTPTo($sender, $player);
        $player->sendMessage($this->getAPI()->getTranslation("commands.tpa.tpto", $sender->getDisplayName()) . $this->getAPI()->getTranslation("commands.tpa.syntax"));
        $this->sendTranslation($sender, "commands.tpa.confirmation", $player->getDisplayName());
        return true;
    }
} 