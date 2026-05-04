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

        ////// EVALUACION////
    // Dentro de la clase EstudianteController
    public function resolverEvaluacion() {
        if ($_SESSION['rol'] !== 'Estudiante') { header('Location: index.php'); exit(); }
        
        $id_evaluacion = $_GET['id_evaluacion'];
        // Validar si el estudiante ya resolvió este examen antes
        $ya_realizado = $this->model->verificarExamenHecho($_SESSION['id_usuario'], $id_evaluacion);
        
        if ($ya_realizado) {
            $_SESSION['error'] = "Ya has completado esta evaluación.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
        
        $examen = $this->model->obtenerDetalleExamen($id_evaluacion);
        require_once 'views/estudiante/resolver_examen.php';
    }


    public function verResultado() {
        // 1. Capturamos el ID que viene en la URL (?id=...)
        $id_evaluacion = $_GET['id'];
        $id_usuario = $_SESSION['id_usuario'];

        if (!$id_evaluacion) {
            header("Location: index.php?controller=Estudiante&action=materias");
            exit();
        }

        // 2. Cargamos la vista. Al estar en el mismo ámbito, 
        // la variable $id_evaluacion ahora será visible para el archivo PHP de la vista.
        require_once 'views/estudiante/ver_resultado.php';
    }

    public function enviarExamen() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_usuario = $_SESSION['id_usuario']; // ID del estudiante en sesión
            $id_evaluacion = $_POST['id_evaluacion'];
            $respuestas = $_POST['respuestas']; // El array [id_pregunta => id_opcion]

            // 1. Verificar que no lo haya hecho ya
            if ($this->model->verificarExamenHecho($id_usuario, $id_evaluacion)) {
                $_SESSION['error'] = "Ya has rendido esta evaluación anteriormente.";
                header("Location: index.php?controller=Estudiante&action=materias");
                exit();
            }

            // 2. Procesar la calificación en el modelo
            $nota = $this->model->procesarCalificacion($id_usuario, $id_evaluacion, $respuestas);

            if ($nota !== false) {
                $_SESSION['exito'] = "Examen enviado correctamente. Tu nota se publicará al finalizar el tiempo.";
                header("Location: index.php?controller=Estudiante&action=verResultado&id=" . $id_evaluacion);
            } else {
                $_SESSION['error'] = "Hubo un error al procesar tu examen.";
                header("Location: index.php?controller=Estudiante&action=materias");
            }
            exit();
        }
    }

    ///////

    
    // NUEVA FUNCIÓN: Descargar Boletín en PDF usando TCPDF
    public function descargarBoletinPDF() {
        if ($_SESSION['rol'] !== 'Estudiante') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

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

        // Obtener las notas usando la función que agregamos al modelo
        $notas = $this->model->obtenerBoletin($estudiante['id_estudiante']);

        // Incluir la librería TCPDF
        require_once 'libs/tcpdf/Tcpdf.php';

        // Crear instancia de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Configuración básica del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Sistema Académico');
        $pdf->SetTitle('Boletín de Notas');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'BOLETÍN OFICIAL DE CALIFICACIONES', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Estudiante: ' . $_SESSION['nombre_usuario'], 0, 1, 'L');
        $pdf->Ln(5);

        // Crear la tabla HTML para las notas
        $html = '<table border="1" cellpadding="5" cellspacing="0" style="text-align:center;">
                    <tr style="background-color:#d9edf7; font-weight:bold;">
                        <th width="35%">Materia</th>
                        <th width="12%">Tri 1</th>
                        <th width="12%">Tri 2</th>
                        <th width="12%">Tri 3</th>
                        <th width="12%">Final</th>
                        <th width="17%">Estado</th>
                    </tr>';

        if (empty($notas)) {
            $html .= '<tr><td colspan="6">No hay calificaciones registradas aún.</td></tr>';
        } else {
            foreach ($notas as $nota) {
                $html .= '<tr>
                            <td style="text-align:left;">' . htmlspecialchars($nota['materia']) . '</td>
                            <td>' . number_format($nota['nota_trimestre1'], 2) . '</td>
                            <td>' . number_format($nota['nota_trimestre2'], 2) . '</td>
                            <td>' . number_format($nota['nota_trimestre3'], 2) . '</td>
                            <td><b>' . number_format($nota['nota_final'], 2) . '</b></td>
                            <td>' . htmlspecialchars($nota['observacion']) . '</td>
                          </tr>';
            }
        }

        $html .= '</table>';

        // Imprimir el HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Forzar descarga del archivo
        $pdf->Output('Boletin_Notas_' . date('Y-m-d') . '.pdf', 'D');
        exit();
    }
}
?>
