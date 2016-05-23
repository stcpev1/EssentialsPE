<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;

abstract class BaseOverrideCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     * @param string $name
     */
    public function __construct(BaseAPI $api, $name){
        parent::__construct($api, $name);
        // Special part :D
        $commandMap = $api->getServer()->getCommandMap();
        $command = $commandMap->getCommand($name);
        $command->setLabel($name . "_disabled");
        $command->unregister($commandMap);
    }
}