<?php
/**
 * MODELO: Distribución de Edad (solo 2023)
 */

class EdadModel extends Model {
    protected string $table = 'distribucion_edad';
    
    /**
     * Obtener distribución de edad (solo para 2023)
     */
    public function getDistribucio2023(): array {
        $stmt = $this->db->query(
            'SELECT rango_edad, cantidad FROM distribucion_edad WHERE anio = 2023 ORDER BY orden ASC'
        );
        return $stmt->fetchAll();
    }
}
