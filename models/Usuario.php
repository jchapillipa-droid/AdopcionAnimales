<?php
class Usuario
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getByUsuarioOrEmail(string $valor): ?array
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.usuario = ? OR u.email = ?";
        $stmt = sqlsrv_query($this->conn, $sql, [$valor, $valor]);
        return $stmt ? (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null) : null;
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.id = ?";
        $stmt = sqlsrv_query($this->conn, $sql, [$id]);
        return $stmt ? (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ?: null) : null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO usuarios (rol_id, nombres, apellidos, dni, telefono, direccion, usuario, email, password, estado, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Activo', 1)";
        return sqlsrv_query($this->conn, $sql, [
            $data['rol_id'],
            $data['nombres'],
            $data['apellidos'],
            $data['dni'],
            $data['telefono'],
            $data['direccion'],
            $data['usuario'],
            $data['email'],
            $data['password']
        ]) !== false;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE usuarios
                SET nombres = ?, apellidos = ?, dni = ?, telefono = ?, direccion = ?, email = ?
                WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [
            $data['nombres'],
            $data['apellidos'],
            $data['dni'],
            $data['telefono'],
            $data['direccion'],
            $data['email'],
            $id
        ]) !== false;
    }

    public function getAll(): array
    {
        $sql = "SELECT u.*, r.nombre AS rol_nombre
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.rol_id
                ORDER BY u.id DESC";
        $stmt = sqlsrv_query($this->conn, $sql);
        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function roles(): array
    {
        $stmt = sqlsrv_query($this->conn, "SELECT * FROM roles ORDER BY nombre");
        $rows = [];
        while ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function existsUsuario(string $usuario, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE usuario = ?";
        $params = [$usuario];
        if ($excludeId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $excludeId;
        }
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $row = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : ['total' => 0];
        return (int)$row['total'] > 0;
    }

    public function existsEmail(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE email = ?";
        $params = [$email];
        if ($excludeId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $excludeId;
        }
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $row = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : ['total' => 0];
        return (int)$row['total'] > 0;
    }

    public function existsDni(string $dni, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE dni = ?";
        $params = [$dni];
        if ($excludeId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $excludeId;
        }
        $stmt = sqlsrv_query($this->conn, $sql, $params);
        $row = $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : ['total' => 0];
        return (int)$row['total'] > 0;
    }

    public function updateRole(int $id, int $rol_id, string $estado): bool
    {
        $sql = "UPDATE usuarios SET rol_id = ?, estado = ? WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [$rol_id, $estado, $id]) !== false;
    }

    public function deactivate(int $id): bool
    {
        $sql = "UPDATE usuarios SET estado = 'Inactivo', activo = 0 WHERE id = ?";
        return sqlsrv_query($this->conn, $sql, [$id]) !== false;
    }
}