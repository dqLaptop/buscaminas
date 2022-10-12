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
    static function cerrarConexion()
    {
        self::$conexion->close();
    }

    static function getUsuario($nombre, $clave)
    {
        $u = null;
        $query = "Select * From " . Constantes::$tablaTablero . " Where NomGuerra Like ? AND CLAVE Like ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ss", $nombre, $clave);
            $stmt->execute();
            $resultados = $stmt->get_result();
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $u = new Usuario($fila['Cod'], $fila['NomGuerra'], $fila['Clave'], $fila['Ganadas'], $fila['Realizadas'], $fila['Verificado']);
                }
            }
        } catch (Exception $e) {
            $u = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            $resultados->free_result();
            self::cerrarConexion();
        }
        return $u;
    }
    static function modificarVerificacion($cod, $verificacion)
    {
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaUsuario . " set Verificacion  = ?  WHERE Cod = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("si", $verificacion, $cod);
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function insertarSituacionTablero($tablero, $tableroJug, $usuario)
    {
        $cad = '';
        for ($i = 0; $i < count($tablero); $i++) {
            if ($tablero[$i] == -1) {
                $cad = $cad . '#BUM';
            } else {
                $cad = $cad . '#' . $tablero[$i];
            }
        }
        $cadena = '';
        for ($i = 0; $i < count($tableroJug); $i++) {
            if ($tablero[$i] == -1) {
                $cadena = $cadena . '#---';
            } else {
                $cadena = $cadena . '#' . $tablero[$i];
            }
        }
        $query = "INSERT INTO " . Constantes::$tablaTablero . "(Id, Cod_usu, TableroOculto,TableroJug,Finalizado) VALUES (?,?,?,?,?)";
    }
    static function modificarSituacionTablero($tablero, $tableroJug, $usuario)
    {
        $cad = '';
        for ($i = 0; $i < count($tablero); $i++) {
            if ($tablero[$i] == -1) {
                $cad = $cad . '#BUM';
            } else {
                $cad = $cad . '#' . $tablero[$i];
            }
        }
        $cadena = '';
        for ($i = 0; $i < count($tableroJug); $i++) {
            if ($tablero[$i] == -1) {
                $cadena = $cadena . '#---';
            } else {
                $cadena = $cadena . '#' . $tablero[$i];
            }
        }
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaTablero . " set Id  = ?, Cod_usu= ?, TableroOculto=?, TableroJug = ?  WHERE Id = ? And Cod_usu= ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sissii", $tablero->getID(), $usuario->getCOD(), $cad, $cadena, $tablero->getID(), $usuario->getCOD());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    /*
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
    static function deleteTablero(){

    }*/
}
