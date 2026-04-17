<?php
/**
 * API ENDPOINT JSON
 * Devuelve datos en formato JSON para gráficos dinámicos
 */

require_once __DIR__ . '/../config/database.php';
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

header('Content-Type: application/json; charset=utf-8');

try {
    $tabla = $_GET['tabla'] ?? '';
    $anio = isset($_GET['anio']) ? (int)$_GET['anio'] : null;
    
    // Tablas permitidas
    $tablas_permitidas = [
        'matriculados', 'nuevos_inscritos', 'genero', 'estado_civil',
        'colegio_procedencia', 'situacion_laboral', 'jornada_laboral',
        'vivienda', 'distribucion_edad', 'permanencia', 'resumen_anual'
    ];
    
    // Validar tabla solicitada
    if (empty($tabla) || !in_array($tabla, $tablas_permitidas)) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Tabla no permitida',
            'tablas_disponibles' => $tablas_permitidas
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Instanciar modelo correspondiente
    $modelo = match($tabla) {
        'matriculados' => new MatriculadosModel($pdo),
        'nuevos_inscritos' => new NuevosInscritosModel($pdo),
        'genero' => new GeneroModel($pdo),
        'estado_civil' => new EstadoCivilModel($pdo),
        'colegio_procedencia' => new ColegioModel($pdo),
        'situacion_laboral' => new SituacionLaboralModel($pdo),
        'jornada_laboral' => new JornadaModel($pdo),
        'vivienda' => new ViviendaModel($pdo),
        'distribucion_edad' => new EdadModel($pdo),
        'permanencia' => new PermanenciaModel($pdo),
        'resumen_anual' => new ResumenAnualModel($pdo),
    };
    
    // Obtener datos
    if ($tabla === 'resumen_anual') {
        if ($anio) {
            $datos = $modelo->getByAnio($anio);
        } else {
            $datos = $modelo->getAll();
        }
    } else {
        if ($anio) {
            $datos = $modelo->getByAnio($anio);
        } else {
            $datos = $modelo->getAll();
        }
    }
    
    // Verificar si se encontraron datos
    if (is_null($datos)) {
        http_response_code(404);
        echo json_encode([
            'error' => 'No se encontraron datos',
            'tabla' => $tabla,
            'anio' => $anio
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Devolver datos
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'tabla' => $tabla,
        'data' => $datos,
        'total' => is_array($datos) ? count($datos) : 1
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    echo json_encode([
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
