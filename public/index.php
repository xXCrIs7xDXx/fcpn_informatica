<?php
/**
 * ROUTER PRINCIPAL
 * Enrutador de la aplicación - Punto de entrada
 */

// Incluir configuración
require_once __DIR__ . '/../config/database.php';

// Incluir todos los modelos
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

// Incluir controladores
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/ReporteController.php';

// Determinar página solicitada
$page = $_GET['page'] ?? 'dashboard';

// Iniciar sesión de output buffering
ob_start();

// Incluir header
include __DIR__ . '/../views/layout/header.php';

// Enrutamiento
switch ($page) {
    case 'dashboard':
    case 'home':
    case '':
        include __DIR__ . '/../views/dashboard/index.php';
        break;
    
    case 'reportes':
        include __DIR__ . '/../views/reportes/index.php';
        break;
    
    case 'comparativo':
        include __DIR__ . '/../views/reportes/comparativo.php';
        break;
    
    case 'datos_anio':
        include __DIR__ . '/../views/reportes/datos_anio.php';
        break;
    
    default:
        echo '<div class="container"><div class="alert alert-danger">Página no encontrada</div></div>';
}

// Incluir footer
include __DIR__ . '/../views/layout/footer.php';

// Limpiar buffer y enviar
ob_end_flush();
