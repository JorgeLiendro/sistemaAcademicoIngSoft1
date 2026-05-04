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


        /////examen///
    // 1. Evita que el alumno rinda el examen dos veces
    public function verificarExamenHecho($id_usuario, $id_evaluacion) {
        $sql = "SELECT COUNT(*) FROM resultados WHERE usuario_id = ? AND evaluacion_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_usuario, $id_evaluacion]);
        return $stmt->fetchColumn() > 0;
    }

    // 2. Obtiene el examen con sus preguntas y opciones para la vista[cite: 2, 3]
    public function obtenerDetalleExamen($id_evaluacion) {
        // Obtenemos la cabecera
        $sql = "SELECT * FROM evaluaciones WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_evaluacion]);
        $examen = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($examen) {
            // Obtenemos preguntas y sus opciones
            $sqlPreguntas = "SELECT * FROM preguntas WHERE evaluacion_id = ?";
            $stmtP = $this->db->prepare($sqlPreguntas);
            $stmtP->execute([$id_evaluacion]);
            $preguntas = $stmtP->fetchAll(PDO::FETCH_ASSOC);

            foreach ($preguntas as &$pregunta) {
                $sqlOpciones = "SELECT * FROM opciones WHERE pregunta_id = ?";
                $stmtO = $this->db->prepare($sqlOpciones);
                $stmtO->execute([$pregunta['id']]);
                $pregunta['opciones'] = $stmtO->fetchAll(PDO::FETCH_ASSOC);
            }
            $examen['preguntas'] = $preguntas;
        }
        return $examen;
    }

    // 3. Valida si la nota ya puede ser vista (Por tiempo o por el docente)
    public function obtenerNotaSiEstaPublicada($id_evaluacion, $id_usuario) {
        $sql = "SELECT r.nota, e.fecha_fin, e.publicacion_forzada, e.id_materia 
                FROM resultados r
                JOIN evaluaciones e ON r.evaluacion_id = e.id
                WHERE r.evaluacion_id = ? AND r.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_evaluacion, $id_usuario]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultado) return null;

        $ahora = date('Y-m-d H:i:s');
        // Si ya pasó la fecha fin o el docente forzó la publicación, es visible[cite: 2]
        $resultado['visible'] = ($ahora > $resultado['fecha_fin'] || $resultado['publicacion_forzada'] == 1);
        
        return $resultado;
    }
    // 4. procesar calificacion
        public function procesarCalificacion($id_usuario, $id_evaluacion, $respuestas) {
        try {
            $this->db->beginTransaction();

            $puntos_ganados = 0;
            $total_preguntas = count($respuestas);

            foreach ($respuestas as $id_pregunta => $id_opcion_seleccionada) {
                // Verificamos si la opción seleccionada es la correcta en la BD
                $sql = "SELECT es_correcta FROM opciones WHERE id = ? AND pregunta_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$id_opcion_seleccionada, $id_pregunta]);
                
                if ($stmt->fetchColumn() == 1) {
                    $puntos_ganados++;
                }
            }

            // Cálculo de nota (ejemplo sobre 100 puntos)
            $nota_final = ($total_preguntas > 0) ? ($puntos_ganados / $total_preguntas) * 100 : 0;

            // Insertar el resultado final
            $sqlInsert = "INSERT INTO resultados (usuario_id, evaluacion_id, nota, fecha_envio) 
                        VALUES (?, ?, ?, NOW())";
            $stmtInsert = $this->db->prepare($sqlInsert);
            $stmtInsert->execute([$id_usuario, $id_evaluacion, $nota_final]);

            $this->db->commit();
            return $nota_final;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al calificar: " . $e->getMessage());
            return false;
        }
    }

        public function iniciarIntento($id_evaluacion, $id_estudiante) {
        $stmt = $this->db->prepare("
            INSERT INTO intentos (id_evaluacion, id_estudiante, fecha_inicio)
            VALUES (?, ?, NOW())
        ");
        $stmt->execute([$id_evaluacion, $id_estudiante]);

        return $this->db->lastInsertId();
    }
    public function guardarRespuesta($id_intento, $id_pregunta, $id_opcion) {
        $stmt = $this->db->prepare("
            INSERT INTO respuestas (id_intento, id_pregunta, id_opcion)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$id_intento, $id_pregunta, $id_opcion]);
    }
    public function calificarExamen($id_intento) {

        $sql = "
            SELECT r.id_opcion, o.es_correcta
            FROM respuestas r
            JOIN opciones o ON r.id_opcion = o.id_opcion
            WHERE r.id_intento = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_intento]);
        $respuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $correctas = 0;
        $total = count($respuestas);

        foreach ($respuestas as $r) {
            if ($r['es_correcta'] == 1) {
                $correctas++;
            }
        }

        $nota = ($total > 0) ? ($correctas / $total) * 100 : 0;

        // Guardar nota
        $stmt = $this->db->prepare("
            UPDATE intentos
            SET nota = ?, fecha_fin = NOW(), estado = 'Finalizado'
            WHERE id_intento = ?
        ");
        $stmt->execute([$nota, $id_intento]);

        return $nota;
    }
///-------
    
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
