<?php
/**
 * MODELO: Vivienda
 */

class ViviendaModel extends Model {
    protected string $table = 'vivienda';
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'propia' => array_map(fn($d) => $d['propia'], $datos),
            'alquilada' => array_map(fn($d) => $d['alquilada'], $datos),
            'anticretico' => array_map(fn($d) => $d['anticretico'], $datos),
            'prestada' => array_map(fn($d) => $d['prestada'], $datos),
            'otra' => array_map(fn($d) => $d['otra'], $datos),
        ];
    }
}
