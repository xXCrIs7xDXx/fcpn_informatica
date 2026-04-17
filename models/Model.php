<?php
/**
 * CLASE BASE PARA MODELOS
 * Proporciona métodos comunes para todas las tablas
 */

abstract class Model {
    protected PDO $db;
    protected string $table;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    /**
     * Obtener todos los registros ordenados por año
     */
    public function getAll(): array {
        $sql = "SELECT * FROM {$this->table} ORDER BY anio ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener registro por año
     */
    public function getByAnio(int $anio): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE anio = ?"
        );
        $stmt->execute([$anio]);
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Obtener registro por ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    
    /**
     * Obtener registros en rango de años
     */
    public function getByRangeAnios(int $anioInicio, int $anioFin): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE anio BETWEEN ? AND ? ORDER BY anio ASC"
        );
        $stmt->execute([$anioInicio, $anioFin]);
        return $stmt->fetchAll();
    }
    
    /**
     * Contar total de registros
     */
    public function countAll(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
        return (int) $stmt->fetch()['count'];
    }
}
