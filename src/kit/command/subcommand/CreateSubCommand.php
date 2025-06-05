<?php

namespace duel\command\subcommand;

use pocketmine\command\CommandSender;

use duel\command\DuelCommand;
use duel\command\SubCommand;

class CreateSubCommand extends SubCommand
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
			 $sender->sendMessage(self::PREFIX.' §r§b/duel criar <nome> §r§fpara criar uma partida.');
			 return false;
		}
		if (empty($this->command->data[$sender->getName()])) {
			 $sender->sendMessage(self::PREFIX.' §r§cVocê não está no modo criador!');
			 return false;
		}
		/** @var array */
		$data = $this->command->data[$sender->getName()];
		
		if (empty($data['lobby']) or empty($data['pos1']) or empty($data['pos2'])) {
			 $sender->sendMessage(self::PREFIX.' §r§cVocê não setou as localizações!');
			 return false;
		}
		$manager = $this->command->loader->arenaManager;
		
		if ($manager->existsArena($args[1])) {
			 $sender->sendMessage(self::PREFIX.' §r§cJá existe uma partida com este nome!');
			 return false;
		}
		$manager->createArena(array_merge(['name' => $args[1]], $data));
		unset($this->command->data[$sender->getName()]);
		$sender->sendMessage(self::PREFIX.' §r§aPartida '.$args[1].' criada.');
		return true;
	}
}