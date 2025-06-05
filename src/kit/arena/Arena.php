<?php

namespace kit\arena;

use pocketmine\event\{Listener, HandlerList};
use pocketmine\event\player\{PlayerExhaustEvent, PlayerInteractEvent, PlayerQuitEvent, PlayerDeathEvent, PlayerDropItemEvent, PlayerCommandPreprocessEvent};
use pocketmine\event\entity\{EntityLevelChangeEvent, EntityDamageEvent, EntityDamageByEntityEvent, EntityRegainHealthEvent};
use pocketmine\level\Position;
use pocketmine\item\Item;
use pocketmine\Player;

use kit\Loader;

class Arena implements Listener, ArenaStats
{

    /** @var Loader */
    private $loader;

    /** @var array */
    private $data;

    /** @var array */
    public $players = [];

    /** @var int */
    private $time = 5;

    /** @var int */
    public $stat = self::WAIT;

    /** @var string */
    private $kit;

    /** @var bool */
    private $register = false;

    /**
     * Arena construct.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->loader = Loader::getInstance();

        if (!$this->register)
            $this->load();
    }

    /**
     * @param Player $player
     */
    public function join(Player $player)
    {
        if ($this->stat == self::RUN || $this->inArena($player))
            return;

        $player->teleport(new Position(...$this->data['lobby']));

        $this->players[] = $player;

        foreach ($this->players as $target) {
            $target->sendMessage('§r§b[PvP]: §r§b' . $player->getName() . ' entrou na partida.');
        }

        $player->setGamemode(Player::ADVENTURE);

        $player->getInventory()->clearAll();

        $player->setHealth(20);
        $player->setFood(20);

        $player->removeAllEffects();

        $player->getInventory()->setItem(8, Item::get(Item::REDSTONE)->setCustomName('§r§cSair'));
        $player->getInventory()->setItem(5, Item::get(Item::STONE_SWORD)->setCustomName('§l§bKit Diamont'));
    }

    /**
     * @param Player $player
     */
    public function quit(Player $player, $msg = false, $quit = false)
    {
        unset($this->players[array_search($player, $this->players)]);

        if (count($this->players) == 0) {
            $this->close();
        }

        $player->removeAllEffects();

        $player->setGamemode($this->loader->getServer()->getDefaultGamemode());

        if ($quit) {
            $player->setHealth(20);
            $player->setFood(20);
        }

        $player->teleport($this->loader->getServer()->getDefaultLevel()->getSpawnLocation());

        $player->getInventory()->clearAll();

        if ($msg) {
            foreach ($this->players as $target) {
                $target->sendMessage('§r§b[PvP]: §r§c' . $player->getName() . ' saiu da partida.');
            }
        }
    }

    /**
     * @param Player $player
     */
    public function dead(Player $player)
    {
        foreach ($this->players as $target) {
            if ($target != $player) {
                $target->sendMessage('§r§b[PvP]: §r§b' . $player->getName() . ' foi eliminado!');
            }
        }
        $this->join($player);
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function inArena(Player $player): bool
    {
        return in_array($player, $this->players);
    }

    /**
     * Arena game update.
     */
    public function tick()
    {
        switch ($this->stat) {
            case self::WAIT:
                if (count($this->players) > 1)
                    $this->time--;
                    foreach ($this->players as $player) {
                        $player->sendTip('§r§aPartida iniciando em: §l§b' . $this->time);
                    }
                    if ($this->time == 0) {
                        $this->startGame();
                    }
                } else {
                    foreach ($this->players as $player) {
                        $player->sendTip('§r§cAguardando por mais jogadores...');
                    }
                    if ($this->time != 999) {
                        $this->time = 999;
                    }
                }
                break;
            case self::RUN:
                foreach ($this->players as $player) {
                    $player->sendPopup('§r§eTempo restante: §l' . gmdate('i:s', $this->time) . '§r');
                }
                if ($this->time == 0) {
                    $this->closeGame();
                }
                $this->time--;
                break;
        }
    }

    public function startGame()
    {
        foreach ($this->players as $player) {
            $player->teleport(new Position(...$this->data['pos1']));
            $player->setNameTag('§r§c' . $player->getName() . "\n§r§f" . floor($player->getHealth()) . ' §r§c/20');
            $this->loader->kitManager->setKit($player, $this->kit);
        }
        $this->stat = self::RUN;
        $this->time = 60 * 999;
    }

    /**
     * Time over game.
     */
    public function closeGame()
    {
        foreach ($this->players as $player) {
            $player->sendMessage('§r§b[PvP]: §r§cAcabou o tempo, não houve um vencedor!');
            $this->quit($player, false, true);
        }
    }

    /**
     * Reset game
     */
    public function close()
    {
        $this->kit = $this->getRandomKit();
        $this->stat = self::WAIT;
        $this->players = [];
        $this->time = 30;
    }

    /**
     * @return string
     */
    public function getRandomKit(): string
    {
        $kits = $this->loader->yamlProvider->getKits();
        return (string)array_rand($kits);
    }

    /**
     * Load game.
     */
    public function load()
    {
        $this->loader->getServer()->getPluginManager()->registerEvents($this, $this->loader);

        $level = $this->data['lobby'][3];

        if (!$this->loader->getServer()->isLevelLoaded($level))
            $this->loader->getServer()->loadLevel($level);

        $this->data['lobby'][3] = $this->loader->getServer()->getLevelByName($this->data['lobby'][3]);

        $level = $this->data['pos1'][3];
        if (!$this->loader->getServer()->isLevelLoaded($level))
            $this->loader->getServer()->loadLevel($level);

        $this->data['pos1'][3] = $this->loader->getServer()->getLevelByName($this->data['pos1'][3]);
        $this->data['pos2'][3] = $this->loader->getServer()->getLevelByName($this->data['pos2'][3]);

        $this->kit = $this->getRandomKit();

        $this->register = true;
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onExhaust(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();

        if ($this->inArena($player) && $this->stat == self::WAIT) {
            $event->setCancelled(true);
            return;
        }
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($this->inArena($player) && $this->stat == self::WAIT) {
            $event->setCancelled(true);

            if ($item->getCustomName() == '§r§cSair') {
                $this->quit($player, true);
                return;
            }
            if ($item->getCustomName() == '§l§bKit Diamont') {
                $this->join($player);
            }
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onCommandPreprocess(PlayerCommandPreprocessEvent $event)
    {
        $player = $event->getPlayer();
        $message = strtolower($event->getMessage());

        if ($this->inArena($player) && $message{0} == '/') {
            $event->setCancelled(true);
            $command = explode(' ', $message);
            if ($command[0] == '/sair') {
                $this->stat == self::WAIT ? $this->quit($player, true, true) : $this->dead($player);
                return;
            } else {
                $player->sendMessage('§r§b[PvP]: §r§cPara sair da partida utilize: /sair.');
                return;
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();

        if ($this->inArena($player) && $this->stat == self::RUN) {
            $event->setDrops([]);
            $event->setKeepInventory(false);
            $this->dead($player);
            return;
        }
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        if ($this->inArena($player)) {
            $this->stat == self::WAIT ? $this->quit($player, true, true) : $this->dead($player);
        }
    }

    /**
     * @param PlayerDropItemEvent $event
     */
    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();

        if ($this->inArena($player)) {
            $event->setCancelled(true);
            return;
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Player && $this->inArena($entity)) {
            $this->stat == self::WAIT ? $event->setCancelled(true) : $entity->setNameTag('§r§c' . $entity->getName() . "\n§r§f" . floor($entity->getHealth()) . ' §r§c/20');
            return;
        }
    }

    /**
     * @param EntityRegainHealthEvent $event
     */
    public function onRegain(EntityRegainHealthEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof Player && $this->inArena($entity) && $this->stat == self::RUN) {
            $entity->setNameTag('§r§c' . $entity->getName() . "\n§r§f" . floor($entity->getHealth()) . ' §r§c/20');
            return;
        }
    }

    /**
     * Arena desconstruct.
     */
    public function __destruct()
    {
        HandlerList::unregisterAll($this);
    }
}
