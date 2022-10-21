<?php
require_once __DIR__ . '/Tablero.php';
require_once __DIR__ .'/Usuario.php';
class Partida
{
    public $cod_usu;
    public $cod_tablero;
    public $cod_partida;
    public $tableroJugador;
    public $tableroOculto;
    public $terminada;

    function __construct($id, $id_tablero, $t, $tj, $terminada, $cod)
    {
        $this->cod_tablero = $id_tablero;
        $this->cod_partida = $id;
        $this->tableroJugador = $tj;
        $this->tableroOculto = $t;
        $this->terminada = $terminada;
        $this->cod_usu = $cod;
    }
    function jugar($pos)
    {
        $res = 0;
        $casilla = 0;
        $tabocul = $this->tableroOculto;
        for ($i = 0; $i < $tabocul->getTam(); $i++) {
            if ($tabocul->obtenerValorTablero($i) === -1) {
                $casilla++;
            }
        }
        if ($casilla > 0) {
            if (!$tabocul->hayMina()) {
                $this->tableroOculto->Jugada($pos, $this->tableroJug);
                $cad = $this->PasarSituacionPartidaACadena();
                ConexionEstatica::modificarSituacionPartida($this, $cad[0], $cad[1]);
                $res = 1;
            } else {
                $this->terminada = 1;
                ConexionEstatica::modificarSituacionPartida2($this);
                $res = 2;
            }
        } else {
            $this->terminada = 1;
            ConexionEstatica::modificarSituacionPartida2($this);
            $res = 3;
        }
        return $res;
    }
    public function PasarSituacionPartidaACadena()
    {
        $tabocul = $this->tableroOculto;
        $tJug=$this->tableroJugador;
        $cad = '';
        for ($i = 0; $i < $tabocul->getTam(); $i++) {
            if ($tabocul->obtenerValorTablero($i) === -1) {
                $cad = $cad . '#BUM';
            } else {
                $cad = $cad . '#' . $tabocul->obtenerValorTablero($i);
            }
        }
        $cadena = '';
        for ($i = 0; $i < $tJug->getTam(); $i++) {
            if ($tJug->obtenerValorTablero($i) === -2) {
                $cadena = $cadena . '#---';
            } else {
                $cadena = $cadena . '#' . $tJug->obtenerValorTablero($i);
            }
        }
        $cadenaTablero = [];
        array_push($cadenaTablero, $cad);
        array_push($cadenaTablero, $cadena);
        return $cadenaTablero;
    }

    public function getTerminada()
    {
        return $this->terminada;
    }
    public function setTerminada($terminada)
    {
        $this->terminada = $terminada;

        return $this;
    }
    public function getCod_partida()
    {
        return $this->cod_partida;
    }

    public function getCod_tablero()
    {
        return $this->cod_tablero;
    }
    public function getTamanio()
    {
        return $this->tableroOculto->getTam();
    }

    public function getCod_usu()
    {
        return $this->cod_usu;
    }
}
