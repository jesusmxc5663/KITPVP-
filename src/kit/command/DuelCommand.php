<?php

namespace duel\command;

use pocketmine\command\{Command, CommandSender};
use pocketmine\Player;

use duel\command\subcommand\{CreateSubCommand, SetSubCommand, DeleteSubCommand, NPCSubCommand, HelpSubCommand, JoinSubCommand};
use duel\Loader;

class DuelCommand extends Command
{
	
	const PREFIX = '§b[Duel]: ';
	
	/** @var Loader */
	public $loader;
	
	/** @var array */
	private $subCommands = [];
	
	/** @var array */
	public $data = [];
	
	/**
	  * DuelCommand constructor.
	  */
	public function __construct(Loader $loader)
	{
		$this->loader = $loader;
		$this->registerSubCommands();
		parent::__construct('duel', 'batalhe com um jogador.', null, ['1vs1']);
	}
	
	/**
	  * @oaram CommandSender $sender
	  * @param string $commandLabel
	  * @param array $args
	  */
	public function execute(CommandSender $sender, $commandLabel, array $args)
	{
		if (!$sender instanceof Player or !$sender->hasPermission('duel.command'))
		      return false;
		
		if (count($args) < 1) {
			 $sender->sendMessage(self::PREFIX.' §r§b/'.$commandLabel.' ajuda §r§fpara mais informações.');
			 return false;
		}
		if (!isset($this->subCommands[$args[0]])) {
			  $sender->sendMessage(self::PREFIX.' §r§cO sub comando '.$args[0].' não existe!');
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
		$this->subCommands['ajuda'] = new HelpSubCommand($this);
		$this->subCommands['join'] = new JoinSubCommand($this);
		
	}
}