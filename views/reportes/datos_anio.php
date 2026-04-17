<?php
/**
 * VISTA: Datos por Año Específico
 * Permite seleccionar un año y ver todos sus datos consolidados
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
require_once __DIR__ . '/../../models/ResumenAnualModel.php';

// Obtener años disponibles
$stmt = $pdo->query('SELECT DISTINCT anio FROM anios ORDER BY anio DESC');
$anios = array_map(fn($row) => $row['anio'], $stmt->fetchAll());

// Año seleccionado (por defecto el más reciente)
$anio_seleccionado = isset($_GET['anio']) ? (int)$_GET['anio'] : $anios[0];

// Validar que el año sea válido
if (!in_array($anio_seleccionado, $anios)) {
    $anio_seleccionado = $anios[0];
}

// Obtener datos consolidados del año seleccionado
$resumen = new ResumenAnualModel($pdo);
$datos_anio = $resumen->getByAnio($anio_seleccionado);

// Cargar datos de Jornada Laboral (estos datos pueden no estar en v_resumen_anual)
$jornada = new JornadaModel($pdo);
$datos_jornada = $jornada->getByAnio($anio_seleccionado);

// Si no hay datos, crear un array con valores por defecto
if (!$datos_anio) {
    $datos_anio = [
        'anio' => $anio_seleccionado,
        'matriculados_total' => 0,
        'nuevos_inscritos' => 0,
        'masculino' => 0,
        'femenino' => 0,
        'pct_masc' => 0,
        'pct_fem' => 0,
        'soltero' => 0,
        'casado' => 0,
        'otro_estado_civil' => 0,
        'fiscal' => 0,
        'particular' => 0,
        'colegio_mixto' => 0,
        'trabaja' => 0,
        'no_trabaja' => 0,
        'trabaja_eventual' => 0,
        'tiempo_completo' => 0,
        'medio_tiempo' => 0,
        'eventual' => 0,
        'horario' => 0,
        'propia' => 0,
        'alquilada' => 0,
        'anticretico' => 0,
        'prestada' => 0,
        'otra_vivienda' => 0,
        'permanencia_1_4' => 0,
        'permanencia_5_9' => 0,
        'permanencia_10_mas' => 0
    ];
}

// Agregar datos de jornada si existen
if ($datos_jornada) {
    $datos_anio['tiempo_completo'] = $datos_jornada['tiempo_completo'] ?? 0;
    $datos_anio['medio_tiempo'] = $datos_jornada['medio_tiempo'] ?? 0;
    $datos_anio['eventual'] = $datos_jornada['eventual'] ?? 0;
    $datos_anio['horario'] = $datos_jornada['horario'] ?? 0;
}

// Modelos para gráficos
$matriculados = new MatriculadosModel($pdo);
$genero = new GeneroModel($pdo);
$edad = new EdadModel($pdo);
$permanencia = new PermanenciaModel($pdo);

$titulo = 'Datos por Año - FCPN Informática';
?>

<div class="container-xl">
    <!-- Página Título -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 text-primary">
                <i class="fas fa-calendar-alt"></i> Datos por Año
            </h1>
            <p class="text-muted">Consulta todos los datos consolidados de un año específico</p>
        </div>
    </div>
    
    <!-- Selector de Año -->
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" id="formAnio">
                        <input type="hidden" name="page" value="datos_anio">
                        
                        <label class="form-label fw-bold mb-2">Seleccionar Año:</label>
                        <div class="input-group input-group-lg">
                            <select id="selectAnio" name="anio" class="form-select">
                                <?php foreach ($anios as $a): ?>
                                    <option value="<?php echo (int)$a; ?>" <?php echo (int)$anio_seleccionado === (int)$a ? 'selected' : ''; ?>>
                                        Año <?php echo (int)$a; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-eye"></i> Ver Datos
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- KPIs del Año Seleccionado -->
    <div class="row g-4 mb-5">
        <!-- KPI 1: Matriculados -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #003366;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Matriculados</p>
                            <h3 class="card-title mb-0" style="color: #003366;">
                                <?php echo number_format($datos_anio['matriculados_total'] ?? 0, 0, ',', '.'); ?>
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #003366; opacity: 0.2;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KPI 2: Nuevos Inscritos -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Nuevos Inscritos</p>
                            <h3 class="card-title mb-0" style="color: #28a745;">
                                <?php echo number_format($datos_anio['nuevos_inscritos'] ?? 0, 0, ',', '.'); ?>
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #28a745; opacity: 0.2;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KPI 3: % Femenino -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #e83e8c;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Participación Femenina</p>
                            <h3 class="card-title mb-0" style="color: #e83e8c;">
                                <?php echo number_format($datos_anio['pct_fem'] ?? 0, 1, ',', '.'); ?>%
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #e83e8c; opacity: 0.2;">
                            <i class="fas fa-venus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KPI 4: % que Trabaja -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Estudiantes que Trabajan</p>
                            <h3 class="card-title mb-0" style="color: #ffc107;">
                                <?php 
                                $total = ($datos_anio['trabaja'] ?? 0) + ($datos_anio['no_trabaja'] ?? 0) + ($datos_anio['trabaja_eventual'] ?? 0);
                                $pct = $total > 0 ? round((($datos_anio['trabaja'] ?? 0) / $total) * 100, 1) : 0;
                                echo number_format($pct, 1, ',', '.'); 
                                ?>%
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #ffc107; opacity: 0.2;">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos del Año -->
    <div class="row g-4 mb-5">
        <!-- Gráfico: Género -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-venus-mars"></i> Distribución por Género
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartGeneroAnio" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico: Estado Civil -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-ring"></i> Estado Civil
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEstadoCivilAnio" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Más Gráficos -->
    <div class="row g-4 mb-5">
        <!-- Gráfico: Colegio de Procedencia -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-school"></i> Colegio de Procedencia
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartColegioAnio" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico: Situación Laboral -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-suitcase"></i> Situación Laboral
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartLaboralAnio" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráfico: Vivienda -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-home"></i> Tipo de Vivienda
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartViviendaAnio" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico: Jornada Laboral -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Jornada Laboral
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartJornadaAnio" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Distribución de Edad (solo si es 2023) -->
    <?php if ($anio_seleccionado === 2023): ?>
    <div class="row g-4 mb-5">
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-birthday-cake"></i> Distribución por Edad (2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEdadAnio" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-hourglass-half"></i> Permanencia en la Carrera
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartPermanenciaAnio" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Tabla Detallada -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table"></i> Datos Detallados - Año <?php echo $anio_seleccionado; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Total Matriculados</strong></td>
                                    <td><?php echo number_format($datos_anio['matriculados_total'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Nuevos Inscritos</strong></td>
                                    <td><?php echo number_format($datos_anio['nuevos_inscritos'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Masculino</strong></td>
                                    <td><?php echo number_format($datos_anio['masculino'] ?? 0, 0, ',', '.'); ?> (<?php echo $datos_anio['pct_masc'] ?? 0; ?>%)</td>
                                    <td><strong>Femenino</strong></td>
                                    <td><?php echo number_format($datos_anio['femenino'] ?? 0, 0, ',', '.'); ?> (<?php echo $datos_anio['pct_fem'] ?? 0; ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Soltero</strong></td>
                                    <td><?php 
                                        $soltero = $datos_anio['soltero'] ?? 0;
                                        $total_civil = ($datos_anio['soltero'] ?? 0) + ($datos_anio['casado'] ?? 0) + ($datos_anio['otro_estado_civil'] ?? 0);
                                        $pct_soltero = $total_civil > 0 ? round(($soltero / $total_civil) * 100, 1) : 0;
                                        echo number_format($soltero, 0, ',', '.') . " ({$pct_soltero}%)";
                                    ?></td>
                                    <td><strong>Casado</strong></td>
                                    <td><?php 
                                        $casado = $datos_anio['casado'] ?? 0;
                                        $pct_casado = $total_civil > 0 ? round(($casado / $total_civil) * 100, 1) : 0;
                                        echo number_format($casado, 0, ',', '.') . " ({$pct_casado}%)";
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fiscal</strong></td>
                                    <td><?php 
                                        $fiscal = $datos_anio['fiscal'] ?? 0;
                                        $total_colegio = ($datos_anio['fiscal'] ?? 0) + ($datos_anio['particular'] ?? 0) + ($datos_anio['colegio_mixto'] ?? 0);
                                        $pct_fiscal = $total_colegio > 0 ? round(($fiscal / $total_colegio) * 100, 1) : 0;
                                        echo number_format($fiscal, 0, ',', '.') . " ({$pct_fiscal}%)";
                                    ?></td>
                                    <td><strong>Particular</strong></td>
                                    <td><?php 
                                        $particular = $datos_anio['particular'] ?? 0;
                                        $pct_particular = $total_colegio > 0 ? round(($particular / $total_colegio) * 100, 1) : 0;
                                        echo number_format($particular, 0, ',', '.') . " ({$pct_particular}%)";
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Trabaja</strong></td>
                                    <td><?php 
                                        $trabaja = $datos_anio['trabaja'] ?? 0;
                                        $total_laboral = ($datos_anio['trabaja'] ?? 0) + ($datos_anio['no_trabaja'] ?? 0) + ($datos_anio['trabaja_eventual'] ?? 0);
                                        $pct_trabaja = $total_laboral > 0 ? round(($trabaja / $total_laboral) * 100, 1) : 0;
                                        echo number_format($trabaja, 0, ',', '.') . " ({$pct_trabaja}%)";
                                    ?></td>
                                    <td><strong>No Trabaja</strong></td>
                                    <td><?php 
                                        $no_trabaja = $datos_anio['no_trabaja'] ?? 0;
                                        $pct_no_trabaja = $total_laboral > 0 ? round(($no_trabaja / $total_laboral) * 100, 1) : 0;
                                        echo number_format($no_trabaja, 0, ',', '.') . " ({$pct_no_trabaja}%)";
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Vivienda Propia</strong></td>
                                    <td><?php 
                                        $propia = $datos_anio['propia'] ?? 0;
                                        $total_vivienda = ($datos_anio['propia'] ?? 0) + ($datos_anio['alquilada'] ?? 0) + ($datos_anio['anticretico'] ?? 0) + ($datos_anio['prestada'] ?? 0) + ($datos_anio['otra_vivienda'] ?? 0);
                                        $pct_propia = $total_vivienda > 0 ? round(($propia / $total_vivienda) * 100, 1) : 0;
                                        echo number_format($propia, 0, ',', '.') . " ({$pct_propia}%)";
                                    ?></td>
                                    <td><strong>Vivienda Alquilada</strong></td>
                                    <td><?php 
                                        $alquilada = $datos_anio['alquilada'] ?? 0;
                                        $pct_alquilada = $total_vivienda > 0 ? round(($alquilada / $total_vivienda) * 100, 1) : 0;
                                        echo number_format($alquilada, 0, ',', '.') . " ({$pct_alquilada}%)";
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>T. Completo</strong></td>
                                    <td><?php 
                                        $tc = $datos_anio['tiempo_completo'] ?? 0;
                                        $total_jornada = ($datos_anio['tiempo_completo'] ?? 0) + ($datos_anio['medio_tiempo'] ?? 0) + ($datos_anio['eventual'] ?? 0) + ($datos_anio['horario'] ?? 0);
                                        $pct_tc = $total_jornada > 0 ? round(($tc / $total_jornada) * 100, 1) : 0;
                                        echo number_format($tc, 0, ',', '.') . " ({$pct_tc}%)";
                                    ?></td>
                                    <td><strong>Medio Tiempo</strong></td>
                                    <td><?php 
                                        $mt = $datos_anio['medio_tiempo'] ?? 0;
                                        $pct_mt = $total_jornada > 0 ? round(($mt / $total_jornada) * 100, 1) : 0;
                                        echo number_format($mt, 0, ',', '.') . " ({$pct_mt}%)";
                                    ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Permanencia 1-4 años</strong></td>
                                    <td><?php echo number_format($datos_anio['permanencia_1_4'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Permanencia 5-9 años</strong></td>
                                    <td><?php echo number_format($datos_anio['permanencia_5_9'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Permanencia 10+ años</strong></td>
                                    <td><?php echo number_format($datos_anio['permanencia_10_mas'] ?? 0, 0, ',', '.'); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const anio = <?php echo $anio_seleccionado; ?>;
    const datos = <?php echo json_encode($datos_anio); ?>;
    
    // Gráfico Género
    const ctxGenero = document.getElementById('chartGeneroAnio').getContext('2d');
    new Chart(ctxGenero, {
        type: 'doughnut',
        data: {
            labels: ['Masculino', 'Femenino'],
            datasets: [{
                data: [datos.masculino || 0, datos.femenino || 0],
                backgroundColor: ['#003366', '#e83e8c'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 }, padding: 15 }
                }
            }
        }
    });
    
    // Gráfico Estado Civil
    const ctxEstado = document.getElementById('chartEstadoCivilAnio').getContext('2d');
    new Chart(ctxEstado, {
        type: 'pie',
        data: {
            labels: ['Soltero', 'Casado', 'Otro'],
            datasets: [{
                data: [datos.soltero || 0, datos.casado || 0, datos.otro_estado_civil || 0],
                backgroundColor: ['#003366', '#ffc107', '#e83e8c'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 }, padding: 15 }
                }
            }
        }
    });
    
    // Gráfico Colegio
    const ctxColegio = document.getElementById('chartColegioAnio').getContext('2d');
    new Chart(ctxColegio, {
        type: 'bar',
        data: {
            labels: ['Fiscal', 'Particular', 'Mixto'],
            datasets: [{
                label: 'Estudiantes',
                data: [datos.fiscal || 0, datos.particular || 0, datos.colegio_mixto || 0],
                backgroundColor: ['#003366', '#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('es-ES');
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico Laboral
    const ctxLaboral = document.getElementById('chartLaboralAnio').getContext('2d');
    new Chart(ctxLaboral, {
        type: 'doughnut',
        data: {
            labels: ['Trabaja', 'No Trabaja', 'Eventual'],
            datasets: [{
                data: [datos.trabaja || 0, datos.no_trabaja || 0, datos.trabaja_eventual || 0],
                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 }, padding: 15 }
                }
            }
        }
    });
    
    // Gráfico Vivienda
    const ctxVivienda = document.getElementById('chartViviendaAnio').getContext('2d');
    new Chart(ctxVivienda, {
        type: 'bar',
        data: {
            labels: ['Propia', 'Alquilada', 'Anticrético', 'Prestada', 'Otra'],
            datasets: [{
                label: 'Estudiantes',
                data: [datos.propia || 0, datos.alquilada || 0, datos.anticretico || 0, datos.prestada || 0, datos.otra_vivienda || 0],
                backgroundColor: '#003366'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('es-ES');
                        }
                    }
                }
            }
        }
    });
    
    // Gráfico Jornada
    const ctxJornada = document.getElementById('chartJornadaAnio').getContext('2d');
    new Chart(ctxJornada, {
        type: 'bar',
        data: {
            labels: ['T. Completo', 'Medio Tiempo', 'Eventual', 'Horario'],
            datasets: [{
                label: 'Estudiantes',
                data: [datos.tiempo_completo || 0, datos.medio_tiempo || 0, datos.eventual || 0, datos.horario || 0],
                backgroundColor: '#e83e8c'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('es-ES');
                        }
                    }
                }
            }
        }
    });
    
    <?php if ($anio_seleccionado === 2023): ?>
    // Obtener datos de edad
    fetch('/fcpn_informatica/public/api.php?tabla=distribucion_edad&anio=<?php echo $anio_seleccionado; ?>')
        .then(r => r.json())
        .then(result => {
            if (result.success && result.data) {
                const edadData = Array.isArray(result.data) ? result.data : [result.data];
                
                // Gráfico Edad
                const ctxEdad = document.getElementById('chartEdadAnio').getContext('2d');
                new Chart(ctxEdad, {
                    type: 'bar',
                    data: {
                        labels: edadData.map(d => d.rango_edad),
                        datasets: [{
                            label: 'Estudiantes',
                            data: edadData.map(d => d.cantidad),
                            backgroundColor: '#ffc107'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('es-ES');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    
    // Obtener datos de permanencia
    fetch('/fcpn_informatica/public/api.php?tabla=permanencia&anio=<?php echo $anio_seleccionado; ?>')
        .then(r => r.json())
        .then(result => {
            if (result.success && result.data) {
                const permData = Array.isArray(result.data) ? result.data[0] : result.data;
                
                // Gráfico Permanencia
                const ctxPermanencia = document.getElementById('chartPermanenciaAnio').getContext('2d');
                new Chart(ctxPermanencia, {
                    type: 'bar',
                    data: {
                        labels: ['1 año', '2 años', '3 años', '4 años', '5-6', '7-9', '10-11', '>11'],
                        datasets: [{
                            label: 'Estudiantes',
                            data: [
                                permData.p_1anio || 0,
                                permData.p_2anios || 0,
                                permData.p_3anios || 0,
                                permData.p_4anios || 0,
                                permData.p_5a6 || 0,
                                permData.p_7a9 || 0,
                                permData.p_10a11 || 0,
                                permData.p_mas11 || 0
                            ],
                            backgroundColor: '#28a745'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('es-ES');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    <?php endif; ?>
    
    // Event listener para cambio de año
    const selectAnio = document.getElementById('selectAnio');
    const formAnio = document.getElementById('formAnio');
    
    if (selectAnio) {
        selectAnio.addEventListener('change', function() {
            // Asegurar que el valor sea un entero válido
            const anioValue = parseInt(this.value);
            if (!isNaN(anioValue)) {
                formAnio.submit();
            }
        });
    }
});
</script>
