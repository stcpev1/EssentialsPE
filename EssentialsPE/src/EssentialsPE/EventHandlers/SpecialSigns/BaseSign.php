<?php

declare(strict_types = 1);

namespace EssentialsPE\EventHandlers\SpecialSigns;

use EssentialsPE\EventHandlers\BaseEventHandler;
use EssentialsPE\Loader;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;

abstract class BaseSign extends BaseEventHandler {

	/** @var string */
	private $name;
	/** @var int */
	private $module = Loader::MODULE_ESSENTIALS;

	public function __construct(Loader $loader, string $name) {
		parent::__construct($loader);
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getModule(): int {
		return $this->module;
	}

	/**
	 * @param int $module
	 */
	public function setModule(int $module) {
		$this->module = $module;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param SignChangeEvent $event
	 * @param Sign            $sign
	 *
	 * @return bool
	 */
	public abstract function create(SignChangeEvent $event, Sign $sign): bool;

	/**
	 * @param PlayerInteractEvent $event
	 * @param Sign                $sign
	 *
	 * @return bool
	 */
	public abstract function tap(PlayerInteractEvent $event, Sign $sign): bool;
}