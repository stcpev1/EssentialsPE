<?php

declare(strict_types = 1);

namespace EssentialsPE\Sessions\Providers;

use EssentialsPE\Loader;

abstract class BaseSessionProvider implements ISessionProvider {

	const IS_AFK = "IsAfk";
	const IS_GOD = "IsGod";
	const IS_MUTED = "IsMuted";
	const MUTED_UNTIL = "MutedUntil";
	const NICK = "Nick";
	const PREFIX = "Prefix";
	const SUFFIX = "Suffix";
	const HAS_PVP_ENABLED = "HasPvpEnabled";
	const HAS_UNLIMITED_ENABLED = "HasUnlimitedEnabled";
	const IS_VANISHED = "IsVanished";

	const POWERTOOL_ID = "PowertoolId";
	const POWERTOOL_COMMAND = "PowertoolCommand";
	const POWERTOOL_CHAT_MACRO = "PowertoolChatMacro";

	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
		$this->prepare();
	}

	/**
	 * @return Loader
	 */
	public function getLoader(): Loader {
		return $this->loader;
	}
}