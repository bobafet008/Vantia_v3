<?php
class UsuarioController
{
    public function registro()
    {
        $tipo = ($_GET['tipo'] ?? '') === 'empresa' ? 'empresa' : 'empleado';
        vista('registro', ['tipo' => $tipo]);
    }

    public function registroEmpleado()
    {
        vista('registro', ['tipo' => 'empleado']);
    }

    public function registroEmpresa()
    {
        vista('registro', ['tipo' => 'empresa']);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ban.php?pagina=registro_empleado');
            exit;
        }

        $tipo = $_POST['tipo'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $confirmarContrasena = $_POST['confirmar_contrasena'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');

        // Validación inicial común
        if ($tipo !== 'empleado' && $tipo !== 'empresa') {
            $error = 'Selecciona un tipo de cuenta válido.';
        } elseif ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $error = 'Completa el nombre y proporciona un correo válido.';
        } elseif (strlen($contrasena) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres.';
        } elseif ($confirmarContrasena !== '' && $contrasena !== $confirmarContrasena) {
            $error = 'Las contraseñas no coinciden. Por favor verifícalas.';
        } elseif ($telefono === '') {
            $error = 'El número de teléfono / WhatsApp es obligatorio.';
        }

        require __DIR__ . '/../../config/database.php';
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Empresa.php';
        require_once __DIR__ . '/../Models/UsuarioAdmin.php';

        $modeloUsuario = new Usuario($conexion);
        $modeloEmpresa = new Empresa($conexion);
        $modeloAdmin   = new UsuarioAdmin($conexion);

        // Verificar si el correo ya existe en cualquier tipo de cuenta (mayor, menor, empresa o admin)
        if (!isset($error)) {
            if (!empty($modeloUsuario->buscarPorCorreo($correo)) || !empty($modeloEmpresa->buscarPorCorreo($correo)) || !empty($modeloAdmin->buscarPorCorreo($correo))) {
                $error = 'Ese correo electrónico ya se encuentra registrado en la plataforma.';
            }
        }

        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $esMenor = false;

        // Validaciones específicas según el tipo de usuario
        if (!isset($error)) {
            if ($tipo === 'empresa') {
                $representante = trim($_POST['representante'] ?? '');
                $nit = trim($_POST['nit'] ?? '');
                $descripcion = trim($_POST['descripcion_empresa'] ?? '');

                // Procesamiento del certificado / código QR ROE en formato de imagen
                $roePath = 'uploads/roe/roe_default.png';
                if (isset($_FILES['roe_archivo']) && $_FILES['roe_archivo']['error'] === UPLOAD_ERR_OK) {
                    $subido = subirArchivo('roe_archivo', 'roe', 'roe');
                    if ($subido === false) {
                        $error = 'El certificado ROE debe ser una imagen válida (JPG, PNG) y no superar los 5MB.';
                    } elseif ($subido !== null) {
                        $roePath = 'uploads/' . $subido;
                    }
                } elseif (!empty($_POST['roe_actual'])) {
                    $roePath = trim($_POST['roe_actual']);
                }

                if ($representante === '') {
                    $error = 'El nombre del representante de la empresa es obligatorio.';
                } elseif ($nit === '') {
                    $error = 'El número de NIT es obligatorio.';
                } elseif (!ctype_digit($nit) || strlen($nit) < 7 || strlen($nit) > 20) {
                    $error = 'El NIT debe ser numérico y contener al menos 7 dígitos.';
                } elseif (empty($roePath)) {
                    $error = 'La imagen o código QR de ROE es obligatorio para la empresa.';
                } else {
                    $datosEmpresa = [
                        'nombre' => $nombre,
                        'representante' => $representante,
                        'nit' => $nit,
                        'roe' => $roePath,
                        'correo' => $correo,
                        'contrasena' => $hashContrasena,
                        'telefono' => $telefono,
                        'descripcion' => $descripcion ?: null,
                        'documento_legal_path' => null,
                        'verificada' => 1,
                    ];
                }
            } else {
                // Empleado / Candidato
                $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
                if ($fechaNacimiento === '') {
                    $error = 'Ingresa tu fecha de nacimiento.';
                } else {
                    $nac = new DateTime($fechaNacimiento);
                    $hoy = new DateTime();
                    if ($nac > $hoy) {
                        $error = 'La fecha de nacimiento no puede ser una fecha futura.';
                    } else {
                        $edad = $hoy->diff($nac)->y;

                        if ($edad < 16) {
                            $error = 'Debes tener al menos 16 años para crear una cuenta en la plataforma.';
                        } elseif ($edad < 18) {
                            // Menor de 18 años -> tabla usuarios_men
                            $esMenor = true;
                            $tutorNombre = trim($_POST['tutor_nombre'] ?? '');
                            $tutorTel = trim($_POST['tutor_telefono'] ?? '');
                            $tutorRel = trim($_POST['tutor_relacion'] ?? '');

                            if ($tutorNombre === '' || $tutorTel === '') {
                                $error = 'Al tener entre 16 y 17 años, es obligatorio ingresar el nombre y teléfono de tu tutor(a) legal.';
                            } else {
                                $datosMenor = [
                                    'nombre' => $nombre,
                                    'correo' => $correo,
                                    'contrasena' => $hashContrasena,
                                    'telefono' => $telefono ?: null,
                                    'fecha_nacimiento' => $fechaNacimiento,
                                    'cv_path' => null,
                                    'permiso_tutor_path' => null,
                                ];
                                $datosTutor = [
                                    'nombre'   => $tutorNombre,
                                    'telefono' => $tutorTel,
                                    'relacion' => $tutorRel ?: 'Tutor legal',
                                ];
                            }
                        } else {
                            // Mayor de 18 años -> tabla usuarios_may
                            $datosMayor = [
                                'nombre' => $nombre,
                                'correo' => $correo,
                                'contrasena' => $hashContrasena,
                                'tipo' => 'empleado',
                                'telefono' => $telefono ?: null,
                                'fecha_nacimiento' => $fechaNacimiento,
                                'cv_path' => null,
                            ];
                        }
                    }
                }
            }
        }

        if (isset($error)) {
            vista('registro', [
                'tipo' => $tipo ?: 'empleado',
                'error' => $error,
                'form' => $_POST,
            ]);
            return;
        }

        if ($tipo === 'empresa') {
            try {
                $modeloEmpresa->crear($datosEmpresa);
                $nuevoId = (int) $conexion->lastInsertId();

                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $nuevoId;
                $_SESSION['usuario'] = $nombre;
                $_SESSION['correo'] = $correo;
                $_SESSION['tipo'] = 'empresa';
                $_SESSION['es_menor'] = 0;
                $_SESSION['representante'] = $representante ?: null;
                $_SESSION['tabla_usuario'] = 'empresas';

                header('Location: ban.php?pagina=panel_empresa');
                exit;
            } catch (PDOException $errorBase) {
                error_log('Error registro empresa: ' . $errorBase->getMessage());
                vista('registro', [
                    'tipo' => 'empresa',
                    'error' => 'No se pudo completar el registro. Verifica los datos o intenta nuevamente.',
                    'form' => $_POST,
                ]);
                return;
            }
        } else {
            // Empleado: menor en usuarios_men o mayor en usuarios_may
            if ($esMenor) {
                require_once __DIR__ . '/../Models/UsuarioMen.php';
                $modeloMen = new UsuarioMen($conexion);

                try {
                    $nuevoId = $modeloMen->crearConTutor($datosMenor, $datosTutor);

                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = $nuevoId;
                    $_SESSION['usuario'] = $nombre;
                    $_SESSION['correo'] = $correo;
                    $_SESSION['tipo'] = 'empleado';
                    $_SESSION['es_menor'] = 1;
                    $_SESSION['tabla_usuario'] = 'usuarios_men';

                    header('Location: ban.php?pagina=trabajos');
                    exit;
                } catch (PDOException $errorBase) {
                    error_log('Error registro menor: ' . $errorBase->getMessage());
                    vista('registro', [
                        'tipo' => 'empleado',
                        'error' => 'No se pudo completar el registro del menor con su tutor legal. Intenta nuevamente.',
                        'form' => $_POST,
                    ]);
                    return;
                }
            } else {
                require_once __DIR__ . '/../Models/UsuarioMay.php';
                $modeloMay = new UsuarioMay($conexion);

                try {
                    $modeloMay->crear($datosMayor);
                    $nuevoId = (int) $conexion->lastInsertId();

                    session_regenerate_id(true);
                    $_SESSION['usuario_id'] = $nuevoId;
                    $_SESSION['usuario'] = $nombre;
                    $_SESSION['correo'] = $correo;
                    $_SESSION['tipo'] = 'empleado';
                    $_SESSION['es_menor'] = 0;
                    $_SESSION['tabla_usuario'] = 'usuarios_may';

                    header('Location: ban.php?pagina=trabajos');
                    exit;
                } catch (PDOException $errorBase) {
                    error_log('Error registro mayor: ' . $errorBase->getMessage());
                    vista('registro', [
                        'tipo' => 'empleado',
                        'error' => 'No se pudo completar el registro. Verifica los datos e intenta nuevamente.',
                        'form' => $_POST,
                    ]);
                    return;
                }
            }
        }
    }
}
