<?php
/**
 * MODELO: Matriculados
 * Total de estudiantes matriculados por año
 */

class MatriculadosModel extends Model {
    protected string $table = 'matriculados';
    
    /**
     * Obtener evolución de matrículas para gráfico
     */
    public function getEvolucion(): array {
        $stmt = $this->db->query(
            'SELECT anio, total FROM matriculados ORDER BY anio ASC'
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener tendencia de crecimiento
     */
    public function getTendencia(): array {
        $stmt = $this->db->query(
            'SELECT * FROM v_crecimiento_matriculas'
        );
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener KPI del año más reciente
     */
    public function getKPIActual(): ?array {
        $stmt = $this->db->query(
            'SELECT anio, total FROM matriculados ORDER BY anio DESC LIMIT 1'
        );
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Comparar crecimiento entre dos años
     */
    public function compararAnios(int $anio1, int $anio2): array {
        $stmt = $this->db->prepare(
            'SELECT anio, total FROM matriculados WHERE anio IN (?, ?) ORDER BY anio'
        );
        $stmt->execute([$anio1, $anio2]);
        $datos = $stmt->fetchAll();
        
        if (count($datos) === 2) {
            return [
                'anio1' => $datos[0]['anio'],
                'total1' => $datos[0]['total'],
                'anio2' => $datos[1]['anio'],
                'total2' => $datos[1]['total'],
                'diferencia' => $datos[1]['total'] - $datos[0]['total'],
                'porcentaje' => round(
                    (($datos[1]['total'] - $datos[0]['total']) / $datos[0]['total']) * 100, 2
                )
            ];
        }
        return [];
    }
}
