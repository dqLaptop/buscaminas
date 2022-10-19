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
        $query = "Select * From " . Constantes::$tablaUsuario . " Where NomGuerra Like ? AND Clave Like ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ss", $nombre, $clave);
            $stmt->execute();
            $resultados = $stmt->get_result();
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $u = new Usuario($fila['Cod'], $fila['NomGuerra'], $fila['Clave'], $fila['Ganadas'], $fila['Realizadas'], $fila['Verificado'], $fila['Email']);
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
    static function insertUsuario($email, $nombre, $clave)
    {
        $filasAfectadas = 0;
        $query = "INSERT INTO " . Constantes::$tablaPartida . "(Cod,Email,NomGuerra,Clave,Ganadas,Realizadas,Verificacion) VALUES (?,?,?,?,?,?,?)";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("isssiib", null, $email, $nombre, $clave, 0, 0, 0);
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function getUsuarioCod($cod)
    {
        $u = null;
        $query = "Select * From " . Constantes::$tablaUsuario . " Where Cod = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("i", $cod);
            $stmt->execute();
            $resultados = $stmt->get_result();
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $u = new Usuario($fila['Cod'], $fila['NomGuerra'], $fila['Clave'], $fila['Ganadas'], $fila['Realizadas'], $fila['Verificado'], $fila['Email']);
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
    static function insertarSituacionPartida($partida, $t, $tj)
    {
        $query = "INSERT INTO " . Constantes::$tablaPartida . "(Id_usu,Id,id_tablero,Terminada,TableroOculto,TableroJug) VALUES (?,?,?,?,?,?)";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("iisiss", $partida->getCod_usu(), $partida->getCod_partida(), $partida->getCod_tablero(), $partida->getTerminada(), $t, $tj);
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function modificarSituacionPartida($partida, $t, $tj)
    {
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaPartida . " set Id  = ?, Id_tablero=?, Terminada=?, TableroOculto=?, TableroJug = ?  WHERE Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("isissi", $partida->getCod_partida(), $partida->getCod_tablero(), $partida->getTerminada(), $t, $tj, $partida->getCod_partida());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function modificarSituacionPartida2($partida)
    {
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaPartida . " set Terminada=? WHERE Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ii", $partida->getTerminada(), $partida->getCod_partida());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }

    static function getUltimaPartida($cod)
    {
        $p = null;
        $query = "Select * From " . Constantes::$tablaPartida . " Where Id_usu= ? And Terminada = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("ii",  $cod, 0);
            $stmt->execute();
            $resultados = $stmt->get_result();
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $tableroOculto = explode('#', $fila[3]);
                    for ($i = 0; $i < count($tableroOculto); $i++) {
                        if ($tableroOculto[$i] == 'BUM') {
                            $tableroOculto[$i] == -1;
                        }
                    }
                    $tableroJug = explode('#', $fila[4]);
                    for ($i = 0; $i < count($tableroJug); $i++) {
                        if ($tableroJug[$i] == '---') {
                            $tableroJug[$i] == -2;
                        }
                    }
                    $p = new Partida($fila[0], $fila[1], $tableroOculto, $tableroJug, $fila[2], $fila[5]);
                }
            }
        } catch (Exception $e) {
            $p = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            if ($resultados != null) { //Realizado este if porque da fallo si va en nulo
                $resultados->free_result();
            }
            self::cerrarConexion();
        }
        return $p;
    }

    static function eliminarPartida($id)
    {
        $cod = 0;
        $query = "Select * From " . Constantes::$tablaPartida . " Where Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $cod = $stmt->affected_rows;
        } catch (Exception $e) {
            $cod = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $cod;
    }
}
