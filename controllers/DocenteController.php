<?php
class DocenteController {

    private $model;

    public function __construct() {
        $this->model = new DocenteModel();
    }

    public function dashboard() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Obtener id_docente del usuario actual
        $id_usuario = $_SESSION['id_usuario'];
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT id_docente FROM Docente WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        $docente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $materias = $this->model->obtenerMateriasDocente($docente['id_docente']);
        require_once 'views/docente/dashboard.php';
    }

    public function gestionTareas() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_materia = $_GET['id_materia'] ?? null;

        if (!$id_materia) {
            header('Location: index.php?controller=Docente&action=dashboard');
            exit();
        }
    
        // Obtener datos de la materia, materiales y tareas
        $materia = $this->model->obtenerMateria($id_materia);
        $materiales = $this->model->obtenerMateriales($id_materia);
        $tareas = $this->model->obtenerTareasMateria($id_materia);
    
        // Obtener la tarea seleccionada (si se envió id_tarea)
        $tarea_seleccionada = null;
        $entregas = [];
        
        if (isset($_GET['id_tarea'])) {
            $tarea_seleccionada = $this->model->obtenerTarea($_GET['id_tarea']);
            $entregas = $this->model->obtenerEntregasTarea($_GET['id_tarea']);
        }
    
        // Obtener reportes estadísticos
        $reportes = $this->model->obtenerReportesMateria($id_materia);

        // Validar que reportes sea un array para evitar errores en la vista
        if (!$reportes) {
            $reportes = [
                'total_tareas' => 0,
                'tareas_calificadas' => 0,
                'total_estudiantes' => 0,
                'distribucion_calificaciones' => []
            ];
        }

        // Obtener desempeño de estudiantes
        $reportes['estudiantes'] = $this->model->obtenerDesempenoEstudiantes($id_materia);

        // Novedades: Asistencia y Evaluaciones
        $fecha_asistencia = $_GET['fecha'] ?? date('Y-m-d');
        $lista_estudiantes = $this->model->obtenerEstudiantesMateria($id_materia);
        $asistencia_registrada = $this->model->obtenerAsistenciaMateriaFecha($id_materia, $fecha_asistencia);
        $evaluaciones = $this->model->obtenerEvaluacionesMateria($id_materia);

        // Calcular promedio de evaluaciones
        $promedio_evaluaciones = 0;
        if (count($evaluaciones) > 0) {
            $suma = 0;
            foreach ($evaluaciones as $ev) { $suma += $ev['puntuacion']; }
            $promedio_evaluaciones = $suma / count($evaluaciones);
        }

        // Pasar las variables a la vista
        require_once 'views/docente/gestion_tareas.php';
    }

    public function crearTarea() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia = $_POST['id_materia'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_entrega = $_POST['fecha_entrega'];
            
            if ($this->model->crearTarea($id_materia, $titulo, $descripcion, $fecha_entrega)) {
                $_SESSION['mensaje'] = "Tarea creada correctamente";
            } else {
                $_SESSION['error'] = "Error al crear la tarea";
            }
            
            header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia");
            exit();
        }
    }

    public function calificarTarea() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_tarea = $_GET['id_tarea'];
        $entregas = $this->model->obtenerEntregasTarea($id_tarea);
        require_once 'views/docente/calificar_tareas.php';
    }

    public function guardarCalificacion() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_entrega = $_POST['id_entrega'];
            $calificacion = $_POST['calificacion'];
            $retroalimentacion = $_POST['retroalimentacion'];
            $id_tarea = $_POST['id_tarea'];
            $id_materia = $_POST['id_materia'];
            
            if ($this->model->calificarTarea($id_entrega, $calificacion, $retroalimentacion)) {
                $_SESSION['mensaje'] = "Calificación guardada correctamente";
            } else {
                $_SESSION['error'] = "Error al guardar la calificación";
            }
            
            header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia&id_tarea=$id_tarea&tab=entregas");
            exit();
        }
    }

    public function subirMaterial() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia = $_POST['id_materia'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $tipo = $_POST['tipo'];
            
            // Manejo de archivos o URL según el tipo
            $ruta = '';
            
            if ($tipo === 'Enlace') {
                $ruta = $_POST['url'];
            } else {
                // Procesar archivo subido
                if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/materiales/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
                    $nombreArchivo = uniqid() . '.' . $extension;
                    $rutaCompleta = $uploadDir . $nombreArchivo;
                    
                    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaCompleta)) {
                        $ruta = $nombreArchivo; // Guardamos solo el nombre del archivo
                    } else {
                        $_SESSION['error'] = "Error al subir el archivo";
                        header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Debes subir un archivo válido";
                    header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia");
                    exit();
                }
            }
            
            // Insertar en la base de datos
            if ($this->model->insertarMaterial($id_materia, $titulo, $descripcion, $tipo, $ruta)) {
                $_SESSION['mensaje'] = "Material subido correctamente";
            } else {
                $_SESSION['error'] = "Error al guardar el material";
            }
            
            header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia");
            exit();
        }
    }

    private function generarReportes($id_materia) {
        $reportes = [];
        
        // Datos básicos
        $reportes['total_tareas'] = $this->contarTareas($id_materia);
        $reportes['tareas_calificadas'] = $this->contarTareasCalificadas($id_materia);
        $reportes['total_estudiantes'] = $this->contarEstudiantesInscritos($id_materia);
        
        // Datos por estudiante
        $reportes['estudiantes'] = $this->obtenerReporteEstudiantes($id_materia);
        
        // Datos para gráficos
        $reportes['distribucion_calificaciones'] = $this->obtenerDistribucionCalificaciones($id_materia);
        
        return $reportes;
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF() {
        // Verificar sesión y rol
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Limpiar cualquier salida previa
        if (ob_get_length()) ob_clean();
        
        $id_materia = $_GET['id_materia'] ?? null;
        if (!$id_materia) {
            die('ID de materia no especificado');
        }
        
        // Obtener ID de usuario desde la sesión
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        if (!$id_usuario) {
            die('No se pudo identificar al usuario');
        }
        
        // Obtener los datos necesarios
        $reportes = $this->generarReportes($id_materia);
        
        // Verificar estructura de reportes
        if (!is_array($reportes) || !isset($reportes['estudiantes'])) {
            die('Los datos del reporte no tienen la estructura esperada');
        }
        
        // Obtener información del docente
        $docente = $this->model->obtenerDocentePorUsuario($id_usuario);
        if (!is_array($docente) || !isset($docente['nombre_completo'])) {
            die('No se encontró información completa del docente');
        }
        
        // Obtener información de la materia
        $materia = $this->model->obtenerMateria($id_materia);
        if (!is_array($materia) || !isset($materia['nombre'])) {
            die('No se pudo obtener información de la materia');
        }
        
        // Configurar valores por defecto
        $reportes['total_tareas'] = $reportes['total_tareas'] ?? 0;
        $reportes['tareas_calificadas'] = $reportes['tareas_calificadas'] ?? 0;
        $reportes['total_estudiantes'] = $reportes['total_estudiantes'] ?? 0;
        $reportes['estudiantes'] = is_array($reportes['estudiantes']) ? $reportes['estudiantes'] : [];
        
        // Configurar PDF
        require_once 'vendor/tecnickcom/tcpdf/tcpdf.php';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Configuración del documento
        $pdf->SetCreator('SmartAcademia');
        $pdf->SetAuthor($docente['nombre_completo']);
        $pdf->SetTitle('Reporte de ' . $materia['nombre']);
        $pdf->SetSubject('Reporte Académico');
        
        // Configurar márgenes
        $pdf->SetMargins(15, 25, 15);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 25);
        
        // Agregar página
        $pdf->AddPage();
        
        // Obtener la ruta del logo
        $logoPath = 'assets/img/logo.jpg';
        $logo = file_exists($logoPath) ? '<img class="logo" src="' . $logoPath . '" />' : '';
        
        // Contenido HTML del reporte
        $html = '
        <style>
            .header { text-align: center; margin-bottom: 10px; }
            .logo { height: 60px; }
            .title { font-size: 18px; font-weight: bold; color: #2c3e50; margin-top: 10px; }
            .subtitle { font-size: 14px; color: #7f8c8d; margin-bottom: 15px; }
            .info-box { background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 10px; margin-bottom: 15px; }
            .info-label { font-weight: bold; color: #3498db; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th { background-color: #2c3e50; color: white; font-weight: bold; padding: 8px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #dee2e6; }
            tr:nth-child(even) { background-color: #f8f9fa; }
            .footer { font-size: 10px; text-align: center; color: #7f8c8d; margin-top: 20px; }
            .highlight { font-weight: bold; color: #e74c3c; }
        </style>
        
        <div class="header">
            ' . $logo . '
            <div class="title">Reporte de Calificaciones</div>
            <div class="subtitle">SmartAcademia - ' . date('d/m/Y H:i') . '</div>
        </div>
        
        <div class="info-box">
            <div><span class="info-label">Materia:</span> ' . htmlspecialchars($materia['nombre']) . '</div>
            <div><span class="info-label">Docente:</span> ' . htmlspecialchars($docente['nombre_completo']) . '</div>
            <div><span class="info-label">Total estudiantes:</span> ' . $reportes['total_estudiantes'] . '</div>
            <div><span class="info-label">Total tareas:</span> ' . $reportes['total_tareas'] . '</div>
            <div><span class="info-label">Tareas calificadas:</span> ' . $reportes['tareas_calificadas'] . '</div>
            <div><span class="info-label">Fecha de generación:</span> ' . date('d/m/Y H:i') . '</div>
        </div>';
        
        // Tabla de estudiantes
        if (!empty($reportes['estudiantes'])) {
            $html .= '<h3>Detalle por Estudiante</h3>
            <table>
                <thead>
                    <tr>
                        <th width="30%">Estudiante</th>
                        <th width="17%">Entregas</th>
                        <th width="17%">Promedio</th>
                        <th width="17%">Pendientes</th>
                        <th width="19%">Estado</th>
                    </tr>
                </thead>
                <tbody>';
            
            foreach ($reportes['estudiantes'] as $estudiante) {
                if (!is_array($estudiante)) continue;
                
                $nombre = htmlspecialchars($estudiante['nombre'] ?? 'N/A');
                $entregadas = $estudiante['entregadas'] ?? 0;
                $promedio = $estudiante['promedio'] ?? 0;
                $pendientes = $estudiante['pendientes'] ?? 0;
                
                $estado = ($promedio >= 7) ? 'Aprobado' : (($promedio >= 5) ? 'Regular' : 'Riesgo');
                $color = ($estado == 'Aprobado') ? '#27ae60' : (($estado == 'Regular') ? '#f39c12' : '#e74c3c');
                
                $html .= '<tr>
                    <td>' . $nombre . '</td>
                    <td>' . $entregadas . '</td>
                    <td>' . number_format($promedio, 2) . '</td>
                    <td>' . $pendientes . '</td>
                    <td style="color: ' . $color . '">' . $estado . '</td>
                </tr>';
            }
            
            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="highlight">No hay estudiantes registrados para esta materia.</p>';
        }
        
        // Pie de página
        $html .= '<div class="footer">
            Reporte generado automáticamente por SmartAcademia.<br>
            © ' . date('Y') . ' - Todos los derechos reservados.
        </div>';
        
        // Generar el PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('reporte_' . preg_replace('/[^a-zA-Z0-9]/', '_', $materia['nombre']) . '_' . date('Ymd_His') . '.pdf', 'D');
        exit();
    }

    /**
     * Exportar reporte a Excel
     */
    public function exportarExcel() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_materia = $_GET['id_materia'];
        $reportes = $this->generarReportes($id_materia);
        $materia = $this->model->obtenerMateria($id_materia);
        
        // Configurar Excel (implementación básica)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="reporte_' . $materia['nombre'] . '_' . date('Ymd') . '.xls"');
        
        echo '<table border="1">
            <tr>
                <th colspan="4">Reporte de Calificaciones - ' . $materia['nombre'] . '</th>
            </tr>
            <tr>
                <th>Estudiante</th>
                <th>Entregas</th>
                <th>Promedio</th>
                <th>Pendientes</th>
            </tr>';
        
        foreach ($reportes['estudiantes'] as $estudiante) {
            echo '<tr>
                <td>' . $estudiante['nombre'] . '</td>
                <td>' . $estudiante['entregadas'] . '</td>
                <td>' . number_format($estudiante['promedio'], 2) . '</td>
                <td>' . $estudiante['pendientes'] . '</td>
            </tr>';
        }
        
        echo '</table>';
        exit();
    }

    /**
     * Métodos auxiliares para reportes
     */
    private function contarTareas($id_materia) {
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT COUNT(*) FROM tarea WHERE id_materia = ?");
        $stmt->execute([$id_materia]);
        return $stmt->fetchColumn();
    }

    private function contarTareasCalificadas($id_materia) {
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT COUNT(DISTINCT id_tarea) FROM entrega 
                             WHERE calificacion IS NOT NULL AND id_tarea IN 
                             (SELECT id_tarea FROM tarea WHERE id_materia = ?)");
        $stmt->execute([$id_materia]);
        return $stmt->fetchColumn();
    }

    private function contarEstudiantesInscritos($id_materia) {
        $db = (new Database())->connect();
        $stmt = $db->prepare("SELECT COUNT(*) FROM inscripcion 
                             WHERE id_materia = ? AND estado = 'Activa'");
        $stmt->execute([$id_materia]);
        return $stmt->fetchColumn();
    }

    private function obtenerReporteEstudiantes($id_materia) {
        $db = (new Database())->connect();
        $query = "SELECT 
                    e.id_estudiante,
                    u.nombre_completo AS nombre,
                    COUNT(en.id_entrega) AS entregadas,
                    AVG(en.calificacion) AS promedio,
                    MIN(en.calificacion) AS minima,
                    MAX(en.calificacion) AS maxima,
                    (SELECT COUNT(*) FROM tarea WHERE id_materia = ?) - COUNT(en.id_entrega) AS pendientes
                  FROM inscripcion i
                  JOIN estudiante e ON i.id_estudiante = e.id_estudiante
                  JOIN usuario u ON e.id_usuario = u.id_usuario
                  LEFT JOIN entrega en ON e.id_estudiante = en.id_estudiante 
                    AND en.id_tarea IN (SELECT id_tarea FROM tarea WHERE id_materia = ?)
                  WHERE i.id_materia = ?
                  GROUP BY e.id_estudiante, u.nombre_completo";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id_materia, $id_materia, $id_materia]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerDistribucionCalificaciones($id_materia) {
        $db = (new Database())->connect();
        $query = "SELECT 
                    SUM(CASE WHEN calificacion BETWEEN 0 AND 49 THEN 1 ELSE 0 END) AS rango_0_49,
                    SUM(CASE WHEN calificacion BETWEEN 50 AND 69 THEN 1 ELSE 0 END) AS rango_50_69,
                    SUM(CASE WHEN calificacion BETWEEN 70 AND 89 THEN 1 ELSE 0 END) AS rango_70_89,
                    SUM(CASE WHEN calificacion BETWEEN 90 AND 100 THEN 1 ELSE 0 END) AS rango_90_100
                  FROM entrega
                  WHERE id_tarea IN (SELECT id_tarea FROM tarea WHERE id_materia = ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_materia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarAsistencia() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia  = $_POST['id_materia'];
            $fecha       = $_POST['fecha'];
            $asistencias = $_POST['asistencia'] ?? [];
            
            $errores = 0;
            foreach ($asistencias as $id_estudiante => $estado) {
                if (!$this->model->registrarAsistencia($id_materia, $id_estudiante, $fecha, $estado)) {
                    $errores++;
                }
            }
            
            if ($errores === 0) {
                $_SESSION['mensaje'] = "Asistencia guardada correctamente para el " . date('d/m/Y', strtotime($fecha));
            } else {
                $_SESSION['error'] = "Hubo algunos errores al guardar la asistencia.";
            }
            
            header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia&fecha=$fecha&tab=asistencia");
            exit();
        }
    }

    public function editarFechaTarea() {
        if ($_SESSION['rol'] !== 'Docente') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_tarea    = (int)$_POST['id_tarea'];
            $id_materia  = (int)$_POST['id_materia'];
            $fecha_nueva = $_POST['fecha_entrega'];

            $db   = (new Database())->connect();
            $stmt = $db->prepare("UPDATE Tarea SET fecha_entrega = ? WHERE id_tarea = ?");

            if ($stmt->execute([$fecha_nueva, $id_tarea])) {
                $_SESSION['mensaje'] = "Fecha límite actualizada correctamente.";
            } else {
                $_SESSION['error'] = "No se pudo actualizar la fecha.";
            }

            header("Location: index.php?controller=Docente&action=gestionTareas&id_materia=$id_materia&tab=tareas");
            exit();
        }
    }
}
?>
