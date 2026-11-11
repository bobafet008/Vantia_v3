<?php


require_once __DIR__ . '/../helpers/mail.php';

class SoporteController {

    public function index() {
        // Cargar la vista de soporte
        require_once __DIR__ . '/../../views/soporte.php';
    }

    public function enviarConsulta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre  = trim($_POST['nombre'] ?? '');
            $email   = trim($_POST['email'] ?? '');
            $rol     = trim($_POST['rol'] ?? 'Usuario');
            $asunto  = trim($_POST['asunto'] ?? 'Consulta general');
            $mensaje = trim($_POST['mensaje'] ?? '');

            
            if (empty($nombre) || empty($email) || empty($mensaje)) {
                $_SESSION['error_soporte'] = "Por favor completa todos los campos obligatorios.";
                header("Location: ban.php?pagina=soporte");
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_soporte'] = "Por favor ingresa una dirección de correo válida.";
                header("Location: ban.php?pagina=soporte");
                exit;
            }

            $config = require __DIR__ . '/../../config/mail.php';
            $correoAdmin = $config['usuario'];

            $asuntoMail = "[Atención al Cliente] $asunto - ($rol)";
            $cuerpoHTML = "
                <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px; max-width: 600px;'>
                    <h2 style='color: #2b6cb0; margin-top: 0;'>Nueva Consulta de Atención al Cliente</h2>
                    <p><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</p>
                    <p><strong>Correo del Usuario:</strong> " . htmlspecialchars($email) . "</p>
                    <p><strong>Tipo de Usuario:</strong> " . htmlspecialchars($rol) . "</p>
                    <p><strong>Asunto:</strong> " . htmlspecialchars($asunto) . "</p>
                    <hr style='border: none; border-top: 1px solid #e2e8f0;'>
                    <p><strong>Mensaje:</strong></p>
                    <blockquote style='background: #f7fafc; padding: 12px; border-left: 4px solid #2b6cb0; margin: 0;'>
                        " . nl2br(htmlspecialchars($mensaje)) . "
                    </blockquote>
                    <hr style='border: none; border-top: 1px solid #e2e8f0; margin-top: 20px;'>
                    <p style='font-size: 12px; color: #718096;'>Puedes responder a este correo directamente para escribirle al usuario.</p>
                </div>
            ";

            $exito = enviarCorreo($correoAdmin, "Admin Vantia", $asuntoMail, $cuerpoHTML, $email, $nombre);

            if ($exito) {
                $_SESSION['exito_soporte'] = "¡Tu mensaje ha sido enviado correctamente! El equipo de soporte técnico se pondrá en contacto contigo pronto.";
            } else {
                $_SESSION['error_soporte'] = "No se pudo enviar el mensaje. Por favor intenta más tarde o verifica la configuración SMTP.";
            }

            header("Location: ban.php?pagina=soporte");
            exit;
        }

        header("Location: ban.php?pagina=soporte");
        exit;
    }
}