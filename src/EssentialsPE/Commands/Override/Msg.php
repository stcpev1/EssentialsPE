<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseOverrideCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;

class Msg extends BaseOverrideCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tell");
        $this->setPermission("essentials.msg");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $t = array_shift($args);
        if(strtolower($t) !== "console" && strtolower($t) !== "rcon"){
            $t = $this->getAPI()->getPlayer($t);
            if(!$t){
                $this->sendTranslation($sender, "error.player-not-found", $t);
                return false;
            }
        }
        $this->sendTranslation($sender, "commands.msg.syntax", $tn = ($t instanceof Player ? $t->getDisplayName() : $t), $m = implode(" ", $args));
        $m = $this->getAPI()->getTranslation("commands.msg.other-syntax", $tn, $m);
        if($t instanceof Player){
            $t->sendMessage($m);
        }else{
            $this->getLogger()->info($m);
        }
        $this->getAPI()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}