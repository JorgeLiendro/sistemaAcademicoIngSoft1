<?php
class MateriaModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }
    // Agrega este método
    public function obtenerMateriasDisponibles($id_usuario) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre_completo as nombre_docente, c.nombre as nombre_carrera
            FROM Materia m
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            LEFT JOIN Carrera c ON m.id_carrera = c.id_carrera
            WHERE m.id_materia NOT IN (
                SELECT i.id_materia 
                FROM Inscripcion i
                JOIN Estudiante e ON i.id_estudiante = e.id_estudiante
                JOIN Usuario u ON e.id_usuario = u.id_usuario
                WHERE u.id_usuario = ?
            )
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function obtenerMateriaPorId($id_materia) {
        $stmt = $this->db->prepare("
            SELECT m.*, d.id_docente, u.nombre_completo AS nombre_docente
            FROM Materia m
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            WHERE m.id_materia = ?
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarMateria($id_materia, $nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno) {
        $stmt = $this->db->prepare("
            UPDATE Materia 
            SET nombre = ?, descripcion = ?, id_docente = ?, id_carrera = ?, id_periodo = ?, nivel_semestre = ?, grupo = ?, turno = ?
            WHERE id_materia = ?
        ");
        return $stmt->execute([$nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno, $id_materia]);
    }

    public function obtenerDocentes() {
        $stmt = $this->db->query("
            SELECT d.id_docente, u.nombre_completo
            FROM Docente d
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            ORDER BY u.nombre_completo
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>