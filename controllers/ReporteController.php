<?php
/**
 * CONTROLADOR: Reportes
 * Gestiona reportes por dimensión con filtros y exportación
 */

class ReporteController {
    private PDO $db;
    private MatriculadosModel $matriculadosModel;
    private GeneroModel $generoModel;
    private EstadoCivilModel $estadoModel;
    private ColegioModel $colegioModel;
    private SituacionLaboralModel $laboralModel;
    private JornadaModel $jornadaModel;
    private ViviendaModel $viviendaModel;
    private EdadModel $edadModel;
    private PermanenciaModel $permanenciaModel;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->matriculadosModel = new MatriculadosModel($db);
        $this->generoModel = new GeneroModel($db);
        $this->estadoModel = new EstadoCivilModel($db);
        $this->colegioModel = new ColegioModel($db);
        $this->laboralModel = new SituacionLaboralModel($db);
        $this->jornadaModel = new JornadaModel($db);
        $this->viviendaModel = new ViviendaModel($db);
        $this->edadModel = new EdadModel($db);
        $this->permanenciaModel = new PermanenciaModel($db);
    }
    
    /**
     * Obtener datos de reporte por tabla
     * @param string $tabla Nombre de la tabla / dimensión
     * @param int|null $anioInicio Año inicial del filtro
     * @param int|null $anioFin Año final del filtro
     */
    public function getReporte(string $tabla, ?int $anioInicio = null, ?int $anioFin = null): array {
        $tablas_permitidas = [
            'matriculados', 'nuevos_inscritos', 'genero', 'estado_civil',
            'colegio_procedencia', 'situacion_laboral', 'jornada_laboral',
            'vivienda', 'distribucion_edad', 'permanencia'
        ];
        
        if (!in_array($tabla, $tablas_permitidas)) {
            return ['error' => 'Tabla no permitida'];
        }
        
        // Rango por defecto (todos los años)
        if (!$anioInicio || !$anioFin) {
            $anios = $this->getAnios();
            $anioInicio = $anios[0];
            $anioFin = $anios[count($anios) - 1];
        }
        
        // Obtener modelo correspondiente
        $modelo = match($tabla) {
            'matriculados' => $this->matriculadosModel,
            'nuevos_inscritos' => new NuevosInscritosModel($this->db),
            'genero' => $this->generoModel,
            'estado_civil' => $this->estadoModel,
            'colegio_procedencia' => $this->colegioModel,
            'situacion_laboral' => $this->laboralModel,
            'jornada_laboral' => $this->jornadaModel,
            'vivienda' => $this->viviendaModel,
            'distribucion_edad' => $this->edadModel,
            'permanencia' => $this->permanenciaModel,
            default => null
        };
        
        if (!$modelo) {
            return ['error' => 'Modelo no encontrado'];
        }
        
        return $modelo->getByRangeAnios($anioInicio, $anioFin);
    }
    
    /**
     * Exportar reporte a CSV
     */
    public function exportarCSV(string $tabla, ?int $anioInicio = null, ?int $anioFin = null): void {
        $datos = $this->getReporte($tabla, $anioInicio, $anioFin);
        
        if (isset($datos['error'])) {
            http_response_code(400);
            echo json_encode($datos);
            exit;
        }
        
        if (empty($datos)) {
            http_response_code(404);
            echo json_encode(['error' => 'No se encontraron datos']);
            exit;
        }
        
        // Preparar archivo CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $tabla . '_' . date('Ymd') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8
        
        // Headers
        if (!empty($datos)) {
            fputcsv($output, array_keys($datos[0]), ',', '"');
        }
        
        // Datos
        foreach ($datos as $row) {
            fputcsv($output, $row, ',', '"');
        }
        
        fclose($output);
    }
    
    /**
     * Obtener años disponibles
     */
    private function getAnios(): array {
        $stmt = $this->db->query('SELECT DISTINCT anio FROM anios ORDER BY anio ASC');
        return array_map(fn($row) => (int)$row['anio'], $stmt->fetchAll());
    }
    
    /**
     * Obtener tabla comparativa de todas las dimensiones para un año
     */
    public function getComparativoAnual(int $anio): ?array {
        $stmt = $this->db->prepare('SELECT * FROM v_resumen_anual WHERE anio = ?');
        $stmt->execute([$anio]);
        return $stmt->fetch() ?: null;
    }
}
