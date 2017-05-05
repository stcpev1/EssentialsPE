<?php

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\EventHandlers\BaseEventHandler;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

abstract class BaseSign extends BaseEventHandler implements Listener {

	private $name;

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader);
		$this->name = $name;
	}

	public function getName(): string {
		return $this->name;
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