<?php
require_once __DIR__ . '/Tutor.php';

class UsuarioMen
{
    private $conexion;
    private $modeloTutor;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
        $this->modeloTutor = new Tutor($conexion);
    }

    public function crear($datos)
    {
        $sql = 'INSERT INTO usuarios_men (
                    nombre, correo, contrasena, telefono, fecha_nacimiento, cv_path, permiso_tutor_path
                ) VALUES (
                    :nombre, :correo, :contrasena, :telefono, :fecha_nacimiento, :cv_path, :permiso_tutor_path
                )';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute($datos);
    }

    public function crearConTutor($datosMenor, $datosTutor)
    {
        $this->crear($datosMenor);
        $usuarioMenId = (int) $this->conexion->lastInsertId();

        $this->modeloTutor->crear(
            $usuarioMenId,
            $datosTutor['nombre'] ?? '',
            $datosTutor['telefono'] ?? '',
            $datosTutor['relacion'] ?? 'Tutor legal'
        );

        return $usuarioMenId;
    }

    public function buscarPorCorreo($correo)
    {
        $sql = 'SELECT un.*, 
                       t.id AS tutor_id,
                       t.nombre AS tutor_nombre, 
                       t.telefono AS tutor_telefono, 
                       t.relacion AS tutor_relacion
                FROM usuarios_men un
                LEFT JOIN tutores t ON t.usuario_men_id = un.id
                WHERE un.correo = :correo 
                LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['correo' => $correo]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = 'SELECT un.*, 
                       t.id AS tutor_id,
                       t.nombre AS tutor_nombre, 
                       t.telefono AS tutor_telefono, 
                       t.relacion AS tutor_relacion
                FROM usuarios_men un
                LEFT JOIN tutores t ON t.usuario_men_id = un.id
                WHERE un.id = :id 
                LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['id' => (int) $id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos()
    {
        $sql = 'SELECT un.*, 
                       t.id AS tutor_id,
                       t.nombre AS tutor_nombre, 
                       t.telefono AS tutor_telefono, 
                       t.relacion AS tutor_relacion,
                       1 AS es_menor, 
                       "menor" AS candidato_tipo
                FROM usuarios_men un
                LEFT JOIN tutores t ON t.usuario_men_id = un.id
                ORDER BY un.fecha_registro DESC';
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = 'DELETE FROM usuarios_men WHERE id = :id';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute(['id' => (int) $id]);
    }

    public function contar()
    {
        return (int) $this->conexion->query('SELECT COUNT(*) FROM usuarios_men')->fetchColumn();
    }
}
