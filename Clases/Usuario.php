<?php
class Usuario
{
    public static $COD;
    public $nomGuerra;
    private $contraseña;
    public $ganadas;
    public $realizadas;
    private $verificado;

    function __construct($cod, $nom, $contraseña, $ganadas, $realizadas, $verificado)
    {
        $this->nomGuerra = $nom;
        $this->contraseña = $contraseña;
        $this->ganadas = $ganadas;
        $this->realizadas = $realizadas;
        $this->verificado = $verificado;
        self::$COD = $cod;
    }
    function __toString()
    {
        return "Usuario{" . self::$COD . ", " . $this->nomGuerra . ", " . $this->ganadas . ", " . $this->realizadas . ", " . $this->verificado . " }";
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

    public function getVerificado()
    {
        return $this->verificado;
    }
    public function setVerificado($verificado)
    {
        $this->verificado = $verificado;

        return $this;
    }
}
