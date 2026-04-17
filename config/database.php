<?php
/**
 * CONFIGURACIÓN DE BASE DE DATOS
 * Proyecto: Digitalización FCPN - Carrera de Informática
 * Stack: PHP 8+ | MySQL 5.7+ | PDO
 */

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=fcpn_informatica;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => false
        ]
    );
    
    // Configuración de zona horaria
    date_default_timezone_set('America/La_Paz');
    
} catch (PDOException $e) {
    http_response_code(500);
    die('Error de conexión a la base de datos: ' . htmlspecialchars($e->getMessage()));
}
