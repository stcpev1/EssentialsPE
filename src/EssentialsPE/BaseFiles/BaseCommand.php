<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\MainLogger;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var BaseAPI  */
    private $api;
    /** @var bool|string */
    private $consoleUsageMessage;

    /**
     * BaseCommand constructor.
     * @param BaseAPI $api
     * @param string $name
     */
    public function __construct(BaseAPI $api, string $name){
        $this->api = $api;
        $t = $this->getAPI()->getTranslation("commands." . $name);
        parent::__construct($t["name"], $t["description"], $t["usage"], $t["alias"] ?? []);
        if(is_bool($t["console-usage"])){
            $this->consoleUsageMessage = (!$t["console-usage"] ? $this->getAPI()->getTranslation("general.error.run-in-game") : parent::getUsage());
        }else{
            $this->consoleUsageMessage = $t["console-usage"];
        }
        $this->setPermissionMessage($this->getAPI()->getTranslation("error.need-permission"));
    }

    /**
     * @return Loader
     */
    public final function getPlugin(): Loader{
        return $this->getAPI()->getEssentialsPEPlugin();
    }

    /**
     * @return BaseAPI
     */
    public final function getAPI(): BaseAPI{
        return $this->api;
    }

    /**
     * @return bool|string
     */
    public function getConsoleUsage(){
        return $this->consoleUsageMessage;
    }

    /**
     * @return MainLogger
     */
    public function getLogger(): MainLogger{
        return $this->getAPI()->getServer()->getLogger();
    }

    /**
     * Function to give different type of usages, switching from "Console" and "Player" executors of a command.
     * This function can be overridden to fit any command needs...
     *
     * @param CommandSender $sender
     * @param string $alias
     */
    public function sendUsage(CommandSender $sender, string $alias){
        $sender->sendMessage(str_replace($this->getName(), $alias, $this->getAPI()->getTranslation("essentials.error.command-usage", ($sender instanceof Player ? $this->getUsage() : $this->getConsoleUsage()))));
    }

    /**
     * @param CommandSender $sender
     * @param string $message
     * @param array ...$args
     */
    public function sendTranslation(CommandSender $sender, string $message, ...$args){
        $sender->sendMessage($this->getAPI()->getTranslation($message, ...$args));
    }
}
