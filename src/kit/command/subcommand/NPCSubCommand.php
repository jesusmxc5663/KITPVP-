<?php

namespace duel\command\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\nbt\tag\{CompoundTag, DoubleTag, ListTag, FloatTag, IntTag, StringTag};
use pocketmine\Player;

use duel\command\DuelCommand;
use duel\command\SubCommand;
use duel\entity\NPC;

class NPCSubCommand extends SubCommand
{
	
	const PREFIX = '§r§b[Duel]';
	
	/** @var DuelCommand */
	private $command;
	
	/**
	  * CreateSubCommand constructor.
	  * @param DuelCommand $command
	  */
	public function __construct(DuelCommand $command)
	{
		$this->command = $command;
	}
	
	/**
     * @param Player $player
     */
    public function createNBT(Player $player) {
        return (new CompoundTag('', array('Pos' => new ListTag('Pos', array(new DoubleTag(0, $player->x), new DoubleTag(1, $player->y), new DoubleTag(2, $player->z))), 'Rotation' => new ListTag('Rotation', array(new DoubleTag(0, $player->yaw), new DoubleTag(1, $player->pitch)))), new ListTag('Motion', array(new DoubleTag(0, 0), new DoubleTag(1, 0), new DoubleTag(2, 0)))));
    }
	
	/**
	  * @param CommandSender $sender
	  * @param array $args
	  */
	public function execute(CommandSender $sender, array $args)
	{
		$entity = new NPC($sender->chunk, $this->createNBT($sender));
		$entity->setNameTagVisible(true);
		$entity->spawnToAll();
		$sender->sendMessage(self::PREFIX.' §r§aNPC setado.');
		return true;
	}
}