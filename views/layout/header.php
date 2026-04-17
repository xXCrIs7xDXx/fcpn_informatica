<?php
/**
 * LAYOUT: Header
 * Cabecera común para todas las páginas
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo ?? 'FCPN - Informática'); ?></title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    
    <style>
        :root {
            --color-umsa-azul: #003366;
            --color-umsa-amarillo: #FFD700;
            --color-text: #333;
            --color-light: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: var(--color-text);
        }
        
        .navbar-custom {
            background-color: var(--color-umsa-azul);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-custom .navbar-brand {
            font-weight: bold;
            color: #fff !important;
            font-size: 1.3rem;
        }
        
        .navbar-custom .nav-link {
            color: #fff !important;
            margin-left: 1rem;
            transition: color 0.3s ease;
        }
        
        .navbar-custom .nav-link:hover {
            color: var(--color-umsa-amarillo) !important;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">
                <i class="fas fa-graduation-cap"></i> FCPN - Informática
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./?page=datos_anio">
                            <i class="fas fa-calendar-alt"></i> Datos por Año
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./?page=reportes">
                            <i class="fas fa-table"></i> Reportes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./?page=comparativo">
                            <i class="fas fa-bar-chart"></i> Comparativos
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="container-fluid py-4">
