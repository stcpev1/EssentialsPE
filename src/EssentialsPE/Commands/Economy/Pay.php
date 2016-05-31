<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Pay extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "pay");
        $this->setPermission("essentials.pay");
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
        if(!$sender instanceof Player || count($args) !== 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $this->sendTranslation($sender, "error.player-not-found");
            return false;
        }
        if(($args[1] = (int) $args[1]) < 1){
            $this->sendTranslation($sender, "error.invalid-amount");
            return false;
        }
        $balance = $this->getAPI()->getPlayerBalance($sender);
        $newBalance = $balance - $args[1];
        if($balance < $args[1] || $newBalance < $this->getAPI()->getMinBalance() || ($newBalance < 0 && !$player->hasPermission("essentials.eco.loan"))){
            $this->sendTranslation($sender, "commands.pay.profit");
            return false;
        }
        $this->sendTranslation($sender, "commands.pay.confirmation", $this->getAPI()->getCurrencySymbol() . $args[1], $player->getDisplayName());
        $this->getAPI()->setPlayerBalance($sender, $newBalance); //Take out from the payer balance.
        $this->getAPI()->addToPlayerBalance($player, $args[1]); //Pay to the other player
        return true;
    }
}