<?php
class Usuario
{
    public static $COD;
    public $nomGuerra;
    private $contraseña;
    public $ganadas;
    public $realizadas;

    function __construct($nom, $contraseña)
    {
        $this->nomGuerra = $nom;
        $this->contraseña = $contraseña;
        $this->ganadas = 0;
        $this->realizadas = 0;
        self::$COD++;
    }
    function __toString()
    {
        return "Usuario{".self::$COD.", ".$this->nomGuerra.", ".$this->ganadas.", ".$this->realizadas." }";
    }
    //--------------------------------------------Getter&setter--------------------------------------

    
    public function getNomGuerra()
    {
        return $this->nomGuerra;
    }

    public function getContraseña()
    {
        return $this->contraseña;
    }

    public function getGanadas()
    {
        return $this->ganadas;
    }
    
    public function setGanadas($ganadas)
    {
        $this->ganadas = $ganadas;

        return $this;
    }

    public function getRealizadas()
    {
        return $this->realizadas;
    }

    public function setRealizadas($realizadas)
    {
        $this->realizadas = $realizadas;

        return $this;
    }

    public function getCOD()
    {
        return $this->COD;
    }
}
