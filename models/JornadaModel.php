<?php
/**
 * MODELO: Jornada Laboral
 */

class JornadaModel extends Model {
    protected string $table = 'jornada_laboral';
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'tiempo_completo' => array_map(fn($d) => $d['tiempo_completo'], $datos),
            'medio_tiempo' => array_map(fn($d) => $d['medio_tiempo'], $datos),
            'eventual' => array_map(fn($d) => $d['eventual'], $datos),
            'horario' => array_map(fn($d) => $d['horario'], $datos),
        ];
    }
}
