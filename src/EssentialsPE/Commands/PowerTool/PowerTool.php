<?php
namespace EssentialsPE\Commands\PowerTool;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class PowerTool extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "powertool");
        $this->setPermission("essentials.powertool.use");
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
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $this->sendTranslation($sender, "commands.powertool.empty-hand");
            return false;
        }

        if(count($args) === 0){
            if(!$this->getAPI()->getPowerToolItemCommand($sender, $item) && !$this->getAPI()->getPowerToolItemCommands($sender, $item) && !$this->getAPI()->getPowerToolItemChatMacro($sender, $item)){
                $this->sendUsage($sender, $alias);
                return false;
            }
            $this->getAPI()->disablePowerToolItem($sender, $item);
            $this->onPowerToolDisable($sender, $item);
        }else{
            if($args[0] === "pt" || $args[0] === "ptt" || $args[0] === "powertool" || $args[0] === "powertooltoggle"){
                $this->sendTranslation($sender, "commands.powertool.invalid-command", $args[0]);
                return false;
            }
            $command = implode(" ", $args);
            if(stripos($command, "c:") !== false){ //Create a chat macro
                $c = substr($command, 2);
                $this->getAPI()->setPowerToolItemChatMacro($sender, $item, $c);
                $this->sendTranslation($sender, "commands.powertool.chat-macro-assigned");
            }elseif(stripos($command, "a:") !== false){
                if(!$sender->hasPermission("essentials.powertool.append")){
                    $this->sendTranslation($sender, "commands.powertool.append-permission");
                    return false;
                }
                $commands = substr($command, 2);
                $commands = explode(";", $commands);
                $this->getAPI()->setPowerToolItemCommands($sender, $item, $commands);
                $this->sendTranslation($sender, "commands.powertool.commands-assigned");
            }elseif(stripos($command, "r:") !== false){
                if(!$sender->hasPermission("essentials.powertool.append")){
                    $this->sendTranslation($sender, "commands.powertool.append-permission");
                    return false;
                }
                $command = substr($command, 2);
                $this->getAPI()->removePowerToolItemCommand($sender, $item, $command);
                $this->sendTranslation($sender, "commands.powertool.command-removed");
            }elseif(count($args) === 1 && (($a = strtolower($args[0])) === "l" || $a === "d")){
                switch($a){
                    case "l":
                        $commands = false;
                        if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
                            $commands = $this->getAPI()->getPowerToolItemCommand($sender, $item);
                        }
                        if($commands !== false){
                            $list = $commands;
                            if(!is_array($list)){
                                $list = [$list];
                            }
                            $commands = "";
                            foreach($list as $cmd){
                                $commands .= $this->getAPI()->getTranslation("commands.powertool.list-command-syntax", $cmd);
                            }
                        }
                        $chat_macro = $this->getAPI()->getPowerToolItemChatMacro($sender, $item);
                        $this->sendTranslation($sender, "commands.powertool.list-syntax", 
                            !$commands ? "commands.powertool.no-commands" : $commands,
                            !$chat_macro ? "commands.powertool.no-chat-macro" : ["commands.powertool.list-chat-macro-syntax", $chat_macro]
                        );
                        return true;
                        break;
                    case "d":
                        if(!$this->getAPI()->getPowerToolItemCommand($sender, $item)){
                            $this->sendUsage($sender, $alias);
                            return false;
                        }
                        $this->getAPI()->disablePowerToolItem($sender, $item);
                        $this->onPowerToolDisable($sender, $item);
                        return true;
                        break;
                }
            }else{
                $this->getAPI()->setPowerToolItemCommand($sender, $item, $command);
                $this->sendTranslation($sender, "command.powertool.commands-assigned");
            }
        }
        return true;
    }
    
    private function onPowerToolDisable(Player $sender, Item $item){
        if($this->getAPI()->getPowerToolItemCommand($sender, $item) !== false){
            $this->sendTranslation($sender, "commands.powertool.command-removed");
        }elseif($this->getAPI()->getPowerToolItemCommands($sender, $item) !== false){
            $this->sendTranslation($sender, "commands.powertool.commands-removed");
        }
        if($this->getAPI()->getPowerToolItemChatMacro($sender, $item) !== false){
            $this->sendTranslation($sender, "commands.powertool.chat-macro-removed");
        }
    }
} 
