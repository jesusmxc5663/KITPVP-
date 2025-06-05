<?php

namespace kit\command\subcommand;

use pocketmine\command\CommandSender;

use kit\command\DuelCommand;
use kit\command\SubCommand;

class HelpSubCommand extends SubCommand
{
	
	const PREFIX = '§r§b[Ayuda KitPvP]';
	
	/** @var KitCommand */
	private $command;
	
	/**
	  * CreateSubCommand constructor.
	  * @param KitCommand $command
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
		$sender->sendMessage(implode("\n", [
		self::PREFIX." §r§fAyuda",
		" ",
		self::PREFIX." §r§f/kitpvp criar §r§fpara crear una partida.",
		self::PREFIX." §r§f/kitpvp deletar §r§fpara borrar una partida.",
		self::PREFIX." §r§f/kitpvp setar §r§fpara definir las posiciones",
		self::PREFIX." §r§f/kitpvp npc §r§fpara spawnar o slapper para entrar party."
		]));
		return true;
	}
}
