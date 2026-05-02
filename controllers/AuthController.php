<?php
class AuthController {
    private $model;

    public function __construct() {
        $this->model = new AuthModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            
            $usuario = $this->model->login($correo, $contrasena);
            
            if ($usuario && $usuario['id_usuario']) {
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                $_SESSION['correo_electronico'] = $usuario['correo_electronico'];
                $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? 'assets/img/perfil1.jpg';
                
                switch ($usuario['rol']) {
                    case 'Administrador':
                        header('Location: index.php?controller=Admin&action=dashboard');
                        break;
                    case 'Docente':
                        header('Location: index.php?controller=Docente&action=dashboard');
                        break;
                    case 'Estudiante':
                        // Obtener nombre de la carrera para el estudiante
                        $db = (new Database())->connect();
                        $stmt = $db->prepare("
                            SELECT c.nombre as nombre_carrera 
                            FROM Estudiante e 
                            LEFT JOIN Carrera c ON e.id_carrera = c.id_carrera 
                            WHERE e.id_usuario = ?
                        ");
                        $stmt->execute([$usuario['id_usuario']]);
                        $carrera = $stmt->fetchColumn();
                        $_SESSION['carrera'] = $carrera ? $carrera : 'Sin Carrera Asignada';
                        
                        header('Location: index.php?controller=Estudiante&action=inicio');
                        break;
                }
                exit();
            } else {
                $error = "Credenciales incorrectas";
                require_once 'views/auth/login.php';
            }
        } else {
            require_once 'views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?controller=Auth&action=login');
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre_completo' => $_POST['nombre_completo'],
                'carnet' => $_POST['carnet'],
                'fecha_nacimiento' => $_POST['fecha_nacimiento'],
                'correo_electronico' => $_POST['correo_electronico'],
                'numero_telefono' => $_POST['numero_telefono'],
                'direccion' => $_POST['direccion'],
                'contraseña' => $_POST['contraseña'],
                'rol' => $_POST['rol']
            ];
            
            if ($this->model->registrarUsuario($datos)) {
                header('Location: index.php?controller=Auth&action=login');
                exit();
            } else {
                $error = "Error al registrar el usuario";
                require_once 'views/auth/register.php';
            }
        } else {
            require_once 'views/auth/register.php';
        }
    }
}
?>