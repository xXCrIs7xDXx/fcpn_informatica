<?php
/**
 * VISTA: Reportes por Dimensión
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Model.php';
require_once __DIR__ . '/../../models/MatriculadosModel.php';
require_once __DIR__ . '/../../models/NuevosInscritosModel.php';
require_once __DIR__ . '/../../models/GeneroModel.php';
require_once __DIR__ . '/../../models/EstadoCivilModel.php';
require_once __DIR__ . '/../../models/ColegioModel.php';
require_once __DIR__ . '/../../models/SituacionLaboralModel.php';
require_once __DIR__ . '/../../models/JornadaModel.php';
require_once __DIR__ . '/../../models/ViviendaModel.php';
require_once __DIR__ . '/../../models/EdadModel.php';
require_once __DIR__ . '/../../models/PermanenciaModel.php';
require_once __DIR__ . '/../../controllers/ReporteController.php';

$reporte = new ReporteController($pdo);
$tabla_seleccionada = $_GET['tabla'] ?? 'matriculados';
$anio_filtro = isset($_GET['anio']) ? (int)$_GET['anio'] : null;

// Obtener datos
$datos = $reporte->getReporte($tabla_seleccionada, $anio_filtro, $anio_filtro);

// Mapeo de etiquetas amigables
$etiquetas = [
    'matriculados' => 'Estudiantes Matriculados',
    'nuevos_inscritos' => 'Nuevos Inscritos',
    'genero' => 'Distribución por Género',
    'estado_civil' => 'Estado Civil',
    'colegio_procedencia' => 'Colegio de Procedencia',
    'situacion_laboral' => 'Situación Laboral',
    'jornada_laboral' => 'Jornada Laboral',
    'vivienda' => 'Tipo de Vivienda',
    'distribucion_edad' => 'Distribución por Edad',
    'permanencia' => 'Permanencia en la Carrera'
];

$titulo = 'Reportes - FCPN Informática';
?>

<div class="container-xl">
    <!-- Página Título -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 text-primary">
                <i class="fas fa-table"></i> Reportes por Dimensión
            </h1>
            <p class="text-muted">Consulta detallada de datos por cada variable estadística</p>
        </div>
    </div>
    
    <!-- Selectores de Filtro -->
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Seleccionar Dimensión:</label>
                    <select id="selectTabla" class="form-select" onchange="cambiarTabla()">
                        <?php foreach ($etiquetas as $key => $label): ?>
                            <option value="<?php echo $key; ?>" <?php echo $tabla_seleccionada === $key ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Opciones:</label>
                    <div class="btn-group w-100" role="group">
                        <a href="?page=reportes&tabla=<?php echo $tabla_seleccionada; ?>" 
                           class="btn btn-sm btn-outline-primary" title="Todos los años">
                            <i class="fas fa-calendar"></i> Todos los Años
                        </a>
                        <button class="btn btn-sm btn-outline-success" onclick="exportarCSV()">
                            <i class="fas fa-download"></i> Exportar CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Datos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <?php echo htmlspecialchars($etiquetas[$tabla_seleccionada]); ?> - 
                        <small class="text-muted"><?php echo count($datos); ?> registros encontrados</small>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($datos) && !isset($datos['error'])): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <?php foreach (array_keys($datos[0]) as $columna): ?>
                                            <th><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $columna))); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datos as $fila): ?>
                                        <tr>
                                            <?php foreach ($fila as $valor): ?>
                                                <td>
                                                    <?php 
                                                    // Formatear números
                                                    if (is_numeric($valor) && !is_string($valor)) {
                                                        echo number_format($valor, 2, ',', '.');
                                                    } else {
                                                        echo htmlspecialchars((string)$valor);
                                                    }
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-circle"></i> 
                            No se encontraron datos para la dimensión seleccionada.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cambiarTabla() {
    const tabla = document.getElementById('selectTabla').value;
    window.location.href = '?page=reportes&tabla=' + tabla;
}

function exportarCSV() {
    const tabla = document.getElementById('selectTabla').value;
    window.location.href = '/export.php?tabla=' + tabla + '&format=csv';
}
</script>
