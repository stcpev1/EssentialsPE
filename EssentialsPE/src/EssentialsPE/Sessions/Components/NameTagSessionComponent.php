<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Components;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\BaseSavedSessionComponent;
use EssentialsPE\Sessions\PlayerSession;
use EssentialsPE\Sessions\Providers\BaseSessionProvider;
use EssentialsPE\Utils\ChatUtils;
use pocketmine\utils\TextFormat as TF;

class NameTagSessionComponent extends BaseSavedSessionComponent {

	private $nick = null;
	private $prefix = null;
	private $suffix = null;

	public function __construct(Loader $loader, PlayerSession $session, array $data = []) {
		parent::__construct($loader, $session);
		if(isset($data[BaseSessionProvider::NICK])) {
			$this->setNick($data[BaseSessionProvider::NICK] === null ? "clear" : $data[BaseSessionProvider::NICK]);
		}
		if(isset($data[BaseSessionProvider::PREFIX])) {
			$this->setPrefix($data[BaseSessionProvider::PREFIX] === null ? "clear" : $data[BaseSessionProvider::PREFIX]);
		}
		if(isset($data[BaseSessionProvider::SUFFIX])) {
			$this->setSuffix($data[BaseSessionProvider::SUFFIX] === null ? "clear" : $data[BaseSessionProvider::SUFFIX]);
		}
	}


	/**
	 * @return string
	 */
	public function getNick(): string {
		if($this->nick === null) {
			return $this->getPlayer()->getName();
		}
		return $this->nick . TF::RESET;
	}

	/**
	 * @param string $nick
	 * @param bool   $noSymbol
	 *
	 * @return bool
	 */
	public function setNick(string $nick, bool $noSymbol = false): bool {
		if(!ChatUtils::colorMessage($this->getNickSymbol() . $nick, $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.error.color-codes-permission"), $this->getPlayer())) {
			return false;
		}
		if(($n = strtolower($nick)) === strtolower($this->getPlayer()->getName()) || $n === "off" || trim($nick) === "") {
			return $this->clearNick();
		}
		if($n === "clear") {
			$this->nick = null;
			$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
			$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
			return true;
		}

		$nick = $noSymbol ? "" : $this->getLoader()->getConfigurableData()->getConfiguration()->get("Chat.Nick-Symbol") . $nick;
		$this->nick = $nick;

		$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
		$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
		return true;
	}

	/**
	 * @return bool
	 */
	public function clearNick(): bool {
		return $this->setNick("clear");
	}

	/**
	 * @return bool
	 */
	public function clearPrefix(): bool {
		return $this->setPrefix("clear");
	}

	/**
	 * @return string
	 */
	public function getPrefix(): string {
		if($this->prefix === null) {
			return "";
		}
		return $this->prefix . TF::RESET;
	}

	/**
	 * @param string $prefix
	 *
	 * @return bool
	 */
	public function setPrefix(string $prefix): bool {
		if(!ChatUtils::colorMessage($this->getNickSymbol() . $prefix, $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.error.color-codes-permission"), $this->getPlayer())) {
			return false;
		}
		if(($n = strtolower($prefix)) === strtolower($this->getPlayer()->getName()) || $n === "off" || trim($prefix) === "") {
			return $this->clearPrefix();
		}
		if($n === "clear") {
			$this->prefix = null;
			$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
			$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
			return true;
		}

		$this->prefix = $prefix;
		$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
		$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
		return true;
	}

	/**
	 * @return bool
	 */
	public function clearSuffix(): bool {
		return $this->setSuffix("clear");
	}

	/**
	 * @return string
	 */
	public function getSuffix(): string {
		if($this->suffix === null) {
			return "";
		}
		return (string) $this->suffix;
	}

	/**
	 * @param string $suffix
	 *
	 * @return bool
	 */
	public function setSuffix(string $suffix): bool {
		if(!ChatUtils::colorMessage($this->getNickSymbol() . $suffix, $this->getLoader()->getConfigurableData()->getMessagesContainer()->getMessage("general.error.color-codes-permission"), $this->getPlayer())) {
			return false;
		}
		if(($n = strtolower($suffix)) === strtolower($this->getPlayer()->getName()) || $n === "off" || trim($suffix) === "") {
			return $this->clearSuffix();
		}
		if($n === "clear") {
			$this->suffix = null;
			$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
			$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
			return true;
		}

		$this->suffix = $suffix;
		$this->getPlayer()->setDisplayName($this->getPrefix() . $this->getNick() . $this->getSuffix());
		$this->getPlayer()->setNameTag($this->getPrefix() . $this->getNick() . $this->getSuffix());
		return true;
	}

	public function save() {
		$this->getSession()->addToSavedData(BaseSessionProvider::NICK, $this->nick);
		$this->getSession()->addToSavedData(BaseSessionProvider::PREFIX, $this->prefix);
		$this->getSession()->addToSavedData(BaseSessionProvider::SUFFIX, $this->suffix);
	}

	/**
	 * @return string
	 */
	private function getNickSymbol(): string {
		return (string) $this->getLoader()->getConfigurableData()->getConfiguration()->get("Chat.Nick-Symbol");
	}
}