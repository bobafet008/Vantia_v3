<?php
class UsuarioAdmin
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorCorreo($correo)
    {
        $sql = 'SELECT * FROM usuarios_admin WHERE correo = :correo AND activo = 1 LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['correo' => $correo]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = 'SELECT * FROM usuarios_admin WHERE id = :id LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['id' => (int) $id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos()
    {
        $sql = 'SELECT * FROM usuarios_admin ORDER BY fecha_registro DESC';
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($datos)
    {
        $sql = 'INSERT INTO usuarios_admin (nombre, correo, contrasena, telefono, cargo, activo)
                VALUES (:nombre, :correo, :contrasena, :telefono, :cargo, :activo)';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute($datos);
    }

    public function contar()
    {
        return (int) $this->conexion->query('SELECT COUNT(*) FROM usuarios_admin WHERE activo = 1')->fetchColumn();
    }
}
