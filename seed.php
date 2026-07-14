<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/conexion.php';

function q($conn, string $sql, array $params = [])
{
    return sqlsrv_query($conn, $sql, $params);
}

function fetch1($stmt)
{
    return $stmt ? sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) : null;
}

$adminRole = fetch1(q($conn, "SELECT id FROM roles WHERE nombre = ?", ['Administrador']));
$userRole  = fetch1(q($conn, "SELECT id FROM roles WHERE nombre = ?", ['Usuario']));

if (!$adminRole || !$userRole) {
    die("Primero ejecuta database.sql.");
}

$adminExists = fetch1(q($conn, "SELECT id FROM usuarios WHERE usuario = ?", ['admin']));

if (!$adminExists) {
    $hash = password_hash('Admin123*', PASSWORD_DEFAULT);

    q($conn, "INSERT INTO usuarios (rol_id, nombres, apellidos, dni, telefono, direccion, usuario, email, password, estado, activo)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Activo', 1)", [
        $adminRole['id'],
        'Administrador',
        'Sistema',
        '00000000',
        '999999999',
        'Oficina central',
        'admin',
        'admin@local.com',
        $hash
    ]);
}

$userExists = fetch1(q($conn, "SELECT id FROM usuarios WHERE usuario = ?", ['usuario1']));
if (!$userExists) {
    $hash = password_hash('Usuario123*', PASSWORD_DEFAULT);

    q($conn, "INSERT INTO usuarios (rol_id, nombres, apellidos, dni, telefono, direccion, usuario, email, password, estado, activo)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Activo', 1)", [
        $userRole['id'],
        'Juan',
        'Pérez',
        '12345678',
        '987654321',
        'Sullana',
        'usuario1',
        'usuario1@email.com',
        $hash
    ]);
}

$admin = fetch1(q($conn, "SELECT id FROM usuarios WHERE usuario = ?", ['admin']));
if ($admin) {
    $animal1 = fetch1(q($conn, "SELECT id FROM animales WHERE nombre = ?", ['Luna']));
    if (!$animal1) {
        q($conn, "INSERT INTO animales
                (nombre, especie_id, raza_id, sexo, edad_meses, tamano, color, descripcion, historia_rescate, fecha_rescate, estado_salud, esterilizado, vacunado, imagen, publicado, disponible, activo, estado, usuario_registro_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            'Luna', 2, 5, 'Hembra', 18, 'Pequeño', 'Blanco',
            'Gatita tranquila y sociable.',
            'Rescatada de una calle transitada.',
            '2025-01-12',
            'Buena',
            1, 1, null, 1, 1, 1, 'Disponible', $admin['id']
        ]);
    }

    $animal2 = fetch1(q($conn, "SELECT id FROM animales WHERE nombre = ?", ['Rocky']));
    if (!$animal2) {
        q($conn, "INSERT INTO animales
                (nombre, especie_id, raza_id, sexo, edad_meses, tamano, color, descripcion, historia_rescate, fecha_rescate, estado_salud, esterilizado, vacunado, imagen, publicado, disponible, activo, estado, usuario_registro_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
            'Rocky', 1, 2, 'Macho', 24, 'Mediano', 'Marrón',
            'Perro noble y juguetón.',
            'Fue encontrado desnutrido y ya se recuperó.',
            '2025-03-03',
            'Buena',
            1, 1, null, 1, 1, 1, 'Disponible', $admin['id']
        ]);
    }
}

echo "Seed ejecutado correctamente.";