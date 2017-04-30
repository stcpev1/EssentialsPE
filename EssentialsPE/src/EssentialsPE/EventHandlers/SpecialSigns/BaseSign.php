<?php

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

abstract class BaseSign implements Listener {

	private $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * @param PlayerInteractEvent $interactEvent
	 */
	public abstract function onInteract(PlayerInteractEvent $interactEvent);

	/**
	 * @param SignChangeEvent $signChangeEvent
	 */
	public abstract function onSignChange(SignChangeEvent $signChangeEvent);
}