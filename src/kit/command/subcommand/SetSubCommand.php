<?php

namespace kit\command\subcommand;

use pocketmine\command\CommandSender;

use kit\command\KitCommand;
use kit\command\SubCommand;

class SetSubCommand extends SubCommand
{
	
	const PREFIX = '§r§b[kitpvp Spawns]';
	
	/** @var KitCommand */
	private $command;
	
	/**
	  * CreateSubCommand constructor.
	  * @param KitCommand $command
	  */
	public function __construct(KitCommand $command)
	{
		$this->command = $command;
	}
	
	/**
	  * @param CommandSender $sender
	  * @param array $args
	  */
	public function execute(CommandSender $sender, array $args)
	{
		if (count($args) < 2) {
			 $sender->sendMessage(self::PREFIX.' §r§b/kitpvp setar <posicion> §r§fpara colocar una posición.');
			 return false;
		}
		if (empty($this->command->data[$sender->getName()]))
		     $this->command->data[$sender->getName()] = [];
		
		if (!in_array($args[1], ['lobby', 'pos1', 'pos2'])) {
			  $sender->sendMessage(self::PREFIX.' §r§cNo existe esa posición:'.$args[1].' solo está: lobby, pos1, pos2');
			 return false;
		}
		$this->command->data[$sender->getName()][$args[1]] = [$sender->x+0.5, $sender->y, $sender->z+0.5, $sender->level->getFolderName()];
		$sender->sendMessage(self::PREFIX.' §r§aposicion'.$args[1].' colocada');
		return true;
	}
}
