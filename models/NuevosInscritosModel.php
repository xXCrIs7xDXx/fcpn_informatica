<?php
/**
 * MODELO: Nuevos Inscritos
 */

class NuevosInscritosModel extends Model {
    protected string $table = 'nuevos_inscritos';
    
    /**
     * Obtener KPI del año actual
     */
    public function getKPIActual(): ?array {
        $stmt = $this->db->query(
            'SELECT anio, nuevos FROM nuevos_inscritos ORDER BY anio DESC LIMIT 1'
        );
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Obtener evolución histórica de nuevos inscritos por año (1992-2023)
     */
    public function getEvolucion(): array {
        $stmt = $this->db->query('SELECT anio, nuevos FROM nuevos_inscritos ORDER BY anio ASC');
        return $stmt->fetchAll();
    }
}
