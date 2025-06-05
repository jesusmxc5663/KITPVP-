<?php

namespace duel\command\subcommand;

use pocketmine\command\CommandSender;

use duel\command\DuelCommand;
use duel\command\SubCommand;

class DeleteSubCommand extends SubCommand
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
	  * @param CommandSender $sender
	  * @param array $args
	  */
	public function execute(CommandSender $sender, array $args)
	{
		if (count($args) < 2) {
			 $sender->sendMessage(self::PREFIX.' §r§b/duel deletar <nome> §r§fpara deletar uma partida.');
			 return false;
		}
		$manager = $this->command->loader->arenaManager;
		
		if (!$manager->existsArena($args[1])) {
			 $sender->sendMessage(self::PREFIX.' §r§cA partida '.$args[1].' não existe!');
			 return false;
		}
		$manager->removeArena($args[1]);
		$sender->sendMessage(self::PREFIX.' §r§aPartida '.$args[1].' deletada.');
		return true;
	}
}