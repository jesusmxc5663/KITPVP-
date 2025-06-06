<?php

namespace kit;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
use pocketmine\entity\Entity;
use kit\provider\YamlProvider;
use kit\utils\KitManager;
use kit\utils\ArenaManager;
use kit\arena\EmptyArenaChooser;
use kit\command\KitCommand;
use kit\entity\NPC;

class Loader extends PluginBase {

	private static $instance;
	public $yamlProvider = null;
	public $kitManager = null;
	public $arenaManager = null;
	public $emptyArenaChooser = null;

	public function onLoad() {
		self::$instance = $this;
		$this->saveResource('kits.yml');
  }

	public function onEnable() {
		$this->yamlProvider = new YamlProvider($this);
		$this->kitManager = new KitManager($this->yamlProvider->getKits());
		$this->arenaManager = new ArenaManager($this);
		$this->emptyArenaChooser = new EmptyArenaChooser($this);

		Entity::registerEntity(NPC::class, true);

		$this->getServer()->getCommandMap()->register('kitpvp:', new KitCommand($this));

		$this->getServer()->getScheduler()->scheduleRepeatingTask(new class($this) extends PluginTask {

			private $loader;

			public function __construct(Loader $loader) {
				$this->loader = $loader;
				parent::__construct($loader);
			}

			public function onRun($currentTick) {
				foreach ($this->loader->arenaManager->arenas as $arena) {
					$arena->tick();
				}
			}
		}, 19);
	}

	public static function getInstance(): Loader {
		return self::$instance;
	}
}
