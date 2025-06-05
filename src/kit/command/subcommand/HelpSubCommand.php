<?php

namespace duel\command\subcommand;

use pocketmine\command\CommandSender;

use duel\command\DuelCommand;
use duel\command\SubCommand;

class HelpSubCommand extends SubCommand
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
		$sender->sendMessage(implode("\n", [
		self::PREFIX." §r§fAjuda",
		" ",
		self::PREFIX." §r§f/duel criar §r§fpara criar uma partida.",
		self::PREFIX." §r§f/duel deletar §r§fpara deletar uma partida.",
		self::PREFIX." §r§f/duel setar §r§fpara definir as posições.",
		self::PREFIX." §r§f/duel npc §r§fpara spawnar o slapper de entrar nas partidas."
		]));
		return true;
	}
}