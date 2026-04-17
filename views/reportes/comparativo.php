<?php
/**
 * VISTA: Comparativos entre Años
 * Análisis comparativo de dimensiones entre años
 */

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Model.php';
require_once __DIR__ . '/../../models/MatriculadosModel.php';
require_once __DIR__ . '/../../models/GeneroModel.php';
require_once __DIR__ . '/../../controllers/ReporteController.php';

$reporte = new ReporteController($pdo);

// Obtener años disponibles
$stmt = $pdo->query('SELECT DISTINCT anio FROM anios ORDER BY anio ASC');
$anios = array_map(fn($row) => $row['anio'], $stmt->fetchAll());

// Años seleccionados (últimos dos años por defecto)
$anio1 = isset($_GET['anio1']) ? (int)$_GET['anio1'] : $anios[count($anios) - 2];
$anio2 = isset($_GET['anio2']) ? (int)$_GET['anio2'] : $anios[count($anios) - 1];

// Obtener matriculados de ambos años
$matriculados = new MatriculadosModel($pdo);
$comparacion = $matriculados->compararAnios($anio1, $anio2);

// Obtener género
$genero = new GeneroModel($pdo);
$generos = $genero->compararGeneros($anio1, $anio2);

$titulo = 'Comparativos - FCPN Informática';
?>

<div class="container-xl">
    <!-- Página Título -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 text-primary">
                <i class="fas fa-balance-scale"></i> Análisis Comparativo
            </h1>
            <p class="text-muted">Comparar dimensiones estadísticas entre dos años</p>
        </div>
    </div>
    
    <!-- Selectores de Años -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <input type="hidden" name="page" value="comparativo">
                        
                        <div class="col-md-5">
                            <label class="form-label">Año 1 (Base):</label>
                            <select name="anio1" class="form-select">
                                <?php foreach ($anios as $a): ?>
                                    <option value="<?php echo $a; ?>" <?php echo $anio1 === $a ? 'selected' : ''; ?>>
                                        <?php echo $a; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label">Año 2 (Comparación):</label>
                            <select name="anio2" class="form-select">
                                <?php foreach ($anios as $a): ?>
                                    <option value="<?php echo $a; ?>" <?php echo $anio2 === $a ? 'selected' : ''; ?>>
                                        <?php echo $a; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Comparar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Comparación de Matrículas -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-graduation-cap"></i> Comparación de Matrículas
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($comparacion)): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Año</strong></td>
                                        <td><strong>Total Matriculados</strong></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $comparacion['anio1']; ?></td>
                                        <td><?php echo number_format($comparacion['total1'], 0, ',', '.'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $comparacion['anio2']; ?></td>
                                        <td><?php echo number_format($comparacion['total2'], 0, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>Variación:</strong><br>
                                    <i class="fas fa-arrow-right"></i> 
                                    <?php 
                                    $signo = $comparacion['diferencia'] >= 0 ? '+' : '';
                                    echo $signo . number_format($comparacion['diferencia'], 0, ',', '.') . ' estudiantes';
                                    ?>
                                    <br>
                                    <i class="fas fa-percent"></i> 
                                    <?php 
                                    $signo = $comparacion['porcentaje'] >= 0 ? '+' : '';
                                    echo $signo . $comparacion['porcentaje'] . '%';
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Gráfico Comparativo -->
                        <canvas id="chartComparativo" height="80"></canvas>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No se encontraron datos para los años seleccionados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Comparación de Género -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-venus-mars"></i> Comparación de Género
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($generos)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Año</th>
                                        <th>Masculino</th>
                                        <th>% Masculino</th>
                                        <th>Femenino</th>
                                        <th>% Femenino</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($generos as $gen): ?>
                                        <tr>
                                            <td><strong><?php echo $gen['anio']; ?></strong></td>
                                            <td><?php echo number_format($gen['masculino'], 0, ',', '.'); ?></td>
                                            <td><?php echo $gen['pct_masc']; ?>%</td>
                                            <td><?php echo number_format($gen['femenino'], 0, ',', '.'); ?></td>
                                            <td><?php echo $gen['pct_fem']; ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No se encontraron datos de género para los años seleccionados.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico Comparativo de Matrículas
    const comparacion = <?php echo json_encode($comparacion); ?>;
    if (Object.keys(comparacion).length > 0) {
        const ctxComparativo = document.getElementById('chartComparativo').getContext('2d');
        new Chart(ctxComparativo, {
            type: 'bar',
            data: {
                labels: [comparacion.anio1, comparacion.anio2],
                datasets: [{
                    label: 'Estudiantes Matriculados',
                    data: [comparacion.total1, comparacion.total2],
                    backgroundColor: ['#003366', '#e83e8c']
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
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
