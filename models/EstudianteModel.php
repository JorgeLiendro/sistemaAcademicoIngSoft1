<?php
class EstudianteModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerMateriasInscritas($id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre_completo as docente,
            (SELECT AVG(e.calificacion) FROM Entrega e 
             JOIN Tarea t ON e.id_tarea = t.id_tarea 
             WHERE t.id_materia = m.id_materia AND e.id_estudiante = ? AND e.calificacion IS NOT NULL) as promedio
            FROM Inscripcion i
            JOIN Materia m ON i.id_materia = m.id_materia
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            WHERE i.id_estudiante = ? AND i.estado = 'Activa'
        ");
        $stmt->execute([$id_estudiante, $id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTareasMateria($id_materia, $id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT t.*, e.calificacion, e.retroalimentacion, e.fecha_entrega as fecha_entrega_estudiante
            FROM Tarea t
            LEFT JOIN Entrega e ON t.id_tarea = e.id_tarea AND e.id_estudiante = ?
            WHERE t.id_materia = ?
            ORDER BY t.fecha_entrega DESC
        ");
        $stmt->execute([$id_estudiante, $id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function entregarTarea($id_tarea, $id_estudiante, $archivo) {
        $ruta_archivo = $this->guardarArchivo($archivo);
        
        if (!$ruta_archivo) {
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO Entrega (id_tarea, id_estudiante, archivo, fecha_entrega)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
            archivo = VALUES(archivo), fecha_entrega = NOW()
        ");
        return $stmt->execute([$id_tarea, $id_estudiante, $ruta_archivo]);
    }

    private function guardarArchivo($archivo) {
        $directorio = 'uploads/tareas/';
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $ruta_completa = $directorio . $nombre_archivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
            return $ruta_completa;
        }
        return null;
    }

    public function obtenerMaterialesMateria($id_materia) {
        $stmt = $this->db->prepare("
            SELECT * FROM material 
            WHERE id_materia = ?
            ORDER BY id_material DESC
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerInfoMateria($id_materia) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nombre_completo as docente 
            FROM Materia m
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            WHERE m.id_materia = ?
        ");
        $stmt->execute([$id_materia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCalificacionesMateria($id_materia, $id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT 
                t.id_tarea,
                t.titulo as titulo_tarea,
                t.descripcion,
                t.fecha_entrega,
                e.fecha_entrega as fecha_entrega_estudiante,
                e.calificacion,
                e.retroalimentacion as comentarios,
                e.archivo as archivo_entrega
            FROM Tarea t
            LEFT JOIN Entrega e ON t.id_tarea = e.id_tarea AND e.id_estudiante = ?
            WHERE t.id_materia = ?
            ORDER BY t.fecha_entrega DESC
        ");
        $stmt->execute([$id_estudiante, $id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerResumenCalificaciones($id_materia, $id_estudiante) {
        // Obtener promedio de calificaciones
        $stmt = $this->db->prepare("
            SELECT AVG(e.calificacion) as promedio
            FROM Tarea t
            JOIN Entrega e ON t.id_tarea = e.id_tarea
            WHERE t.id_materia = ? AND e.id_estudiante = ? AND e.calificacion IS NOT NULL
        ");
        $stmt->execute([$id_materia, $id_estudiante]);
        $promedio = $stmt->fetch(PDO::FETCH_ASSOC)['promedio'];
        
        // Obtener total de tareas y entregadas
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(t.id_tarea) as total_tareas,
                SUM(CASE WHEN e.id_entrega IS NOT NULL THEN 1 ELSE 0 END) as tareas_entregadas,
                MAX(e.calificacion) as mejor_calificacion
            FROM Tarea t
            LEFT JOIN Entrega e ON t.id_tarea = e.id_tarea AND e.id_estudiante = ?
            WHERE t.id_materia = ?
        ");
        $stmt->execute([$id_estudiante, $id_materia]);
        $resumen = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return [
            'promedio_calificaciones' => $promedio ? round($promedio, 1) : 0,
            'tareas_entregadas' => $resumen['tareas_entregadas'],
            'total_tareas' => $resumen['total_tareas'],
            'mejor_calificacion' => $resumen['mejor_calificacion'] ? $resumen['mejor_calificacion'] : 0
        ];
    }

    public function obtenerAsistenciaMateria($id_materia, $id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT fecha, estado 
            FROM asistencia 
            WHERE id_materia = ? AND id_estudiante = ?
            ORDER BY fecha DESC
        ");
        $stmt->execute([$id_materia, $id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function verificarEvaluacionExistente($id_materia, $id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT id_evaluacion 
            FROM evaluacion_docente 
            WHERE id_materia = ? AND id_estudiante = ?
        ");
        $stmt->execute([$id_materia, $id_estudiante]);
        return $stmt->fetchColumn() ? true : false;
    }

    public function guardarEvaluacionDocente($id_estudiante, $id_docente, $id_materia, $puntuacion, $comentario) {
        $stmt = $this->db->prepare("
            INSERT INTO evaluacion_docente (id_estudiante, id_docente, id_materia, puntuacion, comentario, fecha)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$id_estudiante, $id_docente, $id_materia, $puntuacion, $comentario]);
    }

    public function obtenerTareasPendientes($id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT t.*, m.nombre as materia_nombre
            FROM Tarea t
            JOIN Materia m ON t.id_materia = m.id_materia
            JOIN Inscripcion i ON m.id_materia = i.id_materia
            WHERE i.id_estudiante = ? AND i.estado = 'Activa'
            AND t.id_tarea NOT IN (
                SELECT id_tarea FROM Entrega WHERE id_estudiante = ?
            )
            AND t.fecha_entrega >= NOW()
            ORDER BY t.fecha_entrega ASC
            LIMIT 5
        ");
        $stmt->execute([$id_estudiante, $id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMaterialReciente($id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT mat.*, m.nombre as materia_nombre
            FROM Material mat
            JOIN Materia m ON mat.id_materia = m.id_materia
            JOIN Inscripcion i ON m.id_materia = i.id_materia
            WHERE i.id_estudiante = ? AND i.estado = 'Activa'
            ORDER BY mat.id_material DESC
            LIMIT 5
        ");
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // NUEVA FUNCIÓN: Obtener notas finales para el Boletín
    public function obtenerBoletin($id_estudiante) {
        $stmt = $this->db->prepare("
            SELECT m.nombre as materia, cf.nota_trimestre1, cf.nota_trimestre2, cf.nota_trimestre3, cf.nota_final, cf.observacion
            FROM calificacion_final cf
            JOIN materia m ON cf.id_materia = m.id_materia
            WHERE cf.id_estudiante = ?
        ");
        $stmt->execute([$id_estudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>