<?php
class UsuarioMay
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crear($datos)
    {
        $sql = 'INSERT INTO usuarios_may (
                    nombre, correo, contrasena, tipo, telefono, fecha_nacimiento, cv_path
                ) VALUES (
                    :nombre, :correo, :contrasena, :tipo, :telefono, :fecha_nacimiento, :cv_path
                )';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute($datos);
    }

    public function buscarPorCorreo($correo)
    {
        $sql = 'SELECT * FROM usuarios_may WHERE correo = :correo LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['correo' => $correo]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = 'SELECT * FROM usuarios_may WHERE id = :id LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['id' => (int) $id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodos()
    {
        $sql = 'SELECT *, 0 AS es_menor, "mayor" AS candidato_tipo FROM usuarios_may ORDER BY fecha_registro DESC';
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorTipo($tipo)
    {
        $sql = 'SELECT *, 0 AS es_menor, "mayor" AS candidato_tipo FROM usuarios_may WHERE tipo = :tipo ORDER BY fecha_registro DESC';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['tipo' => $tipo]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = 'DELETE FROM usuarios_may WHERE id = :id';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute(['id' => (int) $id]);
    }

    public function contar()
    {
        return (int) $this->conexion->query('SELECT COUNT(*) FROM usuarios_may')->fetchColumn();
    }

    public function contarCandidatos()
    {
        return (int) $this->conexion->query("SELECT COUNT(*) FROM usuarios_may WHERE tipo = 'empleado'")->fetchColumn();
    }
}
