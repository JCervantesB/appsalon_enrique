<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    public function enviarConfirmacion()
    {
        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];

        $mail->setFrom('CuentaPrueba@appsalon.com');
        $mail->addAddress('CuentaPrueba@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Haz creado tu cuenta en Appsalon ,solo debes confirmarla presionando presionando el siguiente enlace</p>";
        $contenido .= "<p>Presioné aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //enviar el Email
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        //crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASSWORD'];

        $mail->setFrom('CuentaPrueba@appsalon.com');
        $mail->addAddress('CuentaPrueba@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'Restablecer tu password';

        // set html
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Haz solicitado restablecer tu password ,sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presioné aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        //enviar el Email
        $mail->send();
    }
}
