<?php

namespace EssentialsPE;

use pocketmine\Server;

class SpoonDetector {

	/*
	 * Thank you @Falk.
	 */

	private static $subtleAsciiSpoon = "   
         ___ _ __    ___    ___  _ __  
        / __| '_ \\ / _ \\ / _ \\|'_ \\ 
        \\__ \\ |_) | (_) | (_) | | | |
        |___/ .__/ \\___/ \\___/|_| |_|
            | |                      
            |_|                      
    ";

	private static $spoonTxtContent = "
        LegendsOfMCPE does not provide support for third-party builds of 
        PocketMine-MP (spoons). Spoons detract from the overall quality of the Minecraft PE plugin environment, which is already 
        lacking in quality. They force plugin developers to waste time trying to support conflicting APIs.
    
        In order to begin using EssentialsPE you must understand that you will be offered no support whatsoever. 
    
        Furthermore, the GitHub issue tracker for EssentialsPE is targeted at vanilla PocketMine only. Any bugs you create which don't affect vanilla PocketMine will be deleted.
    
        Have you read and understood the above (type 'yes' after the question mark)?";

	private static $thingsThatAreNotSpoons = [
		"PocketMine-MP"
	];

	/**
	 * @param Loader $loader
	 */
	public static function printSpoon(Loader $loader) {
		if(self::isThisSpoon()) {
			if(!file_exists($loader->getDataFolder() . "SpoonAgreement.txt")) {
				file_put_contents($loader->getDataFolder() . "SpoonAgreement.txt", self::$spoonTxtContent);
			}
			if(!self::contentValid(file_get_contents($loader->getDataFolder() . "SpoonAgreement.txt"))) {
				$loader->getLogger()->info(self::$subtleAsciiSpoon);
				$loader->getLogger()->warning("You are attempting to run " . $loader->getDescription()->getName() . " on a SPOON!");
				$loader->getLogger()->warning("Before using the plugin you will need to open /plugins/" . $loader->getDescription()->getName() . "/" . "SpoonAgreement.txt" . " in a text editor and agree to the terms.");
				$loader->getServer()->getPluginManager()->disablePlugin($loader);
			}
		}
	}

	/**
	 * @return bool
	 */
	public static function isThisSpoon(): bool {
		return !in_array(Server::getInstance()->getName(), self::$thingsThatAreNotSpoons);
	}

	/**
	 * @param string $content
	 *
	 * @return bool
	 */
	private static function contentValid(string $content): bool {
		return (strpos($content, self::$spoonTxtContent) > -1) && (strrpos($content, "yes") > strrpos($content, "?"));
	}
}