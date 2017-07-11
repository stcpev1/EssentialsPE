<?php

declare(strict_types = 1);

namespace EssentialsPE\Utils;

use pocketmine\Player;

class ChatUtils {

	/**
	 * @param string      $message
	 * @param Player|null $player
	 * @param string      $errorMessage
	 * @param bool        $force
	 *
	 * @return bool|string
	 */
	public static function colorMessage(string $message, string $errorMessage = "", Player $player = null, bool $force = false) {
		$message = preg_replace_callback(
			"/(\\\&|\&)[0-9a-fk-or]/",
			function(array $matches) {
				return str_replace("\\§", "&", str_replace("&", "§", $matches[0]));
			},
			$message
		);
		if(strpos($message, "§") !== false && ($player instanceof Player) && !$player->hasPermission("essentials.chat.color") && $force !== true) {
			if($errorMessage === "") {
				return false;
			}
			$player->sendMessage($errorMessage);
			return false;
		}
		return $message;
	}


	/**
	 * Checks if a name is valid, it could be for a Nick, Home, Warp, etc...
	 *
	 * @param string $string
	 * @param bool   $allowColorCodes
	 *
	 * @return bool
	 */
	public static function validateName(string $string, $allowColorCodes = false): bool {
		if(trim($string) === "") {
			return false;
		}
		$format = [];
		if($allowColorCodes) {
			$format[] = "/(\&|\§)[0-9a-fk-or]/";
		}
		$format[] = "/[a-zA-Z0-9_]/";
		$s = preg_replace($format, "", $string);
		if(strlen($s) !== 0) {
			return false;
		}
		return true;
	}
}