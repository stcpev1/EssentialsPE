<?php

namespace EssentialsPE\Utils;

use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

class ItemUtils {

	/**
	 * Easily get an item by name and metadata.
	 * The way this function understand the information about the item is:
	 * 'ItemNameOrID:Metadata' - Example (Granite block item):
	 *      '1:1' - or - 'stone:1'
	 *
	 * @param string $item_name
	 *
	 * @return Item|ItemBlock
	 */
	public static function getItem(string $item_name): Item {
		if(strpos($item_name, ":") !== false) {
			$v = explode(":", $item_name);
			$item_name = $v[0];
			$damage = (int)$v[1];
		} else {
			$damage = 0;
		}
		if(!is_numeric($item_name)) {
			$item = Item::fromString($item_name);
			if(strtolower($item_name) !== "air" && $item->getId() === Item::AIR) {
				$item = self::readableNameToItem($item_name);
			}
		} else {
			$item = Item::get($item_name);
		}
		$item->setDamage($damage);
		return $item;
	}

	/**
	 * Converts the readable item name (obtained using function above) to an Item object.
	 *
	 * @param string $item_name
	 *
	 * @return Item
	 */
	public static function readableNameToItem(string $item_name): Item {
		$itemClass = new \ReflectionClass("pocketmine\\item\\Item");
		$itemConstant = strtoupper(str_replace(" ", "_", $item_name));
		if($itemClass->hasConstant($itemConstant)) {
			return Item::get($itemClass->getConstant($itemConstant));
		}
		return Item::get(Item::AIR);
	}

	/**
	 * Returns a name of an item using the class constants of the Item class.
	 * This name is not equal to the getName() function from Item classes, and is used for economy signs.
	 *
	 * @param Item $item
	 *
	 * @return string|null
	 */
	public static function getReadableName(Item $item): string {
		$itemClass = new \ReflectionClass("pocketmine\\item\\Item");
		$itemConstant = "AIR";
		foreach($itemClass->getConstants() as $constant => $value) {
			if($value === $item->getId()) {
				$itemConstant = $constant;
			}
		}
		$itemName = explode("_", strtolower($itemConstant));
		$finalItemName = [];
		foreach($itemName as $nameFragment) {
			$finalItemName[] = ucfirst($nameFragment);
		}
		return implode(" ", $finalItemName);
	}

	/**
	 * Checks whether the given item is a repairable item. (tool/armor)
	 *
	 * @param Item $item
	 *
	 * @return bool
	 */
	public static function isRepairable(Item $item): bool {
		if($item->isTool() || $item instanceof Armor) {
			return true;
		}
		return false;
	}
}