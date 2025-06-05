<?php

namespace duel\arena;

use duel\Loader;

/**
 * Class EmptyArenaChooser
 * @package vixikhd\onevsone
 */
class EmptyArenaChooser {

    /** @var Loader */
    public $loader;

    /**
     * EmptyArenaChooser constructor.
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }



    /**
     * @return null|Arena
     *
     * 1. Choose all arenas
     * 2. Remove in-game arenas
     * 3. Sort arenas by players
     * 4. Sort arenas by rand()
     */
    public function getRandomArena() 
    {
        //1.

        /** @var array */
        $availableArenas = [];
        foreach ($this->loader->arenaManager->arenas as $index => $arena) {
            $availableArenas[$index] = $arena;
        }

        //2.
        foreach ($availableArenas as $index => $arena) {
            if($arena->stat !== 0) {
                unset($availableArenas[$index]);
            }
        }

        //3.
        $arenasByPlayers = [];
        foreach ($availableArenas as $index => $arena) {
            $arenasByPlayers[$index] = count($arena->players);
        }

        arsort($arenasByPlayers);
        $top = -1;
        $availableArenas = [];

        foreach ($arenasByPlayers as $index => $players) {
            if($top == -1) {
                $top = $players;
                $availableArenas[] = $index;
            }
            else {
                if($top == $players) {
                    $availableArenas[] = $index;
                }
            }
        }

        if(empty($availableArenas)) {
            return null;
        }

        return $this->loader->arenaManager->arenas[$availableArenas[array_rand($availableArenas, 1)]];
    }
}