<?php
/**
 * CONTROLADOR: Dashboard
 * Gestiona la lógica del dashboard principal
 */

class DashboardController {
    private PDO $db;
    private MatriculadosModel $matriculadosModel;
    private NuevosInscritosModel $nuevosModel;
    private GeneroModel $generoModel;
    private SituacionLaboralModel $laboralModel;
    private ResumenAnualModel $resumenModel;
    private EstadoCivilModel $estadoCivilModel;
    private ColegioModel $colegioModel;
    private ViviendaModel $viviendaModel;
    private JornadaModel $jornadaModel;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->matriculadosModel = new MatriculadosModel($db);
        $this->nuevosModel = new NuevosInscritosModel($db);
        $this->generoModel = new GeneroModel($db);
        $this->laboralModel = new SituacionLaboralModel($db);
        $this->resumenModel = new ResumenAnualModel($db);
        $this->estadoCivilModel = new EstadoCivilModel($db);
        $this->colegioModel = new ColegioModel($db);
        $this->viviendaModel = new ViviendaModel($db);
        $this->jornadaModel = new JornadaModel($db);
    }
    
    /**
     * Obtener todas las métricas KPI del dashboard
     */
    public function getKPIs(): array {
        $matriculados = $this->matriculadosModel->getKPIActual();
        $nuevos = $this->nuevosModel->getKPIActual();
        $laboral = $this->laboralModel->getPctTrabajadores();
        
        // Obtener datos de género del año actual
        $genero = null;
        if ($matriculados) {
            $genero = $this->generoModel->getByAnio($matriculados['anio']);
        }
        
        return [
            'matriculados_total' => $matriculados['total'] ?? 0,
            'matriculados_anio' => $matriculados['anio'] ?? null,
            'nuevos_inscritos' => $nuevos['nuevos'] ?? 0,
            'pct_femenino' => $genero['pct_fem'] ?? 0,
            'pct_trabaja' => $laboral['pct_trabaja'] ?? 0,
        ];
    }
    
    /**
     * Obtener datos para gráfico de evolución de matrículas
     */
    public function getEvolucionMatriculas(): array {
        return $this->matriculadosModel->getEvolucion();
    }
    
    /**
     * Obtener datos para gráfico de evolución de nuevos inscritos
     */
    public function getEvolucionNuevos(): array {
        return $this->nuevosModel->getEvolucion();
    }
    
    /**
     * Obtener datos para gráfico de género por año
     */
    public function getGeneroComparativo(): array {
        return $this->generoModel->getParaGrafico();
    }
    
    /**
     * Obtener resumen desglosado del año actual
     */
    public function getResumenActual(): ?array {
        $matriculados = $this->matriculadosModel->getKPIActual();
        if (!$matriculados) {
            return null;
        }
        return $this->resumenModel->getByAnio($matriculados['anio']);
    }
    
    /**
     * Obtener todos los años disponibles en la BD
     */
    public function getAniosDisponibles(): array {
        $stmt = $this->db->query('SELECT DISTINCT anio FROM anios ORDER BY anio ASC');
        return array_map(fn($row) => $row['anio'], $stmt->fetchAll());
    }
    
    /**
     * Obtener datos para gráfico de estado civil por año
     */
    public function getEstadoCivilComparativo(): array {
        return $this->estadoCivilModel->getParaGrafico();
    }
    
    /**
     * Obtener datos para gráfico de colegio de procedencia por año
     */
    public function getColegioComparativo(): array {
        return $this->colegioModel->getParaGrafico();
    }
    
    /**
     * Obtener datos para gráfico de situación laboral por año
     */
    public function getSituacionLaboralComparativo(): array {
        return $this->laboralModel->getParaGrafico();
    }
    
    /**
     * Obtener datos para gráfico de vivienda por año
     */
    public function getViviendaComparativo(): array {
        return $this->viviendaModel->getParaGrafico();
    }
    
    /**
     * Obtener datos para gráfico de jornada laboral por año
     */
    public function getJornadaComparativo(): array {
        return $this->jornadaModel->getParaGrafico();
    }
}
