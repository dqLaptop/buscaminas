<?php
class Usuario
{
    public $cod;
    public $email;
    public $nomGuerra;
    private $contraseña;
    public $ganadas;
    public $realizadas;
    private $verificado;

    function __construct($cod, $nom, $contraseña, $ganadas, $realizadas, $verificado, $email)
    {
        $this->nomGuerra = $nom;
        $this->contraseña = $contraseña;
        $this->ganadas = $ganadas;
        $this->realizadas = $realizadas;
        $this->verificado = $verificado;
        $this->cod = $cod;
        $this->email = $email;
    }
    function __toString()
    {
        return "Usuario{" . $this->cod . ", " . $this->nomGuerra . ", " . $this->ganadas . ", " . $this->realizadas . ", " . $this->verificado . ", " . $this->email . " }";
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

    public function getCod()
    {
        return $this->cod;
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
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}
