<?php
class Empresa
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crear($datos)
    {
        $sql = 'INSERT INTO empresas (
                    nombre, representante, nit, roe, correo, contrasena, telefono,
                    descripcion, documento_legal_path, verificada
                ) VALUES (
                    :nombre, :representante, :nit, :roe, :correo, :contrasena, :telefono,
                    :descripcion, :documento_legal_path, :verificada
                )';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute($datos);
    }

    public function buscarPorCorreo($correo)
    {
        $sql = 'SELECT * FROM empresas WHERE correo = :correo OR email = :correo LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['correo' => $correo]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = 'SELECT * FROM empresas WHERE id = :id LIMIT 1';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['id' => (int) $id]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerTodas()
    {
        $sql = 'SELECT * FROM empresas ORDER BY creada_en DESC';
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleVerificacion($id)
    {
        $sql = 'UPDATE empresas SET verificada = 1 - verificada WHERE id = :id';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute(['id' => (int) $id]);
    }

    public function eliminar($id)
    {
        $sql = 'DELETE FROM empresas WHERE id = :id';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute(['id' => (int) $id]);
    }

    public function contar()
    {
        return (int) $this->conexion->query('SELECT COUNT(*) FROM empresas')->fetchColumn();
    }
}
