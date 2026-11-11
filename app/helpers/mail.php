<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once __DIR__ . '/../../vendor/autoload.php';

function enviarCorreo(string $destinatario, string $nombreDestinatario, string $asunto, string $mensajeHTML, string $replyToEmail = '', string $replyToName = ''): bool {
    $config = require __DIR__ . '/../../config/mail.php';

    $mail = new PHPMailer(true);

    try {
        
        $mail->SMTPDebug = 0; // Desactivado para no romper las redirecciones HTTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['usuario'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 15;
        
        
        $mail->setFrom($config['usuario'], $config['nombre']);

        
        $mail->addAddress($destinatario, $nombreDestinatario);

        
        if (!empty($replyToEmail)) {
            $mail->addReplyTo($replyToEmail, $replyToName);
        }

        
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensajeHTML;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}