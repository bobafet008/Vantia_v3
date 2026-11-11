<?php
// Configuración de conexión para XAMPP y base de datos unificada Vantia
$dbHost = 'localhost';
$dbName = 'vantia_empleabilidad_v2';
$dbUser = 'root';
$dbPass = '';

try {
    $opciones = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $conexion = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, $opciones);
} catch (PDOException $error) {
    error_log('Error de conexión Vantia: ' . $error->getMessage());
    http_response_code(500);
    exit('No se pudo conectar con MySQL. Verifica que MySQL esté activo en XAMPP y que la base de datos ' . $dbName . ' exista.');
}
