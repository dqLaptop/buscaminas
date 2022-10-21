<?php
require_once './Clases/Correo.php';
require_once './Factoria.php';
require_once './Clases/Tablero.php';
require_once './Clases/Usuario.php';
require_once './Clases/Partida.php';
require_once './Auxiliar/ConexionEstatica.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametro = explode('/', $paths);
unset($parametro[0]);

//Registro y verificacion para el usuario 
if ($requestMethod == 'POST' && $parametro[1] == 'registro') {
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $usuario = ConexionEstatica::insertUsuario($data['email'], $data['nombre'], $data['clave']);
    if ($usuario > 0) {
        $usuario = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
        enviarCorreo($usuario->getEmail(), $usuario->getNomGuerra(), $usuario->getCod());
        $cod = '200';
        $desc = 'Registro realizado';
    } else {
        $cod = '400';
        $desc = 'No se pudo realizar el registro';
    }
}
if ($requestMethod == 'POST' && $parametro[1] == 'consultarinfo') {
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $usuario = ConexionEstatica::getUsuario($data[0], $data[1]);
    if ($usuario != null) {
        $cod = '200';
        $desc = 'Usuario encontrado';
        $mensajeAdicional = ['Usuario' => $u];
    } else {
        $cod = '400';
        $desc = 'No se encontro usuario';
    }
}
if ($requestMethod == 'GET' && $parametro[1] == 'verificacion' && !empty($parametro[2])) {
    if (ConexionEstatica::modificarVerificacion($parametro[2], 1) > 0) {
        enviarCorreo($email, null, null);
    }
}
//Buscaminas
if ($requestMethod == "GET" && $parametro[1] == 'jugarconnivel' && !empty($parametro[2])) {
    if (!is_numeric($parametro[2])) {
        $datosObtenidos = file_get_contents("php://input");
        $data = json_decode($datosObtenidos, true);
        $u = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
        if ($u != null && $u->getVerificado() == 1) {
            if ($p == null) {
                $t = Factoria::crearTablero($parametro[2]);
                $tj = $t;
                $t->formarTableroOculto();
                $tj->formarTableroJug();
                $partida = new Partida(null, $t->getCodigo(), $t, $tj, 0, $u->getCod());
                $cad = $partida->PasarSituacionPartidaACadena();
                ConexionEstatica::insertarSituacionPartida($partida, $cad[0], $cad[1]);
                $cod = 200;
                $desc = 'Juego creado';
                $u->setRealizadas($u->getRealizadas() + 1);
                ConexionEstatica::modificarUsuarioPartidas($u);
            } else {
                $cod = 200;
                $desc = 'Tienes un tablero a medias, terminalo o retirate para poder jugar a uno nuevo';
            }
        } else {
            $cod = 200;
            $desc = 'Comprueba que estas verificado o registrado';
        }
    } else {
        $cod = 400;
        $desc = 'Escribe el nivel que quieres:superfacil, facil, normal, dificil, imposible';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'retirada') {
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $u = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
    if (ConexionEstatica::eliminarPartida($p->getCod_partida(), $u->getCod()) > 0) {
        $cod = 200;
        $desc = 'Tablero eliminado';
    } else {
        $cod = 400;
        $desc = 'No se pudo eliminar el tablero';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'jugar' && !empty($parametro[2]) && !empty($parametro[3])) {
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $u = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
    if ($u != null && $u->getVerificado() == 1) {
        if (is_numeric($parametro[2]) && is_numeric($parametro[3])) {
            if ($parametro[2] < $parametro[3] || $parametro[2] > 100) {
                $cod = 400;
                $desc = 'Revisa los parametros';
            } else {
                if ($p == null) {
                    $t = Factoria::crearTableroPersonalizado($parametro[2], $parametro[3]);
                    $tj = $t;
                    $t->formarTableroOculto();
                    $tj->formarTableroJug();
                    $partida = new Partida(null, $t->getCodigo(), $t, $tj, 0, $u->getCod());
                    $cad = $partida->PasarSituacionPartidaACadena();
                    ConexionEstatica::insertarSituacionPartida($partida, $cad[0], $cad[1]);
                    $cod = 200;
                    $desc = 'Juego creado';
                    $u->setRealizadas($u->getRealizadas() + 1);
                    ConexionEstatica::modificarUsuarioPartidas($u);
                } else {
                    $cod = 200;
                    $desc = 'Tienes un tablero a medias, terminalo o retirate para poder jugar a uno nuevo';
                }
            }
        } else {

            $cod = 400;
            $desc = 'Comprueba los parametros que estas mandando';
        }
    } else {
        $cod = 200;
        $desc = 'Comprueba que estes registrado o verificado';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'jugar' && !empty($parametro[2])) {
    $datosObtenidos = file_get_contents("php://input");
    $data = json_decode($datosObtenidos, true);
    $u = ConexionEstatica::getUsuario($data['nombre'], $data['clave']);
    if ($u != null && $u->getVerificado() == 1) {
        $p = ConexionEstatica::getUltimaPartida($u->getCod());
        if ($p != null) {
            if (is_numeric($parametro[2])) {
                if ($parametro[2] >= 0 || $parametro[2] < $p->getTamanio()) {
                    $respuesta = $p->jugar($parametro[2]);
                    if ($respuesta == 3) {
                        $u->setGanadas($u->getGanadas() + 1);
                        ConexionEstatica::modificarUsuarioPartidas($u);
                        $cod = 200;
                        $desc = 'Felicidades has ganado';
                        $mensajeAdicional = ['Tablero' => $t];
                    } else {
                        if ($respuesta == 2) {
                            $cod = 200;
                            $desc = 'Lo siento pero has pillado una bomba';
                            $mensajeAdicional = ['TableroJug' => $tj->mostrarTablero(), 'Tablero' => $t];
                        } else {
                            if ($respuesta == 1) {
                                $cod = 200;
                                $desc = 'No has dado ha ninguna bomba';
                                $mensajeAdicional = ['Tablero' => $tj->mostrarTablero()];
                            }
                        }
                    }
                } else {
                    $cod = 400;
                    $desc = 'Recuerda las posiciones van de 0 a ' . ($p->getTamanio());
                }
            } else {
                $cod = 400;
                $desc = 'Escribe un número';
            }
        } else {
            $cod = '200';
            $desc = 'Crea una partida nueva';
        }
    } else {
        $cod = '200';
        $desc = 'Usuario no encontrado o no verificado';
    }
}


if ($requestMethod == "PUT" || $requestMethod == "DELETE") {
    $cod = 400;
    $desc = 'Metodo incorrecto';
}

header("HTTP/1.1 " . $cod . " " . $desc);
$mensaje = [
    'cod' => $cod,
    'desc' => $desc
];
if (isset($mensajeAdicional)) {
    $mensaje = array_merge($mensaje, $mensajeAdicional);
}
echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
