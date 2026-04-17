<?php
/**
 * EXPORTACIÓN DE DATOS
 * Maneja exportación a CSV y PDF
 */

require_once __DIR__ . '/../config/database.php';

// Incluir modelos y controladores
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/MatriculadosModel.php';
require_once __DIR__ . '/../models/NuevosInscritosModel.php';
require_once __DIR__ . '/../models/GeneroModel.php';
require_once __DIR__ . '/../models/EstadoCivilModel.php';
require_once __DIR__ . '/../models/ColegioModel.php';
require_once __DIR__ . '/../models/SituacionLaboralModel.php';
require_once __DIR__ . '/../models/JornadaModel.php';
require_once __DIR__ . '/../models/ViviendaModel.php';
require_once __DIR__ . '/../models/EdadModel.php';
require_once __DIR__ . '/../models/PermanenciaModel.php';
require_once __DIR__ . '/../models/ResumenAnualModel.php';
require_once __DIR__ . '/../controllers/ReporteController.php';

try {
    $tabla = $_GET['tabla'] ?? '';
    $format = $_GET['format'] ?? 'csv';
    
    if (empty($tabla)) {
        http_response_code(400);
        die('Error: Tabla no especificada');
    }
    
    $reporte = new ReporteController($pdo);
    
    if ($format === 'csv') {
        $reporte->exportarCSV($tabla);
    } else {
        http_response_code(400);
        die('Formato no soportado. Solo se soporta CSV.');
    }
    
} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    die('Error al exportar: ' . htmlspecialchars($e->getMessage()));
}
