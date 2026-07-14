<?php
class Solicitud
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO solicitudes_adopcion
                (animal_id, usuario_id, motivo, experiencia, tipo_vivienda, miembros_hogar, acepta_compromiso, estado_solicitud, fecha_solicitud)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente', GETDATE())";
        return sqlsrv_query($this->conn, $sql, [
            $data['animal_id'],
            $data['usuario_id'],
            $data['motivo'],
            $data['experiencia'],
            $data['tipo_vivienda'],
            $data['miembros_hogar'],
            $data['acepta_compromiso']
        ]) !== false;
    }

    public function existsPending(int $animalId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*) AS total
                FROM solicitudes_adopcion
                WHERE animal_id = ? AND usuario_id = ? AND estado_solicitud = 'Pendiente'";
        $stmt = sqlsrv_query($this->conn, $sql, [$animalId, $usuarioId]);
        $row = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : ['total' => 0];
        return (int)$row['total'] > 0;
    }

    public function getMine(int $usuarioId): array
    {
        $sql = "SELECT s.*, a.nombre AS animal_nombre, a.imagen, e.nombre AS especie_nombre, r.nombre AS raza_nombre
                FROM solicitudes_adopcion s
                INNER JOIN animales a ON a.id = s.animal_id
                INNER JOIN especies e ON e.id = a.especie_id
                LEFT JOIN razas r ON r.id = a.raza_id
                WHERE s.usuario_id = ?
                ORDER BY s.id DESC";
        $stmt = sqlsrv_query($this->conn, $sql, [$usuarioId]);
        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getAll(): array
    {
        $sql = "SELECT s.*, 
                       a.nombre AS animal_nombre, a.imagen,
                       u.nombres + ' ' + u.apellidos AS solicitante_nombre,
                       u.email AS solicitante_email
                FROM solicitudes_adopcion s
                INNER JOIN animales a ON a.id = s.animal_id
                INNER JOIN usuarios u ON u.id = s.usuario_id
                ORDER BY s.id DESC";
        $stmt = sqlsrv_query($this->conn, $sql);
        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function approve(int $id, int $adminId, string $respuesta): bool
    {
        $stmt = sqlsrv_query($this->conn, "SELECT * FROM solicitudes_adopcion WHERE id = ?", [$id]);
        $solicitud = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : null;

        if (!$solicitud) {
            return false;
        }

        if (!sqlsrv_begin_transaction($this->conn)) {
            return false;
        }

        $ok1 = sqlsrv_query($this->conn, "UPDATE solicitudes_adopcion
                SET estado_solicitud = 'Aprobada', respuesta_admin = ?, revisado_por = ?, fecha_respuesta = GETDATE()
                WHERE id = ?", [$respuesta, $adminId, $id]);

        $ok2 = sqlsrv_query($this->conn, "UPDATE animales
                SET estado = 'Adoptado', publicado = 0, disponible = 0, actualizado_en = GETDATE()
                WHERE id = ?", [$solicitud['animal_id']]);

        $ok3 = sqlsrv_query($this->conn, "UPDATE solicitudes_adopcion
                SET estado_solicitud = 'Rechazada', respuesta_admin = 'El animal fue adoptado en otra solicitud.', revisado_por = ?, fecha_respuesta = GETDATE()
                WHERE animal_id = ? AND id <> ? AND estado_solicitud = 'Pendiente'", [
            $adminId,
            $solicitud['animal_id'],
            $id
        ]);

        $ok4 = sqlsrv_query($this->conn, "INSERT INTO historial_adopciones
                (animal_id, usuario_id, solicitud_id, fecha_adopcion, observacion)
                VALUES (?, ?, ?, GETDATE(), ?)", [
            $solicitud['animal_id'],
            $solicitud['usuario_id'],
            $id,
            $respuesta
        ]);

        if ($ok1 && $ok2 && $ok3 !== false && $ok4) {
            sqlsrv_commit($this->conn);
            return true;
        }

        sqlsrv_rollback($this->conn);
        return false;
    }

    public function reject(int $id, int $adminId, string $respuesta): bool
    {
        $sql = "UPDATE solicitudes_adopcion
                SET estado_solicitud = 'Rechazada', respuesta_admin = ?, revisado_por = ?, fecha_respuesta = GETDATE()
                WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [$respuesta, $adminId, $id]) !== false;
    }
}