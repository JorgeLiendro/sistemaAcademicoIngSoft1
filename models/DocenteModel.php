<?php
class DocenteModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Método para obtener la información de la materia
    public function obtenerMateria($id_materia) {
        $stmt = $this->db->prepare("SELECT * FROM Materia WHERE id_materia = ?");
        $stmt->execute([$id_materia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener los materiales asociados a la materia
    public function obtenerMateriales($id_materia) {
        $stmt = $this->db->prepare("SELECT * FROM Material WHERE id_materia = ?");
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener las tareas asociadas a la materia
    public function obtenerTareasMateria($id_materia) {
        $stmt = $this->db->prepare("
            SELECT 
                t.*,
                COUNT(e.id_entrega) as entregas_totales,
                SUM(CASE WHEN e.calificacion IS NOT NULL THEN 1 ELSE 0 END) as entregas_calificadas
            FROM Tarea t
            LEFT JOIN Entrega e ON t.id_tarea = e.id_tarea
            WHERE t.id_materia = ?
            GROUP BY t.id_tarea
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener las materias de un docente
    public function obtenerMateriasDocente($id_docente) {
        $stmt = $this->db->prepare("
            SELECT m.*, COUNT(i.id_inscripcion) as estudiantes_inscritos
            FROM Materia m
            LEFT JOIN Inscripcion i ON m.id_materia = i.id_materia AND i.estado = 'Activa'
            WHERE m.id_docente = ?
            GROUP BY m.id_materia
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para crear una tarea
    public function crearTarea($id_materia, $titulo, $descripcion, $fecha_entrega) {
        $stmt = $this->db->prepare("
            INSERT INTO Tarea (id_materia, titulo, descripcion, fecha_entrega)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$id_materia, $titulo, $descripcion, $fecha_entrega]);
    }

    // Método para obtener las entregas de una tarea
    public function obtenerEntregasTarea($id_tarea) {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nombre_completo as estudiante
            FROM Entrega e
            JOIN Estudiante est ON e.id_estudiante = est.id_estudiante
            JOIN Usuario u ON est.id_usuario = u.id_usuario
            WHERE e.id_tarea = ?
        ");
        $stmt->execute([$id_tarea]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para calificar una tarea
    public function calificarTarea($id_entrega, $calificacion, $retroalimentacion) {
        $stmt = $this->db->prepare("
            UPDATE Entrega 
            SET calificacion = ?, retroalimentacion = ?, fecha_calificacion = NOW()
            WHERE id_entrega = ?
        ");
        return $stmt->execute([$calificacion, $retroalimentacion, $id_entrega]);
    }

    // En DocenteModel.php
    public function insertarMaterial($id_materia, $titulo, $descripcion, $tipo, $ruta) {
    $stmt = $this->db->prepare("
        INSERT INTO material (id_materia, titulo, descripcion, tipo, ruta)
        VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id_materia, $titulo, $descripcion, $tipo, $ruta]);
    }

    public function obtenerTarea($id_tarea) {
        $stmt = $this->db->prepare("SELECT * FROM Tarea WHERE id_tarea = ?");
        $stmt->execute([$id_tarea]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerReportesMateria($id_materia) {
        // Total de tareas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM Tarea WHERE id_materia = ?");
        $stmt->execute([$id_materia]);
        $total_tareas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
        // Tareas calificadas
        $stmt = $this->db->prepare("
            SELECT COUNT(DISTINCT t.id_tarea) as calificadas
            FROM Tarea t
            JOIN Entrega e ON t.id_tarea = e.id_tarea
            WHERE t.id_materia = ? AND e.calificacion IS NOT NULL
        ");
        $stmt->execute([$id_materia]);
        $tareas_calificadas = $stmt->fetch(PDO::FETCH_ASSOC)['calificadas'];
    
        // Total de estudiantes inscritos
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as estudiantes 
            FROM Inscripcion 
            WHERE id_materia = ? AND estado = 'Activa'
        ");
        $stmt->execute([$id_materia]);
        $total_estudiantes = $stmt->fetch(PDO::FETCH_ASSOC)['estudiantes'];
        
        // Datos para el gráfico de barras
        $stmt = $this->db->prepare("
            SELECT 
                SUM(CASE WHEN e.calificacion BETWEEN 90 AND 100 THEN 1 ELSE 0 END) as excelente,
                SUM(CASE WHEN e.calificacion BETWEEN 80 AND 89 THEN 1 ELSE 0 END) as bueno,
                SUM(CASE WHEN e.calificacion BETWEEN 70 AND 79 THEN 1 ELSE 0 END) as regular,
                SUM(CASE WHEN e.calificacion BETWEEN 60 AND 69 THEN 1 ELSE 0 END) as suficiente,
                SUM(CASE WHEN e.calificacion < 60 THEN 1 ELSE 0 END) as insuficiente
            FROM Entrega e
            JOIN Tarea t ON e.id_tarea = t.id_tarea
            WHERE t.id_materia = ? AND e.calificacion IS NOT NULL
        ");
        $stmt->execute([$id_materia]);
        $distribucion = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_tareas' => $total_tareas,
            'tareas_calificadas' => $tareas_calificadas,
            'total_estudiantes' => $total_estudiantes,
            'distribucion_calificaciones' => $distribucion
        ];
    }

    public function obtenerDesempenoEstudiantes($id_materia) {
        // Obtener datos de desempeño por estudiante
        $stmt = $this->db->prepare("
            SELECT 
                e.id_estudiante,
                u.nombre_completo as nombre,
                COUNT(ent.id_entrega) as entregadas,
                AVG(ent.calificacion) as promedio,
                MIN(ent.calificacion) as minima,
                MAX(ent.calificacion) as maxima,
                (SELECT COUNT(*) FROM Tarea t WHERE t.id_materia = ?) - COUNT(ent.id_entrega) as pendientes
            FROM Inscripcion i
            JOIN Estudiante e ON i.id_estudiante = e.id_estudiante
            JOIN Usuario u ON e.id_usuario = u.id_usuario
            LEFT JOIN Entrega ent ON e.id_estudiante = ent.id_estudiante 
                AND ent.id_tarea IN (SELECT id_tarea FROM Tarea WHERE id_materia = ?)
            WHERE i.id_materia = ? AND i.estado = 'Activa'
            GROUP BY e.id_estudiante, u.nombre_completo
        ");
        
        $stmt->execute([$id_materia, $id_materia, $id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function obtenerDocentePorUsuario($id_usuario) {
        $stmt = $this->db->prepare("
            SELECT d.id_docente, u.nombre_completo 
            FROM docente d
            JOIN usuario u ON d.id_usuario = u.id_usuario
            WHERE d.id_usuario = ?
            LIMIT 1
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }   
    // --- Módulo de Asistencia ---
    public function obtenerEstudiantesMateria($id_materia) {
        $stmt = $this->db->prepare("
            SELECT e.id_estudiante, u.nombre_completo 
            FROM Inscripcion i
            JOIN Estudiante e ON i.id_estudiante = e.id_estudiante
            JOIN Usuario u ON e.id_usuario = u.id_usuario
            WHERE i.id_materia = ? AND i.estado = 'Activa'
            ORDER BY u.nombre_completo
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarAsistencia($id_materia, $id_estudiante, $fecha, $estado) {
        // Verificar si ya existe el registro para actualizar o insertar
        $stmt = $this->db->prepare("SELECT id_asistencia FROM asistencia WHERE id_materia = ? AND id_estudiante = ? AND fecha = ?");
        $stmt->execute([$id_materia, $id_estudiante, $fecha]);
        $existe = $stmt->fetchColumn();

        if ($existe) {
            $stmtUpdate = $this->db->prepare("UPDATE asistencia SET estado = ? WHERE id_asistencia = ?");
            return $stmtUpdate->execute([$estado, $existe]);
        } else {
            $stmtInsert = $this->db->prepare("INSERT INTO asistencia (id_materia, id_estudiante, fecha, estado) VALUES (?, ?, ?, ?)");
            return $stmtInsert->execute([$id_materia, $id_estudiante, $fecha, $estado]);
        }
    }

    public function obtenerAsistenciaMateriaFecha($id_materia, $fecha) {
        $stmt = $this->db->prepare("SELECT id_estudiante, estado FROM asistencia WHERE id_materia = ? AND fecha = ?");
        $stmt->execute([$id_materia, $fecha]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $asistencia = [];
        foreach ($resultados as $row) {
            $asistencia[$row['id_estudiante']] = $row['estado'];
        }
        return $asistencia;
    }

    // --- Módulo de Evaluaciones ---
    public function obtenerEvaluacionesMateria($id_materia) {
        // Obtenemos solo el comentario y la puntuación, manteniendo el anonimato
        $stmt = $this->db->prepare("
            SELECT puntuacion, comentario, fecha 
            FROM evaluacion_docente 
            WHERE id_materia = ? 
            ORDER BY fecha DESC
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
