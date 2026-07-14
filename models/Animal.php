<?php
class Animal
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function especies(): array
    {
        $stmt = sqlsrv_query($this->conn, "SELECT * FROM especies ORDER BY nombre");
        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function razas(): array
    {
        $sql = "SELECT r.*, e.nombre AS especie_nombre
                FROM razas r
                INNER JOIN especies e ON e.id = r.especie_id
                ORDER BY e.nombre, r.nombre";
        $stmt = sqlsrv_query($this->conn, $sql);

        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function featuredPublic(int $limit = 6): array
    {
        $limit = max(1, (int)$limit);

        $sql = "SELECT TOP ($limit)
                    a.*, e.nombre AS especie_nombre, r.nombre AS raza_nombre
                FROM animales a
                INNER JOIN especies e ON e.id = a.especie_id
                LEFT JOIN razas r ON r.id = a.raza_id
                WHERE a.publicado = 1 AND a.estado = 'Disponible' AND a.activo = 1
                ORDER BY a.id DESC";

        $stmt = sqlsrv_query($this->conn, $sql);

        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function getAllPublic(array $filtros = []): array
    {
        $sql = "SELECT a.*, e.nombre AS especie_nombre, r.nombre AS raza_nombre
                FROM animales a
                INNER JOIN especies e ON e.id = a.especie_id
                LEFT JOIN razas r ON r.id = a.raza_id
                WHERE a.publicado = 1 AND a.estado = 'Disponible' AND a.activo = 1";
        $params = [];

        if (!empty($filtros['buscar'])) {
            $sql .= " AND (a.nombre LIKE ? OR e.nombre LIKE ? OR ISNULL(r.nombre,'') LIKE ?)";
            $like = '%' . $filtros['buscar'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if (!empty($filtros['especie_id'])) {
            $sql .= " AND a.especie_id = ?";
            $params[] = (int)$filtros['especie_id'];
        }

        if (!empty($filtros['sexo'])) {
            $sql .= " AND a.sexo = ?";
            $params[] = $filtros['sexo'];
        }

        if (!empty($filtros['tamano'])) {
            $sql .= " AND a.tamano = ?";
            $params[] = $filtros['tamano'];
        }

        if (!empty($filtros['estado'])) {
            $sql .= " AND a.estado = ?";
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['edad_min'])) {
            $sql .= " AND a.edad_meses >= ?";
            $params[] = (int)$filtros['edad_min'];
        }

        if (!empty($filtros['edad_max'])) {
            $sql .= " AND a.edad_meses <= ?";
            $params[] = (int)$filtros['edad_max'];
        }

        $sql .= " ORDER BY a.id DESC";

        $stmt = sqlsrv_query($this->conn, $sql, $params);

        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getAllAdmin(): array
    {
        $sql = "SELECT a.*, e.nombre AS especie_nombre, r.nombre AS raza_nombre, u.nombres + ' ' + u.apellidos AS registrado_por
                FROM animales a
                INNER JOIN especies e ON e.id = a.especie_id
                LEFT JOIN razas r ON r.id = a.raza_id
                INNER JOIN usuarios u ON u.id = a.usuario_registro_id
                ORDER BY a.id DESC";

        $stmt = sqlsrv_query($this->conn, $sql);

        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT a.*, e.nombre AS especie_nombre, r.nombre AS raza_nombre, u.nombres + ' ' + u.apellidos AS registrado_por
                FROM animales a
                INNER JOIN especies e ON e.id = a.especie_id
                LEFT JOIN razas r ON r.id = a.raza_id
                INNER JOIN usuarios u ON u.id = a.usuario_registro_id
                WHERE a.id = ?";

        $stmt = sqlsrv_query($this->conn, $sql, [$id]);
        return $stmt ? (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null) : null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO animales
                (nombre, especie_id, raza_id, sexo, edad_meses, tamano, color, descripcion, historia_rescate, fecha_rescate, estado_salud, esterilizado, vacunado, imagen, publicado, disponible, activo, estado, usuario_registro_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return sqlsrv_query($this->conn, $sql, [
            $data['nombre'],
            $data['especie_id'],
            $data['raza_id'],
            $data['sexo'],
            $data['edad_meses'],
            $data['tamano'],
            $data['color'],
            $data['descripcion'],
            $data['historia_rescate'],
            $data['fecha_rescate'],
            $data['estado_salud'],
            $data['esterilizado'],
            $data['vacunado'],
            $data['imagen'],
            $data['publicado'],
            $data['disponible'],
            $data['activo'],
            $data['estado'],
            $data['usuario_registro_id']
        ]) !== false;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE animales
                SET nombre = ?, especie_id = ?, raza_id = ?, sexo = ?, edad_meses = ?, tamano = ?, color = ?, descripcion = ?, historia_rescate = ?, fecha_rescate = ?, estado_salud = ?, esterilizado = ?, vacunado = ?, imagen = ISNULL(?, imagen), publicado = ?, disponible = ?, activo = ?, estado = ?, actualizado_en = GETDATE()
                WHERE id = ?";

        return sqlsrv_query($this->conn, $sql, [
            $data['nombre'],
            $data['especie_id'],
            $data['raza_id'],
            $data['sexo'],
            $data['edad_meses'],
            $data['tamano'],
            $data['color'],
            $data['descripcion'],
            $data['historia_rescate'],
            $data['fecha_rescate'],
            $data['estado_salud'],
            $data['esterilizado'],
            $data['vacunado'],
            $data['imagen'],
            $data['publicado'],
            $data['disponible'],
            $data['activo'],
            $data['estado'],
            $id
        ]) !== false;
    }

    public function deactivate(int $id): bool
    {
        $sql = "UPDATE animales SET activo = 0, publicado = 0, disponible = 0, estado = 'Eliminado', actualizado_en = GETDATE() WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [$id]) !== false;
    }

    public function togglePublic(int $id, int $publicado): bool
    {
        $sql = "UPDATE animales SET publicado = ?, actualizado_en = GETDATE() WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [$publicado, $id]) !== false;
    }
}