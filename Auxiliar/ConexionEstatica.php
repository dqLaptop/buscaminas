<?php
class ConexionEstatica
{
    private static $conexion;

    static function abrirConexion()
    {
        try {
            self::$conexion = new mysqli(Constantes::$ruta, Constantes::$usuario, Constantes::$password, Constantes::$bbdd);
        } catch (Exception $e) {
            die();
        }
    }
    static function cerrarConexion(){
        self::$conexion->close();
    }
    /*
    static function getUsuario(){

    }
    static function getUtimaPartida(){

    }
    static function getUltimoTablero(){
        $t=null;
        $query="Select * From ".Constantes::$tablaTablero." Where Terminado = 0";
        self::abrirConexion();
        $stmt = self::$conexion->prepare($query);
        $stmt->execute();
        $resultados = $stmt->get_result();
        try{
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $t;
                }
            }
        }catch(Exception $e){
            $t=;
        }finally{
        $resultados->free_result();
        self::cerrarConexion();
        }
        return $t

    }
    static function deletePartida(){

    }*/
}
