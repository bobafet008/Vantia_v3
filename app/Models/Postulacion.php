<?php
class Postulacion
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crear($empleoId, $candidatoId, $candidatoTipo = 'mayor', $mensaje = null)
    {
        $sql = 'INSERT INTO postulaciones (empleo_id, candidato_id, candidato_tipo, mensaje, estado)
                VALUES (:empleo_id, :candidato_id, :candidato_tipo, :mensaje, "pendiente")';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            'empleo_id'      => (int) $empleoId,
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
            'mensaje'        => $mensaje,
        ]);
    }

    public function yaPostulado($empleoId, $candidatoId, $candidatoTipo = 'mayor')
    {
        $sql = 'SELECT COUNT(*) FROM postulaciones 
                WHERE empleo_id = :empleo_id 
                  AND candidato_id = :candidato_id 
                  AND candidato_tipo = :candidato_tipo';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            'empleo_id'      => (int) $empleoId,
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
        ]);
        return (int) $consulta->fetchColumn() > 0;
    }

    public function obtenerIdsPostuladosPorCandidato($candidatoId, $candidatoTipo = 'mayor')
    {
        $sql = 'SELECT empleo_id FROM postulaciones 
                WHERE candidato_id = :candidato_id 
                  AND candidato_tipo = :candidato_tipo';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
        ]);
        return $consulta->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obtenerPorEmpresa($empresaId)
    {
        $sql = 'SELECT p.*, e.titulo AS empleo_titulo, e.categoria, e.tipo AS empleo_tipo,
                       COALESCE(um.nombre, un.nombre) AS candidato_nombre,
                       COALESCE(um.correo, un.correo) AS candidato_correo,
                       COALESCE(um.telefono, un.telefono) AS candidato_telefono,
                       COALESCE(um.fecha_nacimiento, un.fecha_nacimiento) AS fecha_nacimiento,
                       CASE WHEN p.candidato_tipo = "menor" THEN 1 ELSE 0 END AS es_menor,
                       t.nombre AS tutor_nombre, t.telefono AS tutor_telefono, t.relacion AS tutor_relacion
                FROM postulaciones p
                INNER JOIN empleos e ON e.id = p.empleo_id
                LEFT JOIN usuarios_may um ON (um.id = p.candidato_id AND p.candidato_tipo = "mayor")
                LEFT JOIN usuarios_men un ON (un.id = p.candidato_id AND p.candidato_tipo = "menor")
                LEFT JOIN tutores t ON t.usuario_men_id = un.id
                WHERE e.empresa_id = :empresa_id
                ORDER BY p.fecha_postulacion DESC';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['empresa_id' => (int) $empresaId]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCandidato($candidatoId, $candidatoTipo = 'mayor')
    {
        $sql = 'SELECT p.*, e.titulo AS empleo_titulo, e.categoria, e.tipo AS empleo_tipo,
                       e.salario, e.ubicacion, emp.id AS empresa_id, emp.nombre AS empresa_nombre,
                       emp.nit AS empresa_nit, emp.roe AS empresa_roe, 
                       emp.representante AS empresa_representante, emp.verificada AS empresa_verificada
                FROM postulaciones p
                INNER JOIN empleos e ON e.id = p.empleo_id
                INNER JOIN empresas emp ON emp.id = e.empresa_id
                WHERE p.candidato_id = :candidato_id 
                  AND p.candidato_tipo = :candidato_tipo
                ORDER BY p.fecha_postulacion DESC';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
        ]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cambiarEstado($postulacionId, $nuevoEstado)
    {
        $estadosValidos = ['pendiente', 'revision', 'entrevista', 'contratado', 'rechazado'];
        if (!in_array($nuevoEstado, $estadosValidos, true)) {
            return false;
        }
        $sql = 'UPDATE postulaciones SET estado = :estado WHERE id = :id';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            'estado' => $nuevoEstado,
            'id' => (int) $postulacionId,
        ]);
    }

    public function contarPorEstado()
    {
        $sql = 'SELECT estado, COUNT(*) as total FROM postulaciones GROUP BY estado';
        return $this->conexion->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
