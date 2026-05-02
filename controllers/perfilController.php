<?php
class PerfilController {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    public function ver() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $usuario = $this->obtenerUsuario($id_usuario);
        
        if (!$usuario) {
            $_SESSION['mensaje'] = 'Usuario no encontrado';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        // Actualizar la foto en sesión cada vez que se ve el perfil
        $_SESSION['foto_perfil'] = $usuario['foto_perfil'];
        
        $info_adicional = [];
        if ($_SESSION['rol'] === 'Estudiante') {
            $info_adicional = $this->obtenerInfoEstudiante($id_usuario);
        } elseif ($_SESSION['rol'] === 'Docente') {
            $info_adicional = $this->obtenerInfoDocente($id_usuario);
        }
        
        require_once 'views/perfil/ver.php';
    }
    
    public function editar() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        $usuario = $this->obtenerUsuario($id_usuario);
        
        if (!$usuario) {
            $_SESSION['mensaje'] = 'Usuario no encontrado';
            $_SESSION['tipo_mensaje'] = 'danger';
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        require_once 'views/perfil/editar.php';
    }
    
    public function actualizar() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['id_usuario'];
            $datos = [
                'nombre_completo' => trim($_POST['nombre_completo']),
                'carnet' => trim($_POST['carnet']),
                'fecha_nacimiento' => trim($_POST['fecha_nacimiento']),
                'correo_electronico' => trim($_POST['correo_electronico']),
                'numero_telefono' => trim($_POST['numero_telefono']),
                'direccion' => trim($_POST['direccion']),
                'id_usuario' => $id_usuario
            ];
            
            if (!empty($_FILES['foto_perfil']['name'])) {
                $foto = $this->subirFotoPerfil($id_usuario);
                if ($foto) {
                    $datos['foto_perfil'] = $foto;
                }
            }
            
            if ($this->actualizarUsuario($datos)) {
                $_SESSION['mensaje'] = 'Perfil actualizado correctamente';
                $_SESSION['tipo_mensaje'] = 'success';
                $_SESSION['nombre'] = $datos['nombre_completo'];
                if (isset($datos['foto_perfil'])) {
                    $_SESSION['foto_perfil'] = $datos['foto_perfil'];
                }
            } else {
                $_SESSION['mensaje'] = 'Error al actualizar el perfil';
                $_SESSION['tipo_mensaje'] = 'danger';
            }
            
            header('Location: index.php?controller=Perfil&action=ver');
            exit();
        }
    }
    
    public function cambiarPassword() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        require_once 'views/perfil/cambiar_password.php';
    }
    
    public function actualizarPassword() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['id_usuario'];
            $password_actual = $_POST['password_actual'];
            $nuevo_password = $_POST['nuevo_password'];
            $confirmar_password = $_POST['confirmar_password'];
            
            if (strlen($nuevo_password) < 6) {
                $_SESSION['mensaje'] = 'La nueva contraseña debe tener al menos 6 caracteres';
                $_SESSION['tipo_mensaje'] = 'danger';
                header('Location: index.php?controller=Perfil&action=cambiarPassword');
                exit();
            }
            
            if ($nuevo_password !== $confirmar_password) {
                $_SESSION['mensaje'] = 'Las contraseñas no coinciden';
                $_SESSION['tipo_mensaje'] = 'danger';
                header('Location: index.php?controller=Perfil&action=cambiarPassword');
                exit();
            }
            
            $usuario = $this->obtenerUsuario($id_usuario);
            if (hash('sha256', $password_actual) !== $usuario['contraseña']) {
                $_SESSION['mensaje'] = 'La contraseña actual es incorrecta';
                $_SESSION['tipo_mensaje'] = 'danger';
                header('Location: index.php?controller=Perfil&action=cambiarPassword');
                exit();
            }
            
            $nuevo_password_hash = hash('sha256', $nuevo_password);
            if ($this->actualizarContraseña($id_usuario, $nuevo_password_hash)) {
                $_SESSION['mensaje'] = 'Contraseña actualizada correctamente';
                $_SESSION['tipo_mensaje'] = 'success';
                header('Location: index.php?controller=Perfil&action=ver');
                exit();
            } else {
                $_SESSION['mensaje'] = 'Error al actualizar la contraseña';
                $_SESSION['tipo_mensaje'] = 'danger';
                header('Location: index.php?controller=Perfil&action=cambiarPassword');
                exit();
            }
        }
    }
    
    private function subirFotoPerfil($id_usuario) {
        $directorio = "assets/img/usuarios/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        $nombreArchivo = "perfil_" . $id_usuario . "_" . time() . "." . pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $rutaCompleta = $directorio . $nombreArchivo;
        
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, $extensionesPermitidas)) {
            $_SESSION['mensaje'] = 'Solo se permiten archivos JPG, JPEG, PNG o GIF';
            $_SESSION['tipo_mensaje'] = 'danger';
            return false;
        }
        
        if ($_FILES['foto_perfil']['size'] > 2097152) {
            $_SESSION['mensaje'] = 'El archivo es demasiado grande (máximo 2MB)';
            $_SESSION['tipo_mensaje'] = 'danger';
            return false;
        }
        
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaCompleta)) {
            $usuario = $this->obtenerUsuario($id_usuario);
            if ($usuario['foto_perfil'] != 'assets/img/perfil1.jpg' && file_exists($usuario['foto_perfil'])) {
                unlink($usuario['foto_perfil']);
            }
            return $rutaCompleta;
        }
        
        return false;
    }
    
    private function obtenerUsuario($id_usuario) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function obtenerInfoEstudiante($id_usuario) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT e.* FROM Estudiante e 
                              JOIN Usuario u ON e.id_usuario = u.id_usuario
                              WHERE u.id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function obtenerInfoDocente($id_usuario) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT d.* FROM Docente d 
                              JOIN Usuario u ON d.id_usuario = u.id_usuario
                              WHERE u.id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function actualizarUsuario($datos) {
        $conn = $this->db->getConnection();
        $sql = "UPDATE Usuario SET 
                nombre_completo = :nombre_completo,
                carnet = :carnet,
                fecha_nacimiento = :fecha_nacimiento,
                correo_electronico = :correo_electronico,
                numero_telefono = :numero_telefono,
                direccion = :direccion";
        
        if (isset($datos['foto_perfil'])) {
            $sql .= ", foto_perfil = :foto_perfil";
        }
        
        $sql .= " WHERE id_usuario = :id_usuario";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute($datos);
    }
    
    private function actualizarContraseña($id_usuario, $nuevo_password_hash) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE Usuario SET contraseña = ? WHERE id_usuario = ?");
        return $stmt->execute([$nuevo_password_hash, $id_usuario]);
    }
}