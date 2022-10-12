<?php
require_once './Factoria.php';
require_once './Clases/Tablero.php';
require_once './Auxiliar/ConexionEstatica.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametro = explode('/', $paths);
unset($parametro[0]);

if ($requestMethod == 'POST' && $parametro[1] == 'iniciarsesion') { //Primer paso el usuario debe verificarse iniciando sesión
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $usuario = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
    if ($usuario != null) {
        $usuario->setVerificado(true);
        ConexionEstatica::modificarVerificacion($usuario->getCOD(), $usuario->getVerificado());
        header("HTTP/1.1 201 Usuario verificado");
        $mensaje = [
            'cod' => '201',
            'desc' => 'Usuario verificado',
            'usuario' => $usuario
        ];
    } else {
        header("HTTP/1.1 400 Usuario no encontrado");
        $mensaje = [
            'cod' => '400',
            'desc' => 'No se pudo realizar la verificación'
        ];
    }
}

if ($requestMethod == 'POST' && $parametro[1] == 'cerrarsesion') { //Ultimo paso cerrar la sesion para que solo pueda jugar ese usuario
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $usuario = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
    if ($usuario != null) {
        $usuario->setVerificado(false);
        ConexionEstatica::modificarVerificacion($usuario->getCOD(), $usuario->getVerificado());
        header("HTTP/1.1 201 Sesion cerrada");
        $mensaje = [
            'cod' => '201',
            'desc' => 'Sesion cerrada'
        ];
    } else {
        header("HTTP/1.1 400 Error al cerrar sesion");
        $mensaje = [
            'cod' => '400',
            'desc' => 'No se pudo cerrar la sesion'
        ];
    }
}
echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
