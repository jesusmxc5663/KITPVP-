<?php

namespace kit\command;

use pocketmine\command\{Command, CommandSender};
use pocketmine\Player;

use kit\command\subcommand\{CreateSubCommand, SetSubCommand, DeleteSubCommand, NPCSubCommand, HelpSubCommand};
use kit\Loader;

class KitCommand extends Command
{
	
	const PREFIX = '§b[Duel]: ';
	
	/** @var Loader */
	public $loader;
	
	/** @var array */
	private $subCommands = [];
	
	/** @var array */
	public $data = [];
	
	/**
	  * KitCommand constructor.
	  */
	public function __construct(Loader $loader)
	{
		$this->loader = $loader;
		$this->registerSubCommands();
		parent::__construct('kitpvp', 'batalla entre todos .', null, ['todos']);
	}
	
	/**
	  * @oaram CommandSender $sender
	  * @param string $commandLabel
	  * @param array $args
	  */
	public function execute(CommandSender $sender, $commandLabel, array $args)
	{
		if (!$sender instanceof Player or !$sender->hasPermission('kit.command'))
		      return false;
		
		if (count($args) < 1) {
			 $sender->sendMessage(self::PREFIX.' §r§b/'.$commandLabel.' ayuda §r§fPara obtener ayuda Op.');
			 return false;
		}
		if (!isset($this->subCommands[$args[0]])) {
			  $sender->sendMessage(self::PREFIX.' §r§cO sub comando '.$args[0].' no existe');
			  return false;
		}
		$this->subCommands[$args[0]]->execute($sender, $args);
		return true;
	}
	
	public function registerSubCommands()
	{
		$this->subCommands['criar'] = new CreateSubCommand($this);
		$this->subCommands['setar'] = new SetSubCommand($this);
		$this->subCommands['deletar'] = new DeleteSubCommand($this);
		$this->subCommands['npc'] = new NPCSubCommand($this);
		$this->subCommands['ayuda'] = new HelpSubCommand($this);
		
	}
}
