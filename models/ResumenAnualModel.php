<?php
/**
 * MODELO: Resumen Anual
 * Accede directo a la vista v_resumen_anual para dashboards consolidados
 */

class ResumenAnualModel {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obtener resumen consolidado de todos los datos por año
     */
    public function getAll(): array {
        $stmt = $this->db->query('SELECT * FROM v_resumen_anual ORDER BY anio ASC');
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener resumen de un año específico
     */
    public function getByAnio(int $anio): ?array {
        $stmt = $this->db->prepare('SELECT * FROM v_resumen_anual WHERE anio = ?');
        $stmt->execute([$anio]);
        return $stmt->fetch() ?: null;
    }
}
