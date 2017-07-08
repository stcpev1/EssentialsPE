<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns\Others;

use EssentialsPE\EventHandlers\SpecialSigns\BaseSign;
use EssentialsPE\Loader;
use pocketmine\Server;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;

class GamemodeSign extends BaseSign {

	public function __construct(Loader $loader) {
		parent::__construct($loader, "gamemode");
	}

	/**
	 * @param SignChangeEvent $event
	 * @param Sign            $sign
	 *
	 * @return bool
	 */
	public function create(SignChangeEvent $event, Sign $sign): bool {
		$gamemode = $sign->namedtag->essentialsSS->{'0'};
		if(($gamemodeInt = Server::getGamemodeFromString($gamemode)) === -1) {
			return false;
		}
		return true;
	}

	/**
	 * @param PlayerInteractEvent $event
	 * @param Sign                $sign
	 *
	 * @return bool
	 */
	public function tap(PlayerInteractEvent $event, Sign $sign): bool {
		$gamemode = Server::getGamemodeFromString($sign->namedtag->essentialsSS->{'0'});
		$event->getPlayer()->setGamemode($gamemode);
		return true;
	}
}