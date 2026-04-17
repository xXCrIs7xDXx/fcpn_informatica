<?php
/**
 * MODELO: Permanencia
 */

class PermanenciaModel extends Model {
    protected string $table = 'permanencia';
    
    /**
     * Obtener datos estructurados para gráfico de permanencia
     */
    public function getParaGrafico(): array {
        $stmt = $this->db->query(
            'SELECT anio,
                    p_1anio, p_2anios, p_3anios, p_4anios,
                    p_5a6, p_7a9, p_10a11, p_mas11 
             FROM permanencia ORDER BY anio ASC'
        );
        return $stmt->fetchAll();
    }
}
