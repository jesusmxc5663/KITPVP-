<?php

namespace kit\command\subcommand;

use pocketmine\command\CommandSender;

use kit\command\DuelCommand;
use kit\command\SubCommand;

class DeleteSubCommand extends SubCommand
{
	
	const PREFIX = '§r§b[PVP]';
	
	/** @var DuelCommand */
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
			 $sender->sendMessage(self::PREFIX.' §r§b/kitpvp deletar <nome> §r§fpara borrar una partida');
			 return false;
		}
		$manager = $this->command->loader->arenaManager;
		
		if (!$manager->existsArena($args[1])) {
			 $sender->sendMessage(self::PREFIX.' §r§cA partida '.$args[1].' no existe!');
			 return false;
		}
		$manager->removeArena($args[1]);
		$sender->sendMessage(self::PREFIX.' §r§aPartida '.$args[1].' borrada.');
		return true;
	}
}
