<?php
namespace EssentialsPE\BaseFiles;

class CooldownStore{
    /** @var int */
    protected $heal;
    /** @var array */
    protected $kits = [];
    /** @var int */
    protected $teleport;

    public function __construct(){
        $this->heal = $this->teleport = time();
    }

    public function updateHeal(){
        $this->heal = time();
    }

    /**
     * @param string $name
     */
    public function updateKit(string $name){
        $this->kits[$name] = time();
    }

    public function updateTeleport(){
        $this->teleport = time();
    }
}