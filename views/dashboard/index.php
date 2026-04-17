<?php
/**
 * VISTA: Dashboard Principal
 */

// Instanciar controlador
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Model.php';
require_once __DIR__ . '/../../models/MatriculadosModel.php';
require_once __DIR__ . '/../../models/NuevosInscritosModel.php';
require_once __DIR__ . '/../../models/GeneroModel.php';
require_once __DIR__ . '/../../models/EstadoCivilModel.php';
require_once __DIR__ . '/../../models/ColegioModel.php';
require_once __DIR__ . '/../../models/SituacionLaboralModel.php';
require_once __DIR__ . '/../../models/ViviendaModel.php';
require_once __DIR__ . '/../../models/JornadaModel.php';
require_once __DIR__ . '/../../models/ResumenAnualModel.php';
require_once __DIR__ . '/../../controllers/DashboardController.php';

$dashboard = new DashboardController($pdo);
$kpis = $dashboard->getKPIs();
$evolucio = $dashboard->getEvolucionMatriculas();
$evolucionNuevos = $dashboard->getEvolucionNuevos();
$generoData = $dashboard->getGeneroComparativo();
$estadoCivilData = $dashboard->getEstadoCivilComparativo();
$colegioData = $dashboard->getColegioComparativo();
$laboralData = $dashboard->getSituacionLaboralComparativo();
$viviendaData = $dashboard->getViviendaComparativo();
$jornadaData = $dashboard->getJornadaComparativo();
$resumen = $dashboard->getResumenActual();

$titulo = 'Dashboard - FCPN Informática';
?>

<div class="container-xl">
    <!-- Página Título -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 text-primary">
                <i class="fas fa-chart-area"></i> Dashboard Estadístico
            </h1>
            <p class="text-muted">Datos históricos de la Carrera de Informática (1992-2023)</p>
        </div>
    </div>
    
    <!-- KPIs - Tarjetas principales -->
    <div class="row g-4 mb-5">
        <!-- KPI 1: Matriculados 2023 -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #003366;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Matriculados <?php echo $kpis['matriculados_anio']; ?></p>
                            <h3 class="card-title mb-0" style="color: #003366;">
                                <?php echo number_format($kpis['matriculados_total'], 0, ',', '.'); ?>
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
                                <?php echo number_format($kpis['nuevos_inscritos'], 0, ',', '.'); ?>
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #28a745; opacity: 0.2;">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KPI 3: Porcentaje Femenino -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #e83e8c;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Participación Femenina</p>
                            <h3 class="card-title mb-0" style="color: #e83e8c;">
                                <?php echo number_format($kpis['pct_femenino'], 1, ',', '.'); ?>%
                            </h3>
                        </div>
                        <div style="font-size: 2.5rem; color: #e83e8c; opacity: 0.2;">
                            <i class="fas fa-venus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- KPI 4: Estudiantes que Trabajan -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-text text-muted mb-1">Estudiantes que Trabajan</p>
                            <h3 class="card-title mb-0" style="color: #ffc107;">
                                <?php echo number_format($kpis['pct_trabaja'], 1, ',', '.'); ?>%
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
    
    <!-- Gráficos -->
    <div class="row g-4 mb-5">
        <!-- Gráfico 1: Evolución de Matrículas -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-line-chart"></i> Evolución de Matrículas (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEvolucion" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico 2: Evolución de Nuevos Inscritos -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus"></i> Evolución de Nuevos Inscritos (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartNuevos" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Más Gráficos - Fila con Género -->
    <div class="row g-4 mb-5">
        <!-- Gráfico 3: Comparativo de Género -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bar-chart"></i> Distribución por Género (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartGenero" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Más Gráficos - Fila 2 -->
    <div class="row g-4 mb-5">
        <!-- Gráfico 4: Estado Civil -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heart"></i> Estado Civil (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEstadoCivil" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico 5: Colegio de Procedencia -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-school"></i> Colegio de Procedencia (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartColegio" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Más Gráficos - Fila 3 -->
    <div class="row g-4 mb-5">
        <!-- Gráfico 6: Situación Laboral -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-suitcase"></i> Situación Laboral (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartLaboral" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico 7: Tipo de Vivienda -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-home"></i> Tipo de Vivienda (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartVivienda" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Más Gráficos - Fila 4 -->
    <div class="row g-4 mb-5">
        <!-- Gráfico 8: Jornada Laboral -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Jornada Laboral (1992-2023)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartJornada" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Resumen Anual -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table"></i> Resumen Consolidado <?php echo $kpis['matriculados_anio']; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <tbody>
                                <tr>
                                    <td><strong>Total Matriculados</strong></td>
                                    <td><?php echo number_format($resumen['matriculados_total'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Nuevos Inscritos</strong></td>
                                    <td><?php echo number_format($resumen['nuevos_inscritos'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Género - Masculino</strong></td>
                                    <td><?php echo number_format($resumen['masculino'] ?? 0, 0, ',', '.'); ?> (<?php echo $resumen['pct_masc'] ?? 0; ?>%)</td>
                                    <td><strong>Género - Femenino</strong></td>
                                    <td><?php echo number_format($resumen['femenino'] ?? 0, 0, ',', '.'); ?> (<?php echo $resumen['pct_fem'] ?? 0; ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado Civil - Soltero</strong></td>
                                    <td><?php echo number_format($resumen['soltero'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Estado Civil - Casado</strong></td>
                                    <td><?php echo number_format($resumen['casado'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Estudiantes que Trabajan</strong></td>
                                    <td><?php echo number_format($resumen['trabaja'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Estudiantes que No Trabajan</strong></td>
                                    <td><?php echo number_format($resumen['no_trabaja'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Colegio - Fiscal</strong></td>
                                    <td><?php echo number_format($resumen['fiscal'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Colegio - Particular</strong></td>
                                    <td><?php echo number_format($resumen['particular'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Vivienda - Propia</strong></td>
                                    <td><?php echo number_format($resumen['propia'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><strong>Vivienda - Alquilada</strong></td>
                                    <td><?php echo number_format($resumen['alquilada'] ?? 0, 0, ',', '.'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para inicializar gráficos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para gráfico de evolución
    const evolucio = <?php echo json_encode($evolucio); ?>;
    const evolucionNuevos = <?php echo json_encode($evolucionNuevos); ?>;
    const generoData = <?php echo json_encode($generoData); ?>;
    
    // Gráfico de Evolución de Matrículas
    const ctxEvolucion = document.getElementById('chartEvolucion').getContext('2d');
    new Chart(ctxEvolucion, {
        type: 'line',
        data: {
            labels: evolucio.map(d => d.anio),
            datasets: [{
                label: 'Estudiantes Matriculados',
                data: evolucio.map(d => d.total),
                borderColor: '#003366',
                backgroundColor: 'rgba(0, 51, 102, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#003366',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Número de Estudiantes'
                    }
                }
            }
        }
    });
    
    // Gráfico de Evolución de Nuevos Inscritos
    const ctxNuevos = document.getElementById('chartNuevos').getContext('2d');
    new Chart(ctxNuevos, {
        type: 'line',
        data: {
            labels: evolucionNuevos.map(d => d.anio),
            datasets: [{
                label: 'Nuevos Inscritos',
                data: evolucionNuevos.map(d => d.nuevos),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#28a745',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad de Nuevos Inscritos'
                    }
                }
            }
        }
    });
    
    // Gráfico de Género (Barras apiladas)
    const ctxGenero = document.getElementById('chartGenero').getContext('2d');
    new Chart(ctxGenero, {
        type: 'bar',
        data: {
            labels: generoData.años,
            datasets: [
                {
                    label: 'Masculino',
                    data: generoData.masculino,
                    backgroundColor: '#003366'
                },
                {
                    label: 'Femenino',
                    data: generoData.femenino,
                    backgroundColor: '#e83e8c'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Datos para gráficos adicionales
    const estadoCivilData = <?php echo json_encode($estadoCivilData); ?>;
    const colegioData = <?php echo json_encode($colegioData); ?>;
    const laboralData = <?php echo json_encode($laboralData); ?>;
    const viviendaData = <?php echo json_encode($viviendaData); ?>;
    const jornadaData = <?php echo json_encode($jornadaData); ?>;
    
    // Gráfico Estado Civil (Barras apiladas)
    const ctxEstadoCivil = document.getElementById('chartEstadoCivil').getContext('2d');
    new Chart(ctxEstadoCivil, {
        type: 'bar',
        data: {
            labels: estadoCivilData.años,
            datasets: [
                {
                    label: 'Soltero',
                    data: estadoCivilData.soltero,
                    backgroundColor: '#003366'
                },
                {
                    label: 'Casado',
                    data: estadoCivilData.casado,
                    backgroundColor: '#ffc107'
                },
                {
                    label: 'Otros',
                    data: estadoCivilData.otros,
                    backgroundColor: '#6c757d'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico Colegio de Procedencia (Barras apiladas)
    const ctxColegio = document.getElementById('chartColegio').getContext('2d');
    new Chart(ctxColegio, {
        type: 'bar',
        data: {
            labels: colegioData.años,
            datasets: [
                {
                    label: 'Fiscal',
                    data: colegioData.fiscal,
                    backgroundColor: '#17a2b8'
                },
                {
                    label: 'Particular',
                    data: colegioData.particular,
                    backgroundColor: '#28a745'
                },
                {
                    label: 'Mixto',
                    data: colegioData.mixto,
                    backgroundColor: '#6f42c1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico Situación Laboral (Barras apiladas)
    const ctxLaboral = document.getElementById('chartLaboral').getContext('2d');
    new Chart(ctxLaboral, {
        type: 'bar',
        data: {
            labels: laboralData.años,
            datasets: [
                {
                    label: 'Trabaja',
                    data: laboralData.trabaja,
                    backgroundColor: '#28a745'
                },
                {
                    label: 'No Trabaja',
                    data: laboralData.no_trabaja,
                    backgroundColor: '#dc3545'
                },
                {
                    label: 'Eventual',
                    data: laboralData.eventual,
                    backgroundColor: '#ffc107'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico Tipo de Vivienda (Barras apiladas)
    const ctxVivienda = document.getElementById('chartVivienda').getContext('2d');
    new Chart(ctxVivienda, {
        type: 'bar',
        data: {
            labels: viviendaData.años,
            datasets: [
                {
                    label: 'Propia',
                    data: viviendaData.propia,
                    backgroundColor: '#17a2b8'
                },
                {
                    label: 'Alquilada',
                    data: viviendaData.alquilada,
                    backgroundColor: '#6f42c1'
                },
                {
                    label: 'Anticrético',
                    data: viviendaData.anticretico,
                    backgroundColor: '#fd7e14'
                },
                {
                    label: 'Prestada',
                    data: viviendaData.prestada,
                    backgroundColor: '#6c757d'
                },
                {
                    label: 'Otra',
                    data: viviendaData.otra,
                    backgroundColor: '#e83e8c'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico Jornada Laboral (Barras apiladas)
    const ctxJornada = document.getElementById('chartJornada').getContext('2d');
    new Chart(ctxJornada, {
        type: 'bar',
        data: {
            labels: jornadaData.años,
            datasets: [
                {
                    label: 'Tiempo Completo',
                    data: jornadaData.tiempo_completo,
                    backgroundColor: '#003366'
                },
                {
                    label: 'Medio Tiempo',
                    data: jornadaData.medio_tiempo,
                    backgroundColor: '#17a2b8'
                },
                {
                    label: 'Eventual',
                    data: jornadaData.eventual,
                    backgroundColor: '#ffc107'
                },
                {
                    label: 'Horario',
                    data: jornadaData.horario,
                    backgroundColor: '#28a745'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
