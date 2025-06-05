<?php

namespace duel\provider;

use pocketmine\utils\Config;
use duel\Loader;

class YamlProvider implements Provider
{
    private $loader;
    private $kits = [];
    private $join = [];
    private $statsConfig;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
        $this->load();
    }

    public function load()
    {
        $folder = $this->loader->getDataFolder();

        if (!is_dir($folder)) {
            @mkdir($folder);
        }

        if (!is_dir($folder . 'arenas')) {
            @mkdir($folder . 'arenas');
        }

        if (!is_dir($folder . 'stats')) {
            @mkdir($folder . 'stats');
        }

        $kitsFile = $folder . 'kits.yml';
        if (!is_file($kitsFile)) {
            file_put_contents($kitsFile, yaml_emit([]));
        }
        $kitsConfig = new Config($kitsFile, Config::YAML);
        $this->kits = $kitsConfig->getAll();

        $joinFile = $folder . 'join.yml';
        if (!is_file($joinFile)) {
            file_put_contents($joinFile, yaml_emit([]));
        }
        $joinConfig = new Config($joinFile, Config::YAML);
        $this->join = $joinConfig->getAll();

        $statsFile = $folder . 'stats.yml';
        if (!is_file($statsFile)) {
            file_put_contents($statsFile, yaml_emit([]));
        }
        $this->statsConfig = new Config($statsFile, Config::YAML);
    }

    public function getKits()
    {
        return $this->kits;
    }

    public function getJoin()
    {
        return $this->join;
    }

    public function getStats()
    {
        return $this->statsConfig->getAll();
    }

    public function saveStats(array $stats): void
    {
        $this->statsConfig->setAll($stats);
        $this->statsConfig->save();
    }
}