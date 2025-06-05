<?php

namespace kit\command\subcommand;

use pocketmine\command\CommandSender;

use kit\command\KitCommand;
use kit\command\SubCommand;

class CreateSubCommand extends SubCommand
{
	
	const PREFIX = '§r§b[PvP]';
	
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
			 $sender->sendMessage(self::PREFIX.' §r§b/kitpvp criar <nome> §r§fpara crear una pelea');
			 return false;
		}
		if (empty($this->command->data[$sender->getName()])) {
			 $sender->sendMessage(self::PREFIX.' §r§ctu no está modo creador!');
			 return false;
		}
		/** @var array */
		$data = $this->command->data[$sender->getName()];
		
		if (empty($data['lobby']) or empty($data['pos1']) or empty($data['pos2'])) {
			 $sender->sendMessage(self::PREFIX.' §r§cFalta los spawns');
			 return false;
		}
		$manager = $this->command->loader->arenaManager;
		
		if ($manager->existsArena($args[1])) {
			 $sender->sendMessage(self::PREFIX.' §r§cYa hay una partida llamda haci!');
			 return false;
		}
		$manager->createArena(array_merge(['name' => $args[1]], $data));
		unset($this->command->data[$sender->getName()]);
		$sender->sendMessage(self::PREFIX.' §r§aPartida '.$args[1].' creada.');
		return true;
	}
}
