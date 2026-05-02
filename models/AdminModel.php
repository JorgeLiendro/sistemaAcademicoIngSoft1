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
}
?>