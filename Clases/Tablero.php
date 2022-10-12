<?php
class Tablero
{

    public $tablero;
    public $minas;
    public $tam;
    public $terminado;
    public static $ID;

    function __construct($tam, $minas)
    {
        $this->terminado = false;
        $this->tablero = array();
        $this->tam = $tam;
        $this->minas = $minas;
        self::$ID++ . "A";
    }
    //Hacer sobrecarga del constructor


    function __toString()
    {
        $cad = "";
        for ($i = 0; $i < $this->tam; $i++) {
            if ($this->tablero[$i] == -1) {
                $cad = $cad . ' ¡¡¡ BUM !!! ';
            } else {
                $cad = $cad . $this->tablero[$i] . ' ';
            }
        }
        return $cad;
    }
    function mostrarTablero()
    {
        $cad = "";
        for ($i = 0; $i < $this->tam; $i++) {
            if ($this->tablero[$i] == -2) {
                $cad = $cad . ' --- ';
            } else {
                $cad = $cad . ' ' . $this->tablero[$i] . ' ';
            }
        }
        return $cad;
    }
    function Jugada($pos, $tablero, $tableroJugador)
    {
        $tableroJugador[$pos] = $tablero[$pos];
    }

    function formarTablero()
    {
        for ($i = 0; $i < $this->tam; $i++) {
            $this->tablero[$i] = 0;
        }
        $this->colocarMina();
        return $this->tablero;
    }

    function colocarMina()
    {
        while ($this->minas > 0) {
            $alea = rand(0, (($this->tam) - 1));
            if ($this->tablero[$alea] != -1) {
                $this->tablero[$alea] = -1;
                $this->minas--;
            }
        }
    }
    function ComprobarSiHayMina($pos)
    {
        $resultado = 0;
        if ($this->tablero[$pos] == -1) {
            $resultado = 1;
        }
        if ($pos - 1 >= 0) {
            if ($this->tablero[$pos - 1] == -1) {
                $this->tablero[$pos]++;
                $resultado = 2;
            }
        }
        if ($pos + 1 <= $this->length - 1) {
            if ($this->tablero[$pos + 1] == -1) {
                $this->tablero[$pos]++;
                $resultado = 2;
            }
        }
        return $resultado;
    }

    public function getTerminado()
    {
        return $this->terminado;
    }

    public function setTerminado($terminado)
    {
        $this->terminado = $terminado;

        return $this;
    }

    public function getID()
    {
        return $this->ID;
    }
}
