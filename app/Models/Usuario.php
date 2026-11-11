<?php
require_once __DIR__ . '/UsuarioMay.php';
require_once __DIR__ . '/UsuarioMen.php';

class Usuario
{
    private $conexion;
    private $modeloMay;
    private $modeloMen;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->modeloMay = new UsuarioMay($conexion);
        $this->modeloMen = new UsuarioMen($conexion);
    }

    public function buscarPorCorreo($correo)
    {
        // Primero buscar en usuarios mayores / admin
        $usuario = $this->modeloMay->buscarPorCorreo($correo);
        if ($usuario) {
            $usuario['es_menor'] = 0;
            $usuario['candidato_tipo'] = 'mayor';
            $usuario['tabla_origen'] = 'usuarios_may';
            return $usuario;
        }

        // Si no está, buscar en usuarios menores
        $usuario = $this->modeloMen->buscarPorCorreo($correo);
        if ($usuario) {
            $usuario['tipo'] = 'empleado';
            $usuario['es_menor'] = 1;
            $usuario['candidato_tipo'] = 'menor';
            $usuario['tabla_origen'] = 'usuarios_men';
            return $usuario;
        }

        return null;
    }

    public function obtenerPorId($id, $candidatoTipo = null)
    {
        if ($candidatoTipo === 'menor') {
            $res = $this->modeloMen->obtenerPorId($id);
            if ($res) {
                $res['tipo'] = 'empleado';
                $res['es_menor'] = 1;
                $res['candidato_tipo'] = 'menor';
            }
            return $res;
        }

        if ($candidatoTipo === 'mayor') {
            $res = $this->modeloMay->obtenerPorId($id);
            if ($res) {
                $res['es_menor'] = 0;
                $res['candidato_tipo'] = 'mayor';
            }
            return $res;
        }

        // Si no se especifica el tipo, buscar primero en mayores y luego en menores
        $res = $this->modeloMay->obtenerPorId($id);
        if ($res) {
            $res['es_menor'] = 0;
            $res['candidato_tipo'] = 'mayor';
            return $res;
        }

        $res = $this->modeloMen->obtenerPorId($id);
        if ($res) {
            $res['tipo'] = 'empleado';
            $res['es_menor'] = 1;
            $res['candidato_tipo'] = 'menor';
        }
        return $res;
    }

    public function obtenerTodos()
    {
        $mayores = $this->modeloMay->obtenerTodos();
        $menores = $this->modeloMen->obtenerTodos();
        return array_merge($mayores, $menores);
    }

    public function obtenerPorTipo($tipo)
    {
        if ($tipo === 'admin') {
            return $this->modeloMay->obtenerPorTipo('admin');
        }

        $mayores = $this->modeloMay->obtenerPorTipo('empleado');
        $menores = $this->modeloMen->obtenerTodos();
        return array_merge($mayores, $menores);
    }

    public function contarCandidatos()
    {
        return $this->modeloMay->contarCandidatos() + $this->modeloMen->contar();
    }
}
