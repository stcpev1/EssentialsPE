<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseOverrideCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Gamemode extends BaseOverrideCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "gamemode");
        $this->setPermission("essentials.gamemode");
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
        if(strtolower($alias) !== "gamemode" && strtolower($alias) !== "gm"){
            if(isset($args[0])){
                $args[1] = $args[0];
                unset($args[0]);
            }
            switch(strtolower($alias)){
                case "survival":
                case "gms":
                    $args[0] = Player::SURVIVAL;
                    break;
                case "creative":
                case "gmc":
                    $args[0] = Player::CREATIVE;
                    break;
                case "adventure":
                case "gma":
                    $args[0] = Player::ADVENTURE;
                    break;
                case "spectator":
                case "viewer":
                case "gmt":
                    $args[0] = Player::SPECTATOR;
                    break;
                default:
                    return false;
                    break;
            }
        }
        if(count($args) < 1 || (!($player = $sender) instanceof Player && !isset($args[1]))){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(isset($args[1]) && !($player = $this->getAPI()->getPlayer($args[1]))){
            $this->sendTranslation($sender, "error.player-not-found", $args[1]);
            return false;
        }

        /**
         * The following switch is applied when the user execute:
         * /gamemode <MODE>
         */
        if(is_numeric($args[0])){
            switch($args[0]){
                case Player::SURVIVAL:
                case Player::CREATIVE:
                case Player::ADVENTURE:
                case Player::SPECTATOR:
                    $gm = $args[0];
                    break;
                default:
                    $this->sendTranslation($sender, "commands.gamemode.invalid-mode");
                    return false;
                    break;
            }
        }else{
            switch(strtolower($args[0])){
                case "survival":
                case "s":
                    $gm = Player::SURVIVAL;
                    break;
                case "creative":
                case "c":
                    $gm = Player::CREATIVE;
                    break;
                case "adventure":
                case "a":
                    $gm = Player::ADVENTURE;
                    break;
                case "spectator":
                case "viewer":
                case "view":
                case "v":
                case "t":
                    $gm = Player::SPECTATOR;
                    break;
                default:
                    $this->sendTranslation($sender, "commands.gamemode.invalid-mode");
                    return false;
                    break;
            }
        }
        $gmString = $this->getAPI()->getServer()->getGamemodeString($gm);
        if($player->getGamemode() === $gm){
            if($player === $sender){
                $this->sendTranslation($sender, "commands.gamemode.already-in-mode", $gmString);
            }else{
                $this->sendTranslation($sender, "commands.gamemode.other-already-in-mode", $gmString);
            }
            return false;
        }
        $player->setGamemode($gm);
        $this->sendTranslation($player, "commands.gamemode.confirmation", $gmString);
        if($player !== $sender){
            $this->sendTranslation($sender, "commands.gamemode.other-confirmation", $player->getDisplayName(), $gmString);
        }
        return true;
    }

    public function sendUsage(CommandSender $sender, string $alias){
        $usage = str_replace($this->getName(), $alias, $this->getAPI()->getTranslation("essentials.error.command-usage", ($sender instanceof Player ? $this->getUsage() : $this->getConsoleUsage())));
        if(strtolower($alias) !== "gamemode" && strtolower($alias) !== "gm"){
            $usage = str_replace("<mode> ", "", $usage);
        }
        $sender->sendMessage($usage);
    }
} 
