<?php

namespace duel\utils;

use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;

use pocketmine\Player;

class KitManager {
	
	/** @var array */
	private $data;
	
	/**
	  * KitManager constructor.
	  * @param array $data
	  */
	public function __construct(array $data){
		$this->data = $data;
	}
	
	/**
	  * @return array
      */
	public function getKits() : array
    {
		return $this->data;
	}
	
	/**
	  * @param Player $player
	  * @param string $name
	   */
	public function setKit(Player $player, $name){
		$inv = $player->getInventory();
		$inv->clearAll();
		
		foreach ($this->data[$name] as $data) {
			$d = explode(':', $data['item']);
			$item = Item::get(...$d);
			if (isset($data['enchantments']) and !empty($data['enchantments'])) {
				 foreach ($data['enchantments'] as $enchant => $level) {
					$ench = is_int($enchant) ? Enchantment::getEnchantment($enchant) : Enchantment::getEnchantmentByName($enchant);
					if($enchant != null){
					    $item->addEnchantment($ench->setLevel($level));
				    }
				}
			}
			$armor = [];
			$helmet = [298, 302, 306, 314, 310]; $chestplate = [307, 299, 303, 311, 315];
			$leggings = [300, 304, 308, 312, 316]; $boots = [301, 305, 309, 313, 317];
			if(in_array($item->getId(), $helmet)){
				$player->getInventory()->setHelmet($item);
				$armor[] = $item->getId();
			}
			if(in_array($item->getId(), $chestplate)){
				$player->getInventory()->setChestplate($item);
				$armor[] = $item->getId();
			}
			if(in_array($item->getId(), $leggings)){
				$player->getInventory()->setLeggings($item);
				$armor[] = $item->getId();
			}
			if(in_array($item->getId(), $boots)){
				$player->getInventory()->setBoots($item);
				$armor[] = $item->getId();
			}
			if(!in_array($item->getId(), $armor)){
				$inv->addItem($item);
			}
		}
	}
}