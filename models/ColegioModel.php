<?php
/**
 * MODELO: Colegio de Procedencia
 */

class ColegioModel extends Model {
    protected string $table = 'colegio_procedencia';
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'fiscal' => array_map(fn($d) => $d['fiscal'], $datos),
            'particular' => array_map(fn($d) => $d['particular'], $datos),
            'mixto' => array_map(fn($d) => $d['mixto'], $datos),
        ];
    }
}
