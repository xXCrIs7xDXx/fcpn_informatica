<?php
/**
 * MODELO: Estado Civil
 */

class EstadoCivilModel extends Model {
    protected string $table = 'estado_civil';
    
    /**
     * Obtener datos formatados para Chart.js (barras apiladas)
     */
    public function getParaGrafico(): array {
        $datos = $this->getAll();
        
        return [
            'años' => array_map(fn($d) => $d['anio'], $datos),
            'soltero' => array_map(fn($d) => $d['soltero'], $datos),
            'casado' => array_map(fn($d) => $d['casado'], $datos),
            'otros' => array_map(fn($d) => $d['otros'], $datos),
        ];
    }
}
