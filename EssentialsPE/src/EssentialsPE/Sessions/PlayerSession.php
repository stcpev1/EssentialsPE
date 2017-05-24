<?php

namespace EssentialsPE\Sessions;

use EssentialsPE\Loader;
use EssentialsPE\Sessions\Components\AfkSessionComponent;
use EssentialsPE\Sessions\Components\GodSessionComponent;
use EssentialsPE\Sessions\Components\MuteSessionComponent;
use EssentialsPE\Sessions\Components\NameTagSessionComponent;
use EssentialsPE\Sessions\Components\NickSessionComponent;
use EssentialsPE\Sessions\Components\TeleportRequestSessionComponent;
use pocketmine\OfflinePlayer;
use pocketmine\Player;

class PlayerSession {

	private $player;
	private $loader;
	private $savedData;

	private $afkComponent;
	private $godComponent;
	private $muteComponent;
	private $nameTagComponent;

	private $teleportComponent;

	public function __construct(Loader $loader, OfflinePlayer $player, array $values = []) {
		$this->player = $player;
		$this->loader = $loader;
		$data = $loader->getSessionManager()->getProvider()->getPlayerData($player);

		$this->afkComponent = new AfkSessionComponent($loader, $this, $data);
		$this->godComponent = new GodSessionComponent($loader, $this, $data);
		$this->muteComponent = new MuteSessionComponent($loader, $this, $data);
		$this->nameTagComponent = new NameTagSessionComponent($loader, $this, $data);

		$this->teleportComponent = new TeleportRequestSessionComponent($loader, $this);
	}

	/**
	 * @return array
	 */
	public function saveData(): array {
		foreach($this as $key => $value) {
			if(strpos($key, "Component") !== false) {
				if($this->{$key} instanceof BaseSavedSessionComponent) {
					$this->{$key}->save();
				}
			}
		}
		$this->getLoader()->getSessionManager()->getProvider()->storePlayerData($this->player, $this->getSavedData());
		return $this->getSavedData();
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @return array
	 */
	private function getSavedData(): array {
		return $this->savedData;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->getLoader()->getServer()->getPlayer($this->player->getName());
	}

	/**
	 * @param string $key
	 * @param        $value
	 */
	public function addToSavedData(string $key, $value) {
		$this->savedData[$key] = $value;
	}

	/**
	 * @param bool $value
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function setAfk(bool $value = true, bool $broadcast = true): bool {
		return $this->afkComponent->setAfk($value, $broadcast);
	}

	/**
	 * @return bool
	 */
	public function isAfk(): bool {
		return $this->afkComponent->isAfk();
	}

	/**
	 * @param bool $broadcast
	 *
	 * @return bool
	 */
	public function switchAfk(bool $broadcast = true): bool {
		return $this->afkComponent->switchAfk($broadcast);
	}

	/**
	 * @param bool $value
	 *
	 * @return bool
	 */
	public function setGod(bool $value = true): bool {
		return $this->godComponent->setGod($value);
	}

	/**
	 * @return bool
	 */
	public function isGod(): bool {
		return $this->godComponent->isGod();
	}

	/**
	 * @return bool
	 */
	public function switchGod(): bool {
		return $this->godComponent->switchGod();
	}

	/**
	 * @return bool
	 */
	public function isMuted(): bool {
		return $this->muteComponent->isMuted();
	}

	/**
	 * @return bool|\DateTime|null
	 */
	public function getMutedUntil() {
		return $this->muteComponent->getMutedUntil();
	}

	/**
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function switchMute(\DateTime $expires = null, bool $notify = true): bool {
		return $this->muteComponent->switchMute($expires, $notify);
	}

	/**
	 * @param bool           $value
	 * @param \DateTime|null $expires
	 * @param bool           $notify
	 *
	 * @return bool
	 */
	public function setMuted(bool $value = true, \DateTime $expires = null, bool $notify = true) {
		return $this->muteComponent->setMuted($value, $expires, $notify);
	}

	/**
	 * @param Player $player
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public function sendTeleportRequestFrom(Player $player, int $mode = TeleportRequestSessionComponent::MODE_TELEPORT_TO): bool {
		return $this->teleportComponent->sendTeleportRequestFrom($player, $mode);
	}

	/**
	 * @return array
	 */
	public function getLatestRequest(): array {
		return $this->teleportComponent->getLatestRequest();
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function hasARequestFrom(Player $player): bool {
		return $this->teleportComponent->hasARequestFrom($player);
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function removeRequest(Player $player): bool {
		return $this->teleportComponent->removeRequest($player);
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function hasAValidRequestFrom(Player $player): bool {
		return $this->teleportComponent->hasAValidRequestFrom($player);
	}

	/**
	 * @param Player $player
	 *
	 * @return bool
	 */
	public function acceptTeleportRequest(Player $player): bool {
		return $this->teleportComponent->acceptTeleportRequest($player);
	}

	/**
	 * @return bool
	 */
	public function acceptLatestRequest(): bool {
		return $this->teleportComponent->acceptLatestRequest();
	}

	/**
	 * @param Player $player
	 *
	 * @return array
	 */
	public function getRequestFrom(Player $player): array {
		return $this->teleportComponent->getRequestFrom($player);
	}

	/**
	 * @return bool
	 */
	public function hasARequest(): bool {
		return $this->teleportComponent->hasARequest();
	}

	/**
	 * @return string
	 */
	public function getNick(): string {
		return $this->nameTagComponent->getNick();
	}

	/**
	 * @param string $nick
	 *
	 * @return bool
	 */
	public function setNick(string $nick): bool {
		return $this->nameTagComponent->setNick($nick);
	}

	/**
	 * @return bool
	 */
	public function clearNick(): bool {
		return $this->nameTagComponent->clearNick();
	}

	/**
	 * @return string
	 */
	public function getPrefix(): string {
		return $this->nameTagComponent->getPrefix();
	}

	/**
	 * @param string $nick
	 *
	 * @return bool
	 */
	public function setPrefix(string $prefix): bool {
		return $this->nameTagComponent->setPrefix($prefix);
	}

	/**
	 * @return bool
	 */
	public function clearPrefix(): bool {
		return $this->nameTagComponent->clearPrefix();
	}

	/**
	 * @return string
	 */
	public function getSuffix(): string {
		return $this->nameTagComponent->getSuffix();
	}

	/**
	 * @param string $nick
	 *
	 * @return bool
	 */
	public function setSuffix(string $suffix): bool {
		return $this->nameTagComponent->setSuffix($suffix);
	}

	/**
	 * @return bool
	 */
	public function clearSuffiix(): bool {
		return $this->nameTagComponent->clearSuffix();
	}
}