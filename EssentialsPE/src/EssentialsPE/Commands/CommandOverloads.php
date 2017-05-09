<?php

namespace EssentialsPE\Commands;

class CommandOverloads {

	/*
	 * Perhaps a good idea to move this to json.
	 */

	private static $commandOverloads = [];

	/**
	 * @param string $commandName
	 *
	 * @return array
	 */
	public static function getOverloads(string $commandName): array {
		return self::$commandOverloads[$commandName];
	}

	public static function initialize() {
		self::$commandOverloads = [
			"essentialspe" => [
				0 => [
					"type" => "stringenum",
					"name" => "parameter",
					"optional" => true,
					"enum_values" => [
						"version"
					]
				]
			],
			"break" => [

			],
			"eco" => [
				0 => [
					"type" => "stringenum",
					"name" => "parameter",
					"optional" => false,
					"enum_values" => [
						"give",
						"take",
						"set",
						"reset"
					]
				],
				1 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				],
				2 => [
					"type" => "int",
					"name" => "balance",
					"optional" => true
				]
			],
			"pay" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				],
				1 => [
					"type" => "int",
					"name" => "amount",
					"optional" => false
				]
			],
			"balance" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"broadcast" => [
				0 => [
					"type" => "rawtext",
					"name" => "message",
					"optional" => false
				]
			],
			"balancetop" => [

			],
			"burn" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				],
				1 => [
					"type" => "int",
					"name" => "seconds",
					"optional" => false
				]
			],
			"compass" => [

			],
			"depth" => [

			],
			"extinguish" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"feed" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"getpos" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"heal" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"ping" => [

			],
			"setspawn" => [

			],
			"speed" => [
				0 => [
					"type" => "int",
					"name" => "amplifier",
					"optional" => false
				],
				1 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"sudo" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				],
				1 => [
					"type" => "rawtext",
					"name" => "command",
					"optional" => false
				]
			],
			"suicide" => [

			],
			"top" => [

			],
			"world" => [
				0 => [
					"type" => "rawtext",
					"name" => "world",
					"optional" => false
				]
			],
			"kill" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"tpaccept" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"tpa" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				]
			],
			"tpahere" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				]
			],
			"tpall" => [

			],
			"tpdeny" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"tphere" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => false
				]
			],
			"god" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			],
			"afk" => [
				0 => [
					"type" => "rawtext",
					"name" => "player",
					"optional" => true
				]
			]
		];
	}
}