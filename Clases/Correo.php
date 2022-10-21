<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__.'/../EnviarCorreo/phpmailer/src/Exception.php';
require_once __DIR__.'/../EnviarCorreo/phpmailer/src/PHPMailer.php';
require_once __DIR__.'/../EnviarCorreo/phpmailer/src/SMTP.php';

function enviarCorreo($email, $nombre, $cod)
{
    try {
        $mail = new PHPMailer();
        //Configuración del servidor
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;             //Habilitar los mensajes de depuración
        $mail->isSMTP();                                   //Enviar usando SMTP
        $mail->Host       = 'smtp.gmail.com';            //Configurar el servidor SMTP
        $mail->SMTPAuth   = true;                          //Habilitar autenticación SMTP
        $mail->Username   = 'auxiliardaw2@gmail.com';            //Nombre de usuario SMTP
        $mail->Password   = 'yjii vqqd pwui isev';                      //Contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   //Habilitar el cifrado TLS
        $mail->Port       = 465;                           //Puerto TCP al que conectarse; use 587 si configuró `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Emisor
        $mail->setFrom('auxiliardaw2@gmail.com', 'AuxiliarDaw');

        //Destinatarios
        if ($nombre != null && $cod != null) {
            $mail->addAddress($email, 'Enviado para: ' . $nombre);     //Añadir un destinatario, el nombre es opcional
        } else {
            $mail->addAddress($email);
        }
        //Destinatarios opcionales
        // $mail->addReplyTo('info@example.com', 'Information');  //Responder a
        // $mail->addCC('cc@example.com');                        //Copia pública
        // $mail->addBCC('bcc@example.com');                      //Copia oculta

        //Archivos adjuntos
        // $mail->addAttachment('files/comunicado.pdf', 'Comunicado');         //Agregar archivos adjuntos, nombre opcional

        //Nombre opcional
        $mail->isHTML(true);                         //Establecer el formato de correo electrónico en HTMl
        $mail->Subject = 'Verificación';
        if ($nombre != null && $cod != null) {
            $mail->Body    = 'Hola ' . $nombre . '</br><a href="http://127.0.0.1:8096/verificar/' . $cod . '">Pincha en este enlace para verificar tu correo</a>';
        } else {
            $mail->Body    = '<p>La verificacion se ha completado correctamente</p>';
        }
        $mail->AltBody = 'Este es el cuerpo en texto sin formato para clientes de correo que no son HTML';

        $mail->send();    //Enviar correo eletrónico
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "No se pudo enviar el mensaje. Error de correo: {$mail->ErrorInfo}";
    }
}
