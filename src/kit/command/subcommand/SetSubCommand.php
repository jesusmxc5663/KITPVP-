<?php

namespace duel\command\subcommand;

use pocketmine\command\CommandSender;

use duel\command\DuelCommand;
use duel\command\SubCommand;

class SetSubCommand extends SubCommand
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
			 $sender->sendMessage(self::PREFIX.' §r§b/duel setar <localização> §r§fpara setar uma localização.');
			 return false;
		}
		if (empty($this->command->data[$sender->getName()]))
		     $this->command->data[$sender->getName()] = [];
		
		if (!in_array($args[1], ['lobby', 'pos1', 'pos2'])) {
			  $sender->sendMessage(self::PREFIX.' §r§cA localização '.$args[1].' não existe!, localizações disponíveis: lobby, pos1, pos2');
			 return false;
		}
		$this->command->data[$sender->getName()][$args[1]] = [$sender->x+0.5, $sender->y, $sender->z+0.5, $sender->level->getFolderName()];
		$sender->sendMessage(self::PREFIX.' §r§aLocalização '.$args[1].' setada.');
		return true;
	}
}