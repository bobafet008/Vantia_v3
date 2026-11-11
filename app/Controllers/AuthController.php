<?php
class AuthController
{
    public function formulario()
    {
        $tipo = $_GET['tipo'] ?? '';
        vista('login', ['tipo' => $tipo]);
    }

    public function validar()
    {
        require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Empresa.php';
        require_once __DIR__ . '/../Models/UsuarioAdmin.php';

        $modeloUsuario = new Usuario($conexion);
        $modeloEmpresa = new Empresa($conexion);
        $modeloAdmin   = new UsuarioAdmin($conexion);

        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $tipoRequerido = $_POST['tipo'] ?? '';

        if ($correo === '' || $contrasena === '') {
            vista('login', [
                'tipo' => $tipoRequerido,
                'error' => 'Por favor, ingresa tu correo y contraseña.',
                'correo' => $correo,
            ]);
            return;
        }

        // 1. Verificar si las credenciales corresponden a un Administrador en usuarios_admin
        $admin = $modeloAdmin->buscarPorCorreo($correo);
        if ($admin && password_verify($contrasena, $admin['contrasena'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int) $admin['id'];
            $_SESSION['usuario'] = $admin['nombre'];
            $_SESSION['correo'] = $admin['correo'];
            $_SESSION['tipo'] = 'admin';
            $_SESSION['cargo'] = $admin['cargo'] ?? 'Administrador General';
            $_SESSION['es_menor'] = 0;
            $_SESSION['tabla_usuario'] = 'usuarios_admin';

            header('Location: ban.php?pagina=panel_admin');
            exit;
        }

        // 1. Si se especificó tipo empresa, intentar validar contra la tabla empresas
        if ($tipoRequerido === 'empresa') {
            $empresa = $modeloEmpresa->buscarPorCorreo($correo);
            if ($empresa && password_verify($contrasena, $empresa['contrasena'])) {
                if (estaBaneado($empresa)) {
                    vista('login', [
                        'tipo' => $tipoRequerido,
                        'error' => mensajeBaneo($empresa),
                        'correo' => $correo,
                    ]);
                    return;
                }

                if (isset($empresa['verificada']) && (int) $empresa['verificada'] === 0) {
                    vista('login', [
                        'tipo' => $tipoRequerido,
                        'error' => 'Tu empresa aún está pendiente de revisión por el administrador.',
                        'correo' => $correo,
                    ]);
                    return;
                }

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $empresa['id'];
                $_SESSION['usuario'] = $empresa['nombre'];
                $_SESSION['representante'] = $empresa['representante'] ?? null;
                $_SESSION['correo'] = $empresa['correo'];
                $_SESSION['tipo'] = 'empresa';
                $_SESSION['es_menor'] = 0;
                $_SESSION['tabla_usuario'] = 'empresas';

                header('Location: ban.php?pagina=panel_empresa');
                exit;
            }
        }

        // 2. Buscar en usuarios mayores (usuarios_may) o menores (usuarios_men)
        $usuario = $modeloUsuario->buscarPorCorreo($correo);
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            if (estaBaneado($usuario)) {
                vista('login', [
                    'tipo' => $tipoRequerido,
                    'error' => mensajeBaneo($usuario),
                    'correo' => $correo,
                ]);
                return;
            }

            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['tipo'] = $usuario['tipo'];
            $_SESSION['es_menor'] = (int) ($usuario['es_menor'] ?? 0);
            $_SESSION['tabla_usuario'] = $usuario['tabla_origen'] ?? ($usuario['es_menor'] ? 'usuarios_men' : 'usuarios_may');

            if ($usuario['tipo'] === 'admin') {
                header('Location: ban.php?pagina=panel_admin');
            } else {
                header('Location: ban.php?pagina=trabajos');
            }
            exit;
        }

        // 3. Si no se especificó tipo empresa pero las credenciales corresponden a una empresa
        if ($tipoRequerido !== 'empresa') {
            $empresa = $modeloEmpresa->buscarPorCorreo($correo);
            if ($empresa && password_verify($contrasena, $empresa['contrasena'])) {
                if (estaBaneado($empresa)) {
                    vista('login', [
                        'tipo' => $tipoRequerido,
                        'error' => mensajeBaneo($empresa),
                        'correo' => $correo,
                    ]);
                    return;
                }

                if (isset($empresa['verificada']) && (int) $empresa['verificada'] === 0) {
                    vista('login', [
                        'tipo' => $tipoRequerido,
                        'error' => 'Tu empresa aún está pendiente de revisión por el administrador.',
                        'correo' => $correo,
                    ]);
                    return;
                }

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = (int) $empresa['id'];
                $_SESSION['usuario'] = $empresa['nombre'];
                $_SESSION['representante'] = $empresa['representante'] ?? null;
                $_SESSION['correo'] = $empresa['correo'];
                $_SESSION['tipo'] = 'empresa';
                $_SESSION['es_menor'] = 0;
                $_SESSION['tabla_usuario'] = 'empresas';

                header('Location: ban.php?pagina=panel_empresa');
                exit;
            }
        }

        vista('login', [
            'tipo' => $tipoRequerido,
            'error' => 'Correo o contraseña incorrectos.',
            'correo' => $correo,
        ]);
    }

    public function salir()
    {
        $_SESSION = [];
        if (session_id() != '' || headers_sent()) {
            session_destroy();
        }
        header('Location: ban.php?pagina=inicio');
        exit;
    }
}
