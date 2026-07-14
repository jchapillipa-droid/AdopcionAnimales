<?php
require_once __DIR__ . '/app.php';

$serverName = "ALEJANDRA";

$connectionOptions = [
    "Database" => "AdopcionAnimalesDB",
    "Uid" => "sa",
    "PWD" => "123456",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die("Error de conexión con SQL Server: <pre>" . print_r(sqlsrv_errors(), true) . "</pre>");
}