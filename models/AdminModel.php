<?php
class AdminModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerTodosUsuarios() {
        $stmt = $this->db->query("
            SELECT u.*, 
                   e.correo_institucional AS correo_estudiante,
                   d.correo_institucional AS correo_docente,
                   d.id_docente
            FROM Usuario u
            LEFT JOIN Estudiante e ON u.id_usuario = e.id_usuario
            LEFT JOIN Docente d ON u.id_usuario = d.id_usuario
            ORDER BY u.rol, u.nombre_completo
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($datos) {
        try {
            $this->db->beginTransaction();
    
            $stmt = $this->db->prepare("
                INSERT INTO Usuario (
                    nombre_completo, carnet, fecha_nacimiento, 
                    correo_electronico, numero_telefono, direccion, 
                    contraseña, rol
                ) VALUES (?, ?, ?, ?, ?, ?, SHA2(?, 256), ?)
            ");
            
            $stmt->execute([
                $datos['nombre_completo'],
                $datos['carnet'],
                $datos['fecha_nacimiento'],
                $datos['correo_electronico'],
                $datos['numero_telefono'],
                $datos['direccion'],
                $datos['contraseña'],
                $datos['rol']
            ]);
    
            $id_usuario = $this->db->lastInsertId();
    
            if ($datos['rol'] === 'Estudiante') {
                // Convertir cadena vacía a null para evitar error de FK
                $id_carrera = !empty($datos['id_carrera']) ? (int)$datos['id_carrera'] : null;
                $stmt = $this->db->prepare("INSERT INTO Estudiante (correo_institucional, id_usuario, id_carrera) VALUES (?, ?, ?)");
                $stmt->execute([$datos['correo_electronico'], $id_usuario, $id_carrera]);
            } elseif ($datos['rol'] === 'Docente') {
                $stmt = $this->db->prepare("
                    INSERT INTO Docente (correo_institucional, id_usuario, nivel_educacion, experiencia_ensenanza, horarios_disponibilidad) 
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $datos['correo_electronico'],
                    $id_usuario,
                    $datos['nivel_educacion'] ?? null,
                    $datos['experiencia_ensenanza'] ?? null,
                    $datos['horarios_disponibilidad'] ?? null
                ]);
            } elseif ($datos['rol'] === 'Administrador') {
                $stmt = $this->db->prepare("INSERT INTO Administrador (id_usuario) VALUES (?)");
                $stmt->execute([$id_usuario]);
            }
    
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al registrar usuario: " . $e->getMessage());
            // Re-lanzar como Exception para que el controlador muestre el error real
            throw new Exception($e->getMessage());
        }
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   e.correo_institucional AS correo_estudiante,
                   d.correo_institucional AS correo_docente,
                   d.id_docente,
                   d.nivel_educacion,
                   d.experiencia_ensenanza,
                   d.horarios_disponibilidad
            FROM Usuario u
            LEFT JOIN Estudiante e ON u.id_usuario = e.id_usuario
            LEFT JOIN Docente d ON u.id_usuario = d.id_usuario
            WHERE u.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarUsuario($id_usuario, $datos) {
        try {
            $this->db->beginTransaction();
            
            // Actualizar datos básicos del usuario
            $stmt = $this->db->prepare("
                UPDATE Usuario SET
                    nombre_completo = ?,
                    carnet = ?,
                    fecha_nacimiento = ?,
                    correo_electronico = ?,
                    numero_telefono = ?,
                    direccion = ?,
                    rol = ?
                WHERE id_usuario = ?
            ");
            $stmt->execute([
                $datos['nombre_completo'],
                $datos['carnet'],
                $datos['fecha_nacimiento'],
                $datos['correo_electronico'],
                $datos['numero_telefono'],
                $datos['direccion'],
                $datos['rol'],
                $id_usuario
            ]);
            
            // Si es docente, actualizar información específica
            if ($datos['rol'] === 'Docente') {
                $stmt = $this->db->prepare("
                    UPDATE Docente SET
                        nivel_educacion = ?,
                        experiencia_ensenanza = ?,
                        horarios_disponibilidad = ?
                    WHERE id_usuario = ?
                ");
                $stmt->execute([
                    $datos['nivel_educacion'] ?? null,
                    $datos['experiencia_ensenanza'] ?? null,
                    $datos['horarios_disponibilidad'] ?? null,
                    $id_usuario
                ]);
            }
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarUsuario($id_usuario) {
        try {
            // Verificar si hay una transacción activa
            $transactionInProgress = $this->db->inTransaction();
            
            if (!$transactionInProgress) {
                $this->db->beginTransaction();
            }
            
            // Obtener información del usuario
            $usuario = $this->obtenerUsuarioPorId($id_usuario);
            
            if (!$usuario) {
                throw new Exception("Usuario no encontrado");
            }
            
            // Si es docente, primero manejar sus materias
            if ($usuario['rol'] === 'Docente' && !empty($usuario['id_docente'])) {
                // Reasignar materias a otro docente o eliminarlas
                $this->reubicarMateriasDocente($usuario['id_docente']);
            }
            
            // Eliminar el usuario (ON DELETE CASCADE se encargará del resto)
            $stmt = $this->db->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            
            if (!$transactionInProgress) {
                $this->db->commit();
            }
            
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction() && !$transactionInProgress) {
                $this->db->rollBack();
            }
            error_log("Error al eliminar usuario: " . $e->getMessage());
            return false;
        }
    }

    private function reubicarMateriasDocente($id_docente) {
        // Primero verificar si hay materias asignadas
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Materia WHERE id_docente = ?");
        $stmt->execute([$id_docente]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // Obtener el primer docente disponible (excluyendo al que se está eliminando)
            $stmt = $this->db->prepare("
                SELECT id_docente FROM Docente 
                WHERE id_docente != ? 
                LIMIT 1
            ");
            $stmt->execute([$id_docente]);
            $nuevo_docente = $stmt->fetchColumn();
            
            if ($nuevo_docente) {
                // Reasignar materias
                $stmt = $this->db->prepare("
                    UPDATE Materia SET id_docente = ? 
                    WHERE id_docente = ?
                ");
                $stmt->execute([$nuevo_docente, $id_docente]);
            } else {
                // No hay otros docentes, eliminar las materias y sus relaciones
                $this->eliminarMateriasDocente($id_docente);
            }
        }
    }

    private function eliminarMateriasDocente($id_docente) {
        // Obtener todas las materias del docente
        $stmt = $this->db->prepare("SELECT id_materia FROM Materia WHERE id_docente = ?");
        $stmt->execute([$id_docente]);
        $materias = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($materias as $id_materia) {
            $this->eliminarMateria($id_materia);
        }
    }

    public function obtenerTodasMaterias() {
        $stmt = $this->db->query("
            SELECT m.*, u.nombre_completo AS nombre_docente, c.nombre AS nombre_carrera
            FROM Materia m
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            LEFT JOIN Carrera c ON m.id_carrera = c.id_carrera
            ORDER BY m.nombre
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDocentes() {
        $stmt = $this->db->query("
            SELECT d.id_docente, u.nombre_completo, u.correo_electronico
            FROM Docente d
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            ORDER BY u.nombre_completo
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearMateria($nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno) {
        $stmt = $this->db->prepare("
            INSERT INTO Materia (nombre, descripcion, id_docente, id_carrera, id_periodo, nivel_semestre, grupo, turno)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno]);
    }

    public function eliminarMateria($id_materia) {
        try {
            $transactionInProgress = $this->db->inTransaction();
            
            if (!$transactionInProgress) {
                $this->db->beginTransaction();
            }
            
            // 1. Eliminar entregas relacionadas con tareas de esta materia
            $stmt = $this->db->prepare("
                DELETE e FROM Entrega e
                JOIN Tarea t ON e.id_tarea = t.id_tarea
                WHERE t.id_materia = ?
            ");
            $stmt->execute([$id_materia]);
            
            // 2. Eliminar tareas de esta materia
            $stmt = $this->db->prepare("DELETE FROM Tarea WHERE id_materia = ?");
            $stmt->execute([$id_materia]);
            
            // 3. Eliminar materiales de esta materia
            $stmt = $this->db->prepare("DELETE FROM Material WHERE id_materia = ?");
            $stmt->execute([$id_materia]);
            
            // 4. Eliminar inscripciones a esta materia
            $stmt = $this->db->prepare("DELETE FROM Inscripcion WHERE id_materia = ?");
            $stmt->execute([$id_materia]);
            
            // 5. Finalmente eliminar la materia
            $stmt = $this->db->prepare("DELETE FROM Materia WHERE id_materia = ?");
            $stmt->execute([$id_materia]);
            
            if (!$transactionInProgress) {
                $this->db->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction() && !$transactionInProgress) {
                $this->db->rollBack();
            }
            error_log("Error al eliminar materia: " . $e->getMessage());
            return false;
        }
    }

    public function inscribirEstudianteMateria($id_estudiante, $id_materia) {
        try {
            $this->db->beginTransaction();
            
            // Verificar si el estudiante ya está inscrito
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM Inscripcion 
                WHERE id_estudiante = ? AND id_materia = ?
            ");
            $stmt->execute([$id_estudiante, $id_materia]);
            $existe = $stmt->fetchColumn();
            
            if ($existe > 0) {
                throw new Exception("El estudiante ya está inscrito en esta materia");
            }
            
            // Realizar la inscripción
            $stmt = $this->db->prepare("
                INSERT INTO Inscripcion (id_estudiante, id_materia)
                VALUES (?, ?)
            ");
            $stmt->execute([$id_estudiante, $id_materia]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error al inscribir estudiante: " . $e->getMessage());
            return false;
        }
    }
    public function obtenerUsuariosFiltrados($filtroRol = '', $busqueda = '') {
        $sql = "
            SELECT u.*, 
                   e.correo_institucional AS correo_estudiante,
                   d.correo_institucional AS correo_docente,
                   d.id_docente
            FROM Usuario u
            LEFT JOIN Estudiante e ON u.id_usuario = e.id_usuario
            LEFT JOIN Docente d ON u.id_usuario = d.id_usuario
            WHERE 1=1
        ";
        
        $params = [];
        
        // Aplicar filtro por rol
        if (!empty($filtroRol)) {
            $sql .= " AND u.rol = ?";
            $params[] = $filtroRol;
        }
        
        // Aplicar búsqueda general
        if (!empty($busqueda)) {
            $sql .= " AND (u.nombre_completo LIKE ? OR u.carnet LIKE ? OR u.correo_electronico LIKE ?)";
            $searchTerm = "%$busqueda%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY u.rol, u.nombre_completo";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMateriasFiltradas($filtroDocente = '', $busqueda = '') {
        $sql = "
            SELECT m.*, u.nombre_completo AS nombre_docente, c.nombre AS nombre_carrera
            FROM Materia m
            JOIN Docente d ON m.id_docente = d.id_docente
            JOIN Usuario u ON d.id_usuario = u.id_usuario
            LEFT JOIN Carrera c ON m.id_carrera = c.id_carrera
            WHERE 1=1
        ";
        
        $params = [];
        
        // Aplicar filtro por docente
        if (!empty($filtroDocente)) {
            $sql .= " AND m.id_docente = ?";
            $params[] = $filtroDocente;
        }
        
        // Aplicar búsqueda general
        if (!empty($busqueda)) {
            $sql .= " AND (m.nombre LIKE ? OR m.descripcion LIKE ? OR u.nombre_completo LIKE ?)";
            $searchTerm = "%$busqueda%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY m.nombre";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Gestión de Carreras ---
    public function obtenerTodasCarreras() {
        $stmt = $this->db->query("SELECT * FROM carrera ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearCarrera($nombre, $descripcion, $estado) {
        $stmt = $this->db->prepare("INSERT INTO carrera (nombre, descripcion, estado) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $estado]);
    }

    public function obtenerCarreraPorId($id_carrera) {
        $stmt = $this->db->prepare("SELECT * FROM carrera WHERE id_carrera = ?");
        $stmt->execute([$id_carrera]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarCarrera($id_carrera, $nombre, $descripcion, $estado) {
        $stmt = $this->db->prepare("UPDATE carrera SET nombre = ?, descripcion = ?, estado = ? WHERE id_carrera = ?");
        return $stmt->execute([$nombre, $descripcion, $estado, $id_carrera]);
    }

    public function eliminarCarrera($id_carrera) {
        $stmt = $this->db->prepare("DELETE FROM carrera WHERE id_carrera = ?");
        return $stmt->execute([$id_carrera]);
    }

    // --- Gestión de Periodos Académicos ---
    public function obtenerTodosPeriodos() {
        $stmt = $this->db->query("SELECT * FROM periodo_academico ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearPeriodo($nombre, $fecha_inicio, $fecha_fin, $estado) {
        $stmt = $this->db->prepare("INSERT INTO periodo_academico (nombre, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $fecha_inicio, $fecha_fin, $estado]);
    }

    public function obtenerPeriodoPorId($id_periodo) {
        $stmt = $this->db->prepare("SELECT * FROM periodo_academico WHERE id_periodo = ?");
        $stmt->execute([$id_periodo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarPeriodo($id_periodo, $nombre, $fecha_inicio, $fecha_fin, $estado) {
        $stmt = $this->db->prepare("UPDATE periodo_academico SET nombre = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_periodo = ?");
        return $stmt->execute([$nombre, $fecha_inicio, $fecha_fin, $estado, $id_periodo]);
    }

    public function eliminarPeriodo($id_periodo) {
        $stmt = $this->db->prepare("DELETE FROM periodo_academico WHERE id_periodo = ?");
        return $stmt->execute([$id_periodo]);
    }

    // ===== MÉTODOS PARA REPORTES =====

    public function obtenerEstadisticasCarrera($id_carrera) {
        // Total estudiantes
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM estudiante WHERE id_carrera = ?");
        $stmt->execute([$id_carrera]);
        $total_estudiantes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Promedio de calificaciones
        $stmt = $this->db->prepare("
            SELECT AVG(e.calificacion) as promedio
            FROM entrega e
            JOIN tarea t ON e.id_tarea = t.id_tarea
            JOIN materia m ON t.id_materia = m.id_materia
            WHERE m.id_carrera = ?
        ");
        $stmt->execute([$id_carrera]);
        $promedio = $stmt->fetch(PDO::FETCH_ASSOC)['promedio'] ?? 0;

        // Mejores estudiantes
        $stmt = $this->db->prepare("
            SELECT u.nombre_completo, AVG(e.calificacion) as promedio
            FROM estudiante est
            JOIN usuario u ON est.id_usuario = u.id_usuario
            LEFT JOIN entrega e ON est.id_estudiante = e.id_estudiante
            WHERE est.id_carrera = ?
            GROUP BY est.id_estudiante
            ORDER BY promedio DESC
            LIMIT 5
        ");
        $stmt->execute([$id_carrera]);
        $mejores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'total_estudiantes' => $total_estudiantes,
            'promedio_general' => round($promedio, 2),
            'mejores_estudiantes' => $mejores
        ];
    }

    public function obtenerEstadisticasDocentes() {
        $stmt = $this->db->prepare("
            SELECT 
                u.nombre_completo,
                COUNT(DISTINCT m.id_materia) as materias,
                COUNT(DISTINCT i.id_estudiante) as estudiantes_total,
                AVG(ed.puntuacion) as promedio_evaluacion
            FROM docente d
            JOIN usuario u ON d.id_usuario = u.id_usuario
            LEFT JOIN materia m ON d.id_docente = m.id_docente
            LEFT JOIN inscripcion i ON m.id_materia = i.id_materia
            LEFT JOIN evaluacion_docente ed ON d.id_docente = ed.id_docente
            GROUP BY d.id_docente
            ORDER BY promedio_evaluacion DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticasAsistencia() {
        $stmt = $this->db->prepare("
            SELECT 
                m.nombre as materia,
                COUNT(CASE WHEN a.estado = 'Presente' THEN 1 END) as presentes,
                COUNT(CASE WHEN a.estado = 'Ausente' THEN 1 END) as ausentes,
                COUNT(CASE WHEN a.estado = 'Licencia' THEN 1 END) as licencias,
                COUNT(CASE WHEN a.estado = 'Retraso' THEN 1 END) as retrasos,
                COUNT(*) as total,
                ROUND(COUNT(CASE WHEN a.estado = 'Presente' THEN 1 END) * 100 / COUNT(*), 2) as porcentaje_asistencia
            FROM asistencia a
            JOIN materia m ON a.id_materia = m.id_materia
            GROUP BY a.id_materia
            ORDER BY porcentaje_asistencia DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticasCalificaciones() {
        $stmt = $this->db->prepare("
            SELECT 
                m.nombre as materia,
                COUNT(*) as total_entregas,
                AVG(e.calificacion) as promedio,
                MIN(e.calificacion) as minima,
                MAX(e.calificacion) as maxima,
                COUNT(CASE WHEN e.calificacion >= 90 THEN 1 END) as excelente,
                COUNT(CASE WHEN e.calificacion BETWEEN 80 AND 89 THEN 1 END) as bueno,
                COUNT(CASE WHEN e.calificacion BETWEEN 70 AND 79 THEN 1 END) as regular,
                COUNT(CASE WHEN e.calificacion < 70 THEN 1 END) as insuficiente
            FROM entrega e
            JOIN tarea t ON e.id_tarea = t.id_tarea
            JOIN materia m ON t.id_materia = m.id_materia
            GROUP BY t.id_materia
            ORDER BY promedio DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticasInscripciones() {
        $stmt = $this->db->prepare("
            SELECT 
                m.nombre as materia,
                COUNT(i.id_inscripcion) as estudiantes_inscritos,
                COUNT(CASE WHEN i.estado = 'Activa' THEN 1 END) as activos,
                COUNT(CASE WHEN i.estado = 'Retirado' THEN 1 END) as retirados,
                u.nombre_completo as docente
            FROM materia m
            LEFT JOIN inscripcion i ON m.id_materia = i.id_materia
            LEFT JOIN docente d ON m.id_docente = d.id_docente
            LEFT JOIN usuario u ON d.id_usuario = u.id_usuario
            GROUP BY m.id_materia
            ORDER BY estudiantes_inscritos DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticasEvaluaciones() {
        $stmt = $this->db->prepare("
            SELECT 
                d.id_docente,
                u.nombre_completo,
                COUNT(ed.id_evaluacion) as total_evaluaciones,
                AVG(ed.puntuacion) as promedio,
                COUNT(CASE WHEN ed.puntuacion = 5 THEN 1 END) as cinco_estrellas,
                COUNT(CASE WHEN ed.puntuacion = 4 THEN 1 END) as cuatro_estrellas,
                COUNT(CASE WHEN ed.puntuacion = 3 THEN 1 END) as tres_estrellas,
                COUNT(CASE WHEN ed.puntuacion <= 2 THEN 1 END) as dos_estrellas_menos
            FROM docente d
            JOIN usuario u ON d.id_usuario = u.id_usuario
            LEFT JOIN evaluacion_docente ed ON d.id_docente = ed.id_docente
            GROUP BY d.id_docente
            ORDER BY promedio DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>