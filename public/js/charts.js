/**
 * FUNCIONES Y UTILIDADES JAVASCRIPT
 * Manejo de gráficos, validaciones y eventos
 */

// Colores institucionales
const COLORES = {
    azul: '#003366',
    amarillo: '#FFD700',
    rosa: '#e83e8c',
    verde: '#28a745',
    naranja: '#fd7e14',
    rojo: '#dc3545'
};

/**
 * Inicializar gráfico de línea
 */
function initChartLinea(canvasId, labels, data, titulo) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: titulo,
                data: data,
                borderColor: COLORES.azul,
                backgroundColor: 'rgba(0, 51, 102, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: COLORES.azul,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15
                    }
                }
            },
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

/**
 * Inicializar gráfico de barras
 */
function initChartBarras(canvasId, labels, datasets, titulo) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15
                    }
                }
            },
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

/**
 * Inicializar gráfico de barras apiladas
 */
function initChartBarrasApiladas(canvasId, labels, datasets) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
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

/**
 * Inicializar gráfico de pastel/donut
 */
function initChartPastel(canvasId, labels, data, titulo) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    const colores = [
        COLORES.azul,
        COLORES.rosa,
        COLORES.verde,
        COLORES.naranja,
        COLORES.rojo,
        COLORES.amarillo
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colores.slice(0, labels.length),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { size: 12 },
                        padding: 15
                    }
                }
            }
        }
    });
}

/**
 * Cargar datos de API y crear gráfico
 */
async function cargarDatosAPI(tabla, callback) {
    try {
        const response = await fetch('/api.php?tabla=' + tabla);
        const resultado = await response.json();
        
        if (resultado.success) {
            callback(resultado.data);
        } else {
            console.error('Error:', resultado.error);
        }
    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
}

/**
 * Formatear número como moneda o cantidad
 */
function formatearNumero(numero) {
    return new Intl.NumberFormat('es-ES').format(numero);
}

/**
 * Formatear porcentaje
 */
function formatearPorcentaje(valor, decimales = 2) {
    return (valor ?? 0).toFixed(decimales) + '%';
}

/**
 * Mostrar/Ocultar cargando
 */
function mostrarCargando(elemento) {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-border text-primary';
    spinner.setAttribute('role', 'status');
    spinner.innerHTML = '<span class="visually-hidden">Cargando...</span>';
    elemento.appendChild(spinner);
}

/**
 * Evento de cambio de tabla en reportes
 */
function cambiarTabla() {
    const tabla = document.getElementById('selectTabla').value;
    window.location.href = '?page=reportes&tabla=' + tabla;
}

/**
 * Exportar a CSV
 */
function exportarCSV() {
    const tabla = document.getElementById('selectTabla')?.value || '';
    if (tabla) {
        window.location.href = '/export.php?tabla=' + tabla + '&format=csv';
    }
}

/**
 * Imprimir tabla
 */
function imprimirTabla() {
    window.print();
}

/**
 * Validar formulario
 */
function validarFormulario(formularioId) {
    const formulario = document.getElementById(formularioId);
    if (!formulario) return false;
    
    return formulario.checkValidity() === false ? false : true;
}

/**
 * Inicializar tooltips de Bootstrap
 */
function inicializarTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Inicializar popovers de Bootstrap
 */
function inicializarPopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Ejecutar cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    inicializarTooltips();
    inicializarPopovers();
});

// Función auxiliar para obtener parámetros de URL
function getParametroURL(nombre) {
    const params = new URLSearchParams(window.location.search);
    return params.get(nombre);
}

// Agregar estilos dinámicos
function agregarEstilo(css) {
    const style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);
}
