<?php

namespace kit\provider;

use pocketmine\utils\Config;
use kit\Loader;

class YamlProvider implements Provider
{
    private $loader;
    private $kits = [];
    
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

        $kitsFile = $folder . 'kits.yml';
        if (!is_file($kitsFile)) {
            file_put_contents($kitsFile, yaml_emit([]));
        }
        $kitsConfig = new Config($kitsFile, Config::YAML);
        $this->kits = $kitsConfig->getAll();
    }


    public function getKits()
    {
        return $this->kits;
    }
}