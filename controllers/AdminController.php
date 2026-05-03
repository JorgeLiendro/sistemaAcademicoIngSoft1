<?php
class AdminController {
    private $model;

    public function __construct() {
        $this->model = new AdminModel();
    }

    public function dashboard() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $usuarios = $this->model->obtenerTodosUsuarios();
        $materias = $this->model->obtenerTodasMaterias();
        
        require_once 'views/admin/dashboard.php';
    }

    public function gestionUsuarios() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Obtener parámetros de filtrado
        $filtroRol = $_GET['filtroRol'] ?? '';
        $busqueda = $_GET['busqueda'] ?? '';
        $itemsPorPagina = $_GET['itemsPorPagina'] ?? 10;
        
        // Llamar al modelo con los filtros
        $usuarios = $this->model->obtenerUsuariosFiltrados($filtroRol, $busqueda);
        
        require_once 'views/admin/gestion_usuarios.php';
    }

    public function nuevoUsuario() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $carreras = $this->model->obtenerTodasCarreras();
        require_once 'views/admin/nuevo_usuario.php';
    }

    public function guardarUsuario() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $datos = [
                    'nombre_completo' => trim($_POST['nombre_completo']),
                    'carnet' => trim($_POST['carnet']),
                    'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                    'correo_electronico' => trim($_POST['correo_electronico']),
                    'numero_telefono' => trim($_POST['numero_telefono']),
                    'direccion' => trim($_POST['direccion']),
                    'contraseña' => $_POST['contrasena'],
                    'rol' => $_POST['rol'],
                    'id_carrera' => $_POST['id_carrera'] ?? null
                ];
    
                // Validaciones básicas
                if (empty($datos['nombre_completo']) || empty($datos['correo_electronico']) || empty($datos['rol']) || empty($datos['contraseña'])) {
                    throw new Exception("Todos los campos obligatorios deben completarse");
                }
    
                $adminModel = new AdminModel();
                if ($adminModel->registrarUsuario($datos)) {
                    $_SESSION['mensaje'] = "Usuario registrado correctamente";
                    header('Location: index.php?controller=Admin&action=gestionUsuarios');
                } else {
                    throw new Exception("Error al registrar el usuario en la base de datos");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?controller=Admin&action=nuevoUsuario');
            }
            exit();
        }
        
        // Si no es POST, redirigir
        header('Location: index.php?controller=Admin&action=nuevoUsuario');
        exit();
    }

    // ... (resto de métodos)
    public function editarUsuario() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_GET['id'];
        $usuario = $this->model->obtenerUsuarioPorId($id_usuario);
        
        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado";
            header('Location: index.php?controller=Admin&action=gestionUsuarios');
            exit();
        }
        
        require_once 'views/admin/editar_usuario.php';
    }

    public function actualizarUsuario() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_POST['id_usuario'];
        $datos = [
            'nombre_completo' => $_POST['nombre_completo'],
            'carnet' => $_POST['carnet'],
            'fecha_nacimiento' => $_POST['fecha_nacimiento'],
            'correo_electronico' => $_POST['correo_electronico'],
            'numero_telefono' => $_POST['numero_telefono'],
            'direccion' => $_POST['direccion'],
            'rol' => $_POST['rol']
        ];
        
        if ($this->model->actualizarUsuario($id_usuario, $datos)) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el usuario";
        }
        
        header('Location: index.php?controller=Admin&action=gestionUsuarios');
        exit();
    }

    public function eliminarUsuario() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_GET['id'];
        if ($this->model->eliminarUsuario($id_usuario)) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        
        header('Location: index.php?controller=Admin&action=gestionUsuarios');
        exit();
    }

    public function gestionMaterias() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Obtener parámetros de filtrado
        $filtroDocente = $_GET['filtroDocente'] ?? '';
        $busqueda = $_GET['busqueda'] ?? '';
        
        // Llamar al modelo con los filtros
        $materias = $this->model->obtenerMateriasFiltradas($filtroDocente, $busqueda);
        $docentes = $this->model->obtenerDocentes();
        $carreras = $this->model->obtenerTodasCarreras();
        $periodos = $this->model->obtenerTodosPeriodos();
        
        require_once 'views/admin/gestion_materias.php';
    }

    public function crearMateria() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_docente = $_POST['id_docente'];
            $id_carrera = $_POST['id_carrera'] ?? null;
            $id_periodo = $_POST['id_periodo'] ?? null;
            $nivel_semestre = $_POST['nivel_semestre'] ?? '1';
            $grupo = $_POST['grupo'] ?? 'A';
            $turno = $_POST['turno'] ?? 'Mañana';
            
            if ($this->model->crearMateria($nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno)) {
                $_SESSION['mensaje'] = "Materia creada correctamente";
            } else {
                $_SESSION['error'] = "Error al crear la materia";
            }
            
            header('Location: index.php?controller=Admin&action=gestionMaterias');
            exit();
        }
    }
        // ... (métodos existentes)

    public function editarMateria() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_materia = $_GET['id'];
        $materiaModel = new MateriaModel();
        $adminModel = new AdminModel();
        
        $materia = $materiaModel->obtenerMateriaPorId($id_materia);
        $docentes = $materiaModel->obtenerDocentes();
        $carreras = $adminModel->obtenerTodasCarreras();
        $periodos = $adminModel->obtenerTodosPeriodos();
        
        if (!$materia) {
            $_SESSION['error'] = "Materia no encontrada";
            header('Location: index.php?controller=Admin&action=gestionMaterias');
            exit();
        }
        
        require_once 'views/admin/editar_materia.php';
    }

    public function actualizarMateria() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_materia = $_POST['id_materia'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            $id_docente = $_POST['id_docente'];
            $id_carrera = $_POST['id_carrera'] ?? null;
            $id_periodo = $_POST['id_periodo'] ?? null;
            $nivel_semestre = $_POST['nivel_semestre'] ?? '1';
            $grupo = $_POST['grupo'] ?? 'A';
            $turno = $_POST['turno'] ?? 'Mañana';
            
            $materiaModel = new MateriaModel();
            if ($materiaModel->actualizarMateria($id_materia, $nombre, $descripcion, $id_docente, $id_carrera, $id_periodo, $nivel_semestre, $grupo, $turno)) {
                $_SESSION['mensaje'] = "Materia actualizada correctamente";
            } else {
                $_SESSION['error'] = "Error al actualizar la materia";
            }
            
            header('Location: index.php?controller=Admin&action=gestionMaterias');
            exit();
        }
    }

    // ... (resto de métodos existentes)

    public function eliminarMateria() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_materia = $_GET['id'];
        if ($this->model->eliminarMateria($id_materia)) {
            $_SESSION['mensaje'] = "Materia eliminada correctamente";
        } else {
            $_SESSION['error'] = "Error al eliminar la materia";
        }
        
        header('Location: index.php?controller=Admin&action=gestionMaterias');
        exit();
    }
    
    public function obtenerNotificaciones() {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Obtener notificaciones del usuario (si es que las notificaciones son personales)
        // Si son globales, simplemente obtén todas
        $query = "SELECT * FROM notificaciones ORDER BY fecha DESC LIMIT 10";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener notificaciones no leídas
        $query_unread = "SELECT COUNT(*) FROM notificaciones WHERE leida = 0";
        if (isset($_SESSION['id_usuario'])) {
            $query_unread .= " AND usuario_id = :usuario_id";
        }
        
        $stmt_unread = $conn->prepare($query_unread);
        if (isset($_SESSION['id_usuario'])) {
            $stmt_unread->bindParam(':usuario_id', $_SESSION['id_usuario']);
        }
        $stmt_unread->execute();
        $notificaciones_sin_leer = $stmt_unread->fetchColumn();
        
        return [
            'notificaciones' => $notificaciones,
            'notificaciones_sin_leer' => $notificaciones_sin_leer
        ];
    }
    
    public function marcarNotificacionesLeidas() {
        if (isset($_SESSION['id_usuario'])) {
            $db = new Database();
            $conn = $db->getConnection();
            
            $query = "UPDATE notificaciones SET leida = 1 WHERE usuario_id = :usuario_id AND leida = 0";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':usuario_id', $_SESSION['id_usuario']);
            $stmt->execute();
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    public function verTodasNotificaciones() {
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM notificaciones ORDER BY fecha DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Incluir la vista de notificaciones
        require_once 'views/admin/notificaciones.php';
    }

    // --- Gestión de Carreras ---
    public function gestionCarreras() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        $carreras = $this->model->obtenerTodasCarreras();
        require_once 'views/admin/gestion_carreras.php';
    }

    public function crearCarrera() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->crearCarrera($_POST['nombre'], $_POST['descripcion'], $_POST['estado'])) {
                $_SESSION['mensaje'] = "Carrera creada correctamente";
            } else { $_SESSION['error'] = "Error al crear la carrera"; }
            header('Location: index.php?controller=Admin&action=gestionCarreras');
            exit();
        }
    }

    public function actualizarCarrera() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->actualizarCarrera($_POST['id_carrera'], $_POST['nombre'], $_POST['descripcion'], $_POST['estado'])) {
                $_SESSION['mensaje'] = "Carrera actualizada correctamente";
            } else { $_SESSION['error'] = "Error al actualizar la carrera"; }
            header('Location: index.php?controller=Admin&action=gestionCarreras');
            exit();
        }
    }

    public function eliminarCarrera() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($this->model->eliminarCarrera($_GET['id'])) {
            $_SESSION['mensaje'] = "Carrera eliminada correctamente";
        } else { $_SESSION['error'] = "Error al eliminar la carrera"; }
        header('Location: index.php?controller=Admin&action=gestionCarreras');
        exit();
    }

    // --- Gestión de Periodos ---
    public function gestionPeriodos() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        $periodos = $this->model->obtenerTodosPeriodos();
        require_once 'views/admin/gestion_periodos.php';
    }

    public function crearPeriodo() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->crearPeriodo($_POST['nombre'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['estado'])) {
                $_SESSION['mensaje'] = "Periodo creado correctamente";
            } else { $_SESSION['error'] = "Error al crear el periodo"; }
            header('Location: index.php?controller=Admin&action=gestionPeriodos');
            exit();
        }
    }

    public function actualizarPeriodo() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->model->actualizarPeriodo($_POST['id_periodo'], $_POST['nombre'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['estado'])) {
                $_SESSION['mensaje'] = "Periodo actualizado correctamente";
            } else { $_SESSION['error'] = "Error al actualizar el periodo"; }
            header('Location: index.php?controller=Admin&action=gestionPeriodos');
            exit();
        }
    }

    public function eliminarPeriodo() {
        if ($_SESSION['rol'] !== 'Administrador') { header('Location: index.php?controller=Auth&action=login'); exit(); }
        if ($this->model->eliminarPeriodo($_GET['id'])) {
            $_SESSION['mensaje'] = "Periodo eliminado correctamente";
        } else { $_SESSION['error'] = "Error al eliminar el periodo"; }
        header('Location: index.php?controller=Admin&action=gestionPeriodos');
        exit();
    }

    public function reportes() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        require_once 'views/admin/reportes.php';
    }

    public function generarReporte() {
        if ($_SESSION['rol'] !== 'Administrador') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $tipo = $_GET['tipo'] ?? null;

        if (!$tipo) {
            header('Location: index.php?controller=Admin&action=reportes');
            exit();
        }

        switch ($tipo) {
            case 'desempenoCarrera':
                $this->reporteDesempenoCarrera();
                break;
            case 'docentes':
                $this->reporteDocentes();
                break;
            case 'asistencia':
                $this->reporteAsistencia();
                break;
            case 'calificaciones':
                $this->reporteCalificaciones();
                break;
            case 'inscripciones':
                $this->reporteInscripciones();
                break;
            case 'evaluacionesDocentes':
                $this->reporteEvaluacionesDocentes();
                break;
            default:
                header('Location: index.php?controller=Admin&action=reportes');
                exit();
        }
    }

    private function reporteDesempenoCarrera() {
        $carreras = $this->model->obtenerTodasCarreras();
        $reporteData = [];
        
        foreach ($carreras as $carrera) {
            $stats = $this->model->obtenerEstadisticasCarrera($carrera['id_carrera']);
            $reporteData[$carrera['nombre']] = $stats;
        }

        require_once 'views/admin/reportes/desempenoCarrera.php';
    }

    private function reporteDocentes() {
        $docentes = $this->model->obtenerEstadisticasDocentes();
        require_once 'views/admin/reportes/docentes.php';
    }

    private function reporteAsistencia() {
        $asistencia = $this->model->obtenerEstadisticasAsistencia();
        require_once 'views/admin/reportes/asistencia.php';
    }

    private function reporteCalificaciones() {
        $calificaciones = $this->model->obtenerEstadisticasCalificaciones();
        require_once 'views/admin/reportes/calificaciones.php';
    }

    private function reporteInscripciones() {
        $inscripciones = $this->model->obtenerEstadisticasInscripciones();
        require_once 'views/admin/reportes/inscripciones.php';
    }

    private function reporteEvaluacionesDocentes() {
        $evaluaciones = $this->model->obtenerEstadisticasEvaluaciones();
        require_once 'views/admin/reportes/evaluacionesDocentes.php';
    }
}
?>