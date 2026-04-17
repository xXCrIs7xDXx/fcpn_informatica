<?php
/**
 * MODELO: Situación Laboral
 */

class SituacionLaboralModel extends Model {
    protected string $table = 'situacion_laboral';
    
    /**
     * Obtener porcentaje de estudiantes que trabajan
     */
    public function getPctTrabajadores(): ?array {
        $stmt = $this->db->query(
            'SELECT anio, trabaja, no_trabaja, eventual, total,
                    ROUND(trabaja * 100.0 / total, 2) as pct_trabaja
             FROM situacion_laboral ORDER BY anio DESC LIMIT 1'
        );
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'trabaja' => array_map(fn($d) => $d['trabaja'], $datos),
            'no_trabaja' => array_map(fn($d) => $d['no_trabaja'], $datos),
            'eventual' => array_map(fn($d) => $d['eventual'], $datos),
        ];
    }
}
