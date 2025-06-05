<?php

namespace duel\entity;

use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\entity\Entity;
use pocketmine\Player;

use duel\Loader;

class NPC extends Entity {
    
    /**
      * @param float $damager
      * @param EntityDamageEvent $source
      */
    public function attack($damager, EntityDamageEvent $source)
    {
        if ($source instanceof EntityDamageByEntityEvent) {
            $damager = $source->getDamager();
            
            if ($damager instanceof Player) {
                if ($damager->getInventory()->getItemInHand()->getId() == 388 and $damager->isOp()) {
                    $this->close();
                    return;
                }
                $arena = Loader::getInstance()->emptyArenaChooser->getRandomArena();
                
                if ($arena == null) {
                     $damager->sendMessage('§r§eDuel: §r§cNão foi encontrada nenhuma partida disponível!');
                     return;
                }
                $arena->join($damager);
                return;
            }
        }
    }
    
    /**
      * @return string
      */
    public function getText() : string
    {
    	$count = 0;
    	foreach (Loader::getInstance()->arenaManager->arenas as $arena) {
    	     $count += count($arena->players);
        }
        $color = $count == 0 ? '§r§c' : '§r§a';
        return "§r§eDuel §r§7Versão [0.1]\n§r§fJogando: ".$color.$count;
    }
    
    /**
      * @param int $currentTick
      */
    public function onUpdate($currentTick = 15)
    {
    	if ($this->getNameTag() != $this->getText()) {
             $this->setNameTag($this->getText());
        }
    	parent::onUpdate($currentTick);
    }
    
    /**
      * @param Player $player
      */
    public function spawnTo(Player $player) 
    {
        $pk = new AddEntityPacket();
        $pk->eid = $this->getId();
        $pk->type = 15;
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->yaw = $this->yaw;
        $pk->pitch = $this->pitch;
        $pk->speedX = $this->motionX;
        $pk->speedY = $this->motionY;
        $pk->speedZ = $this->motionZ;
        $pk->metadata = $this->dataProperties;
        $pk->metadata[15] = [0, 1];
        $player->dataPacket($pk);
        
        parent::spawnTo($player);
        $this->setRotation($this->yaw, $this->pitch);
    }
}