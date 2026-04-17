<?php
/**
 * MODELO: Género
 * Distribución por sexo
 */

class GeneroModel extends Model {
    protected string $table = 'genero';
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'masculino' => array_map(fn($d) => $d['masculino'], $datos),
            'femenino' => array_map(fn($d) => $d['femenino'], $datos),
        ];
    }
    
    /**
     * Obtener tendencia de participación femenina
     */
    public function getTendenciaFemenina(): array {
        $stmt = $this->db->query(
            'SELECT anio, pct_fem, femenino, total FROM genero ORDER BY anio ASC'
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Comparar proporción de género entre dos años
     */
    public function compararGeneros(int $anio1, int $anio2): array {
        $stmt = $this->db->prepare(
            'SELECT anio, masculino, femenino, pct_masc, pct_fem FROM genero WHERE anio IN (?, ?) ORDER BY anio'
        );
        $stmt->execute([$anio1, $anio2]);
        return $stmt->fetchAll();
    }
}
