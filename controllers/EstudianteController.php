<?php
class EstudianteController {
    private $model;

    public function __construct() {
        $this->model = new EstudianteModel();
    }

    public function inicio() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("
            SELECT e.id_estudiante, c.nombre as nombre_carrera 
            FROM Estudiante e 
            LEFT JOIN Carrera c ON e.id_carrera = c.id_carrera 
            WHERE e.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $tareas_pendientes = [];
        $material_reciente = [];
        
        if ($estudiante) {
            $tareas_pendientes = $this->model->obtenerTareasPendientes($estudiante['id_estudiante']);
            $material_reciente = $this->model->obtenerMaterialReciente($estudiante['id_estudiante']);
        }
        
        require_once 'views/estudiante/inicio.php';
    }

    public function dashboard() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Obtener id_estudiante del usuario actual
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("
            SELECT e.id_estudiante, c.nombre as nombre_carrera 
            FROM Estudiante e 
            LEFT JOIN Carrera c ON e.id_carrera = c.id_carrera 
            WHERE e.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Asegurar que la carrera esté en la sesión para el header
        if (!isset($_SESSION['carrera'])) {
            $_SESSION['carrera'] = $estudiante['nombre_carrera'] ?? 'Sin Carrera Asignada';
        }
        
        $materias = $this->model->obtenerMateriasInscritas($estudiante['id_estudiante']);
        require_once 'views/estudiante/dashboard.php';
    }
    // Agrega estos métodos a tu controlador

    public function inscribirMateria() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
    
        // Obtener id_estudiante
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT id_estudiante FROM Estudiante WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$estudiante) {
            $_SESSION['error'] = "No se encontró el estudiante";
            header('Location: index.php?controller=Estudiante&action=dashboard');
            exit();
        }
    
        // Obtener materias disponibles correctamente
        $materiaModel = new MateriaModel();
        $materias = $materiaModel->obtenerMateriasDisponibles($id_usuario);
    
        require_once 'views/estudiante/inscribir_materia.php';
    }
    

    public function guardarInscripcion() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['id_usuario'];
            $db = (new Database())->connect();
    
            // Obtener id_estudiante
            $stmt = $db->prepare("SELECT id_estudiante FROM Estudiante WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$estudiante) {
                $_SESSION['error'] = "No se encontró el estudiante";
                header('Location: index.php?controller=Estudiante&action=dashboard');
                exit();
            }
    
            $id_materia = $_POST['id_materia'];
    
            try {
                $stmt = $db->prepare("INSERT INTO Inscripcion (id_estudiante, id_materia) VALUES (?, ?)");
                $result = $stmt->execute([$estudiante['id_estudiante'], $id_materia]);
    
                $_SESSION['mensaje'] = $result ? "Inscripción realizada correctamente" : "Error al realizar la inscripción";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Ya estás inscrito en esta materia o ha ocurrido un error";
            }
    
            header('Location: index.php?controller=Estudiante&action=dashboard');
            exit();
        }
    }    

    public function evaluacionDocente() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT id_estudiante FROM Estudiante WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $materias = $this->model->obtenerMateriasInscritas($estudiante['id_estudiante']);
        require_once 'views/estudiante/evaluacion_docente.php';
    }

    public function verTareas() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_materia = $_GET['id_materia'];
        
        // Obtener id_estudiante del usuario actual
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("
            SELECT e.id_estudiante, c.nombre as nombre_carrera 
            FROM Estudiante e 
            LEFT JOIN Carrera c ON e.id_carrera = c.id_carrera 
            WHERE e.id_usuario = ?
        ");
        $stmt->execute([$id_usuario]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asegurar que la carrera esté en la sesión para el header
        if (!isset($_SESSION['carrera'])) {
            $_SESSION['carrera'] = $estudiante['nombre_carrera'] ?? 'Sin Carrera Asignada';
        }
        
        // Verificar que el estudiante esté inscrito en la materia
        $stmt = $db->prepare("SELECT * FROM Inscripcion WHERE id_estudiante = ? AND id_materia = ? AND estado = 'Activa'");
        $stmt->execute([$estudiante['id_estudiante'], $id_materia]);
        
        if (!$stmt->fetch()) {
            $_SESSION['error'] = "No estás inscrito en esta materia o tu inscripción no está activa";
            header('Location: index.php?controller=Estudiante&action=dashboard');
            exit();
        }
        
        // Obtener tareas, materiales y calificaciones
        $tareas = $this->model->obtenerTareasMateria($id_materia, $estudiante['id_estudiante']);
        $materiales = $this->model->obtenerMaterialesMateria($id_materia);
        $materia = $this->model->obtenerInfoMateria($id_materia);
        
        // Nuevos datos para la pestaña de calificaciones
        $calificaciones = $this->model->obtenerCalificacionesMateria($id_materia, $estudiante['id_estudiante']);
        $resumen_calificaciones = $this->model->obtenerResumenCalificaciones($id_materia, $estudiante['id_estudiante']);
        
        // --- Añadido para Asistencia y Evaluación ---
        $asistencia = $this->model->obtenerAsistenciaMateria($id_materia, $estudiante['id_estudiante']);
        $ya_evaluado = $this->model->verificarEvaluacionExistente($id_materia, $estudiante['id_estudiante']);

        require_once 'views/estudiante/tareas.php';
    }

    public function entregarTarea() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_tarea = $_POST['id_tarea'];
            $id_materia = $_POST['id_materia'];
            
            // Obtener id_estudiante del usuario actual
            $id_usuario = $_SESSION['id_usuario'];
            $db = (new Database())->connect();
            $stmt = $db->prepare("SELECT id_estudiante FROM Estudiante WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($this->model->entregarTarea($id_tarea, $estudiante['id_estudiante'], $_FILES['archivo'])) {
                $_SESSION['mensaje'] = "Tarea entregada correctamente";
            } else {
                $_SESSION['error'] = "Error al entregar la tarea";
            }
            
            header("Location: index.php?controller=Estudiante&action=verTareas&id_materia=$id_materia");
            exit();
        }
    }

    public function enviarEvaluacion() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia = $_POST['id_materia'];
            $id_docente = $_POST['id_docente'];
            $puntuacion = $_POST['puntuacion'];
            
            // Unir respuestas a las preguntas guía en el comentario
            $p1 = $_POST['q1'] ?? '';
            $p2 = $_POST['q2'] ?? '';
            $p3 = $_POST['q3'] ?? '';
            
            $comentario = "¿Cómo califica la metodología del docente? $p1\n¿La comunicación con los estudiantes fue buena? $p2\nComentarios adicionales: $p3";

            // Obtener id_estudiante
            $id_usuario = $_SESSION['id_usuario'];
            $db = (new Database())->connect();
            $stmt = $db->prepare("SELECT id_estudiante FROM Estudiante WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);
            $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar que no haya evaluado ya
            if ($this->model->verificarEvaluacionExistente($id_materia, $estudiante['id_estudiante'])) {
                $_SESSION['error'] = "Ya has enviado una evaluación para este docente en esta materia.";
            } else {
                if ($this->model->guardarEvaluacionDocente($estudiante['id_estudiante'], $id_docente, $id_materia, $puntuacion, $comentario)) {
                    $_SESSION['mensaje'] = "Evaluación enviada con éxito. ¡Gracias por tu retroalimentación anónima!";
                } else {
                    $_SESSION['error'] = "Error al enviar la evaluación.";
                }
            }

            header("Location: index.php?controller=Estudiante&action=verTareas&id_materia=$id_materia&tab=evaluacion");
            exit();
        }
    }
}
?>