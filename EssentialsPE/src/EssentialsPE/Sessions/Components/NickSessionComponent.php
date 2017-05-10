<?php

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use EssentialsPE\Utils\ChatUtils;
use pocketmine\utils\TextFormat as TF;

class NickSessionComponent extends BaseSavedSessionComponent {

	private $nick = null;

	public function __construct(Loader $loader, PlayerSession $session, array $data = []) {
		parent::__construct($loader, $session);
		if(isset($data[BaseSessionProvider::NICK])) {
			$this->setNick($data[BaseSessionProvider::NICK] === null ? null : $data[BaseSessionProvider::NICK]);
		}
	}


	/**
	 * @return string
	 */
	public function getNick(): string {
		return $this->nick . TF::RESET;
	}

	/**
	 * @param string $nick
	 *
	 * @return bool
	 */
	public function setNick(string $nick): bool {
		if(!ChatUtils::colorMessage($this->getNickSymbol() . $nick, $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.error.color-codes-permission"), $this->getPlayer())) {
			return false;
		}
		if(($n = strtolower($nick)) === strtolower($this->getPlayer()->getName()) || $n === "off" || trim($nick) === "") {
			return $this->clearNick();
		}
		if($n === null) {
			$this->getPlayer()->setDisplayName($this->getPlayer()->getName());
			$this->getPlayer()->setNameTag($this->getPlayer()->getName());
			return true;
		}

		$this->getPlayer()->setDisplayName($nick);
		$this->getPlayer()->setNameTag($nick);
		$this->nick = $nick;
		return true;
	}

	/**
	 * @return bool
	 */
	public function clearNick(): bool {
		return $this->setNick(null);
	}

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::NICK, $this->nick);
	}

	/**
	 * @return string
	 */
	private function getNickSymbol(): string {
		return $this->getLoader()->getConfigurableData()->getConfiguration()->get("Chat.Nick-Symbol");
	}
}