<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/helpers.php';

// Verificar en tiempo real si el usuario o empresa con sesión activa ha sido suspendido/baneado
if (!empty($_SESSION['usuario_id']) && !empty($_SESSION['tabla_usuario']) && ($_GET['pagina'] ?? '') !== 'salir') {
    require_once __DIR__ . '/../config/database.php';
    $tablaActual = $_SESSION['tabla_usuario'];
    if (in_array($tablaActual, ['empresas', 'usuarios_may', 'usuarios_men'], true)) {
        $stmtBaneo = $conexion->prepare("SELECT baneado, baneado_hasta, motivo_baneo FROM $tablaActual WHERE id = ?");
        $stmtBaneo->execute([(int) $_SESSION['usuario_id']]);
        $cuentaActual = $stmtBaneo->fetch();
        if ($cuentaActual && estaBaneado($cuentaActual)) {
            $msgBaneo = mensajeBaneo($cuentaActual);
            $_SESSION = [];
            session_destroy();
            accesoDenegado($msgBaneo, 'Cuenta Suspendida');
        }
    }
}

$rutas = require __DIR__ . '/../routes/web.php';

$pagina = $_GET['pagina'] ?? 'inicio';

if (!isset($rutas[$pagina])) {
    http_response_code(404);
    exit('La página solicitada no existe.');
}

[$controlador, $accion] = $rutas[$pagina];
require_once __DIR__ . '/../app/Controllers/' . $controlador . '.php';

$objeto = new $controlador();
$objeto->$accion();
