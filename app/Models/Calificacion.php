<?php
class Calificacion
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function guardar($empresaId, $candidatoId, $candidatoTipo = 'mayor', $puntuacion, $comentario = null)
    {
        $sql = 'INSERT INTO calificaciones (empresa_id, candidato_id, candidato_tipo, puntuacion, comentario, creado_en)
                VALUES (:empresa_id, :candidato_id, :candidato_tipo, :puntuacion, :comentario, NOW())
                ON DUPLICATE KEY UPDATE 
                    puntuacion = VALUES(puntuacion), 
                    comentario = VALUES(comentario), 
                    creado_en = NOW()';
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            'empresa_id'     => (int) $empresaId,
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
            'puntuacion'     => (int) $puntuacion,
            'comentario'     => $comentario,
        ]);
    }

    public function obtenerPorCandidato($candidatoId, $candidatoTipo = 'mayor')
    {
        $sql = 'SELECT c.*, emp.nombre AS empresa_nombre
                FROM calificaciones c
                INNER JOIN empresas emp ON emp.id = c.empresa_id
                WHERE c.candidato_id = :candidato_id 
                  AND c.candidato_tipo = :candidato_tipo';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            'candidato_id'   => (int) $candidatoId,
            'candidato_tipo' => $candidatoTipo === 'menor' ? 'menor' : 'mayor',
        ]);
        $rows = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $calificaciones = [];
        foreach ($rows as $r) {
            $calificaciones[$r['empresa_id']] = $r;
        }
        return $calificaciones;
    }

    public function obtenerPorEmpresa($empresaId)
    {
        $sql = 'SELECT c.*, 
                       COALESCE(um.nombre, un.nombre) AS candidato_nombre
                FROM calificaciones c
                LEFT JOIN usuarios_may um ON (um.id = c.candidato_id AND c.candidato_tipo = "mayor")
                LEFT JOIN usuarios_men un ON (un.id = c.candidato_id AND c.candidato_tipo = "menor")
                WHERE c.empresa_id = :empresa_id
                ORDER BY c.creado_en DESC';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['empresa_id' => (int) $empresaId]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPromedioPorEmpresa($empresaId)
    {
        $sql = 'SELECT ROUND(AVG(puntuacion), 1) as promedio, COUNT(*) as total
                FROM calificaciones
                WHERE empresa_id = :empresa_id';
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['empresa_id' => (int) $empresaId]);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}
