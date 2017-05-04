<?php

namespace EssentialsPE\Commands;

class CommandOverloads {

	/*
	 * Perhaps a good idea to move this to json...
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

			]
		];
	}
}