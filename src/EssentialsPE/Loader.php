<?php
namespace EssentialsPE;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Commands\AFK;
use EssentialsPE\Commands\Antioch;
use EssentialsPE\Commands\Back;
use EssentialsPE\Commands\BreakCommand;
use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\Compass;
use EssentialsPE\Commands\Condense;
use EssentialsPE\Commands\Depth;
#use EssentialsPE\Commands\Economy\Balance;
#use EssentialsPE\Commands\Economy\Eco;
#use EssentialsPE\Commands\Economy\Pay;
#use EssentialsPE\Commands\Economy\Sell;
#use EssentialsPE\Commands\Economy\SetWorth;
#use EssentialsPE\Commands\Economy\Worth;
use EssentialsPE\Commands\EssentialsPE;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\Fly;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\God;
use EssentialsPE\Commands\Hat;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\Home\DelHome;
use EssentialsPE\Commands\Home\Home;
use EssentialsPE\Commands\Home\SetHome;
use EssentialsPE\Commands\ItemCommand;
use EssentialsPE\Commands\ItemDB;
use EssentialsPE\Commands\Jump;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\Kit;
use EssentialsPE\Commands\Lightning;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Near;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\Nuke;
use EssentialsPE\Commands\Override\Gamemode;
use EssentialsPE\Commands\Override\Kill;
use EssentialsPE\Commands\Override\Msg;
use EssentialsPE\Commands\Ping;
use EssentialsPE\Commands\PowerTool\PowerTool;
use EssentialsPE\Commands\PowerTool\PowerToolToggle;
use EssentialsPE\Commands\PTime;
use EssentialsPE\Commands\PvP;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Reply;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\Spawn;
use EssentialsPE\Commands\Sudo;
use EssentialsPE\Commands\Suicide;
use EssentialsPE\Commands\Teleport\TPA;
use EssentialsPE\Commands\Teleport\TPAccept;
use EssentialsPE\Commands\Teleport\TPAHere;
use EssentialsPE\Commands\Teleport\TPAll;
use EssentialsPE\Commands\Teleport\TPDeny;
use EssentialsPE\Commands\Teleport\TPHere;
use EssentialsPE\Commands\TempBan;
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Unlimited;
use EssentialsPE\Commands\Vanish;
use EssentialsPE\Commands\Warp\DelWarp;
use EssentialsPE\Commands\Warp\Setwarp;
use EssentialsPE\Commands\Warp\Warp;
use EssentialsPE\Commands\World;
use EssentialsPE\EventHandlers\OtherEvents;
use EssentialsPE\EventHandlers\PlayerEvents;
use EssentialsPE\EventHandlers\SignEvents;
use EssentialsPE\Events\CreateAPIEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Loader extends PluginBase{
    /** @var BaseAPI */
    private $api;

    public function onEnable(){
        // Before anything else...
        $this->checkConfig();

        // Custom API Setup :3
        $this->getServer()->getPluginManager()->callEvent($ev = new CreateAPIEvent($this, BaseAPI::class));
        $class = $ev->getClass();
        $this->api = new $class($this);

        // Other startup code...
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        $this->registerEvents();
        $this->registerCommands();
        if(count($p = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->createSession($p);
        }
        if($this->getAPI()->isUpdaterEnabled()){
            $this->getAPI()->fetchEssentialsPEUpdate(false);
        }
        $this->getAPI()->scheduleAutoAFKSetter();
    }

    public function onDisable(){
        if(count($l = $this->getServer()->getOnlinePlayers()) > 0){
            $this->getAPI()->removeSession($l);
        }
        $this->getAPI()->__destruct();
    }

    /**
     * Function to register all the Event Handlers that EssentialsPE provide
     */
    public function registerEvents(){
        $this->getServer()->getPluginManager()->registerEvents(new OtherEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvents($this->getAPI()), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SignEvents($this->getAPI()), $this);
    }

    /**
     * Function to register all EssentialsPE's commands...
     * And to override some default ones
     */
    private function registerCommands(){
        $commands = [
            new AFK($this->getAPI()),
            new Antioch($this->getAPI()),
            new Back($this->getAPI()),
            //new BigTreeCommand($this->getAPI()), TODO
            new BreakCommand($this->getAPI()),
            new Broadcast($this->getAPI()),
            new Burn($this->getAPI()),
            new ClearInventory($this->getAPI()),
            new Compass($this->getAPI()),
            new Condense($this->getAPI()),
            new Depth($this->getAPI()),
            new EssentialsPE($this->getAPI()),
            new Extinguish($this->getAPI()),
            new Fly($this->getAPI()),
            new GetPos($this->getAPI()),
            new God($this->getAPI()),
            new Hat($this->getAPI()), //TODO: Implement when MCPE implements "Block-Hat rendering"
            new Heal($this->getAPI()),
            new ItemCommand($this->getAPI()),
            new ItemDB($this->getAPI()),
            new Jump($this->getAPI()),
            new KickAll($this->getAPI()),
            new Kit($this->getAPI()),
            new Lightning($this->getAPI()),
            new More($this->getAPI()),
            new Mute($this->getAPI()),
            new Near($this->getAPI()),
            new Nick($this->getAPI()),
            new Nuke($this->getAPI()),
            new Ping($this->getAPI()),
            new PTime($this->getAPI()),
            new PvP($this->getAPI()),
            new RealName($this->getAPI()),
            new Repair($this->getAPI()),
            new Seen($this->getAPI()),
            new SetSpawn($this->getAPI()),
            new Spawn($this->getAPI()),
            //new Speed($this->getAPI()), TODO
            new Sudo($this->getAPI()),
            new Suicide($this->getAPI()),
            new TempBan($this->getAPI()),
            new Top($this->getAPI()),
            //new TreeCommand($this->getAPI()), TODO
            new Unlimited($this->getAPI()),
            new Vanish($this->getAPI()),
            //new Whois($this->getAPI()), TODO
            new World($this->getAPI()),

            //Economy
            //new Balance($this->getAPI()),
            //new Eco($this->getAPI()),
            //new Pay($this->getAPI()),
            //new Sell($this->getAPI()),
            //new SetWorth($this->getAPI()),
            //new Worth($this->getAPI()),

            //Home
            new DelHome($this->getAPI()),
            new Home($this->getAPI()),
            new SetHome($this->getAPI()),

            // Messages
            new Msg($this->getAPI()),
            new Reply($this->getAPI()),

            //PowerTool
            new PowerTool($this->getAPI()),
            new PowerToolToggle($this->getAPI()),

            //Teleport
            new TPA($this->getAPI()),
            new TPAccept($this->getAPI()),
            new TPAHere($this->getAPI()),
            new TPAll($this->getAPI()),
            new TPDeny($this->getAPI()),
            new TPHere($this->getAPI()),

            //Warp
            new DelWarp($this->getAPI()),
            new Setwarp($this->getAPI()),
            new Warp($this->getAPI()),

            //Override
            new Gamemode($this->getAPI()),
            new Kill($this->getAPI())
        ];
        $register = [];
        $cfg = $this->getConfig()->get("disabled-commands") ?? [];
        foreach($commands as $k => $cmd){
            /** @var BaseCommand $cmd */
            $register[$cmd->getName()] = $cmd;
            $alias = $cmd->getAliases();
            $alias[] = $cmd->getName();
            foreach($alias as $a){
                if(isset($cfg[$a])){
                    unset($register[$cmd->getName()]);
                    break;
                }
            }
        }
        unset($commands);
        $this->getServer()->getCommandMap()->registerAll("EssentialsPE", $register);
    }

    public function checkConfig(){
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        if(!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveDefaultConfig();
        }
        //$this->saveResource("Economy.yml");
        $this->saveResource("Kits.yml");
        $this->saveResource("Warps.yml");
        $cfg = $this->getConfig();

        if(\Phar::running(true) !== ""){
            $path = \Phar::running(true) . DIRECTORY_SEPARATOR;
        }else{
            $path = $this->getServer()->getPluginPath() . DIRECTORY_SEPARATOR . "EssentialsPE" . DIRECTORY_SEPARATOR;
        }
        $default = new Config($path . "resources" . DIRECTORY_SEPARATOR . "config.yml");

        if($cfg->get("version") !== $default->get("version")){
            $this->getLogger()->debug($this->getAPI()->getTranslation("general  .error.invalidconfig"));
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config.yml.old");
            $this->saveDefaultConfig();
            $cfg = $this->getConfig();
        }

        foreach($default->getAll() as $k => $v){
            if(!$cfg->exists($k)){
                $cfg->set($k, $v);
            }
        }
    }

    /**
     * @return BaseAPI
     */
    public function getAPI(): BaseAPI{
        return $this->api;
    }
}
