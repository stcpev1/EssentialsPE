<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class More extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "more");
        $this->setPermission("essentials.more");
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
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $this->sendTranslation($sender, "error.gamemode-error", $this->getAPI()->getServer()->getGamemodeString($gm));
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $this->sendTranslation($sender, "commands.more.air-stack");
            return false;
        }
        $item->setCount($sender->hasPermission("essentials.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize());
        $this->sendTranslation($sender, "commands.more.stack-filled", $item->getCount());
        return true;
    }
}
