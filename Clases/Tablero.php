<?php
class Tablero{
    public $tam;
    public $minas;
    public static $ID;

    function __construct($tam,$minas)
    {
        $this->$tam=$tam;
        $this->minas=$minas;
        self::$ID++;
    }
}