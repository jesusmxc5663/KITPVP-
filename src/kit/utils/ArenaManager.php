<?php

namespace duel\utils;

use pocketmine\utils\Config;

use duel\arena\Arena;
use duel\Loader;

class ArenaManager
{
	
	/** @var Loader */
	private $loader;
	
	/** @var array */
	public $arenas = [];
	
	/**
	  * ArenaManager constructor.
	  * @param Loader $loader
	  */
	public function __construct(Loader $loader)
	{
		$this->loader = $loader;
		$this->load();
	}
	
	/**
	  * @param string $name
	  * @return bool
      */
	public function existsArena(string $name) : bool
	{
		return isset($this->arenas[$name]);
	}
	
	/**
	  * @param array
      */
	public function createArena(array $data)
	{
		$config = new Config($this->loader->getDataFolder().'arenas'.DIRECTORY_SEPARATOR.$data['name'].'.yml', Config::YAML);
		$config->setAll($data);
		$config->save();
		
		$this->arenas[$data['name']] = new Arena($data);
	}
	
	/**
	  * @param string $name
	  */
	public function removeArena(string $name)
	{
		@unlink($this->loader->getDataFolder().'arenas'.DIRECTORY_SEPARATOR.$data['name'].'.yml');
		unset($this->arenas[$name]);
	}
	
	public function load()
	{
		$folder = $this->loader->getDataFolder();
		
		foreach (scandir($folder.'arenas') as $file) {
			if ($file != '.' and $file != '..') {
				 /** @var Config */
			    $config = new Config($folder.'arenas'.DIRECTORY_SEPARATOR.$file, Config::YAML);
			    $this->arenas[basename($file, '.yml')] = new Arena($config->getAll());
			}
		}
	}
}