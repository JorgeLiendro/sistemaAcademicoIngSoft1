<?php
class AuthModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function login($correo, $contrasena) {
        $stmt = $this->db->prepare("CALL sp_ValidarLogin(?, ?)");
        $stmt->execute([$correo, $contrasena]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrarUsuario($datos) {
        $stmt = $this->db->prepare("CALL sp_RegistrarUsuario(?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $datos['nombre_completo'],
            $datos['carnet'],
            $datos['fecha_nacimiento'],
            $datos['correo_electronico'],
            $datos['numero_telefono'],
            $datos['direccion'],
            $datos['contraseña'],
            $datos['rol']
        ]);
    }

    public function obtenerUsuario($id_usuario) {
        $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>