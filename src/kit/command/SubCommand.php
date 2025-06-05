<?php

namespace duel\command;

use pocketmine\command\CommandSender;

abstract class SubCommand
{
	
	/**
	  * @param CommandSender $sender
	  * @param array $args
	  */
	public abstract function execute(CommandSender $sender, array $args);
}