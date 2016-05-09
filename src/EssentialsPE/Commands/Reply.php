<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;

class Reply extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "reply");
        $this->setPermission("essentials.reply");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($t = $this->getAPI()->getQuickReply($sender))){
            $this->sendTranslation($sender, "commands.reply.no-target");
            return false;
        }
        $this->sendTranslation($sender, "commands.reply.self-syntax", ($t instanceof Player ? $t->getDisplayName() : $t), $m = implode(" ", $args));
        $m = $this->getAPI()->getTranslation("commands.reply.other-syntax", ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()), $m);
        if($t instanceof Player){
            $t->sendMessage($m);
        }else{
            $this->getLogger()->info($m);
        }
        $this->getAPI()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}