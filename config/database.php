<?php
class Database {
    private $host = 'localhost';
    private $port = 3307;
    private $db_name = 'sistemaacademico';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Constructor que establece la conexión automáticamente
    public function __construct() {
        $this->connect();
    }

    // Método de conexión
    public function connect() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $this->conn;
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Método para obtener la conexión directamente
    public function getConnection() {
        return $this->conn;
    }

    public function agregarNotificacion($titulo, $mensaje, $tipo = 'info', $icono = 'fa-info-circle', $usuario_id = null) {
        $query = "INSERT INTO notificaciones (usuario_id, titulo, mensaje, tipo, icono, leida, fecha) 
                  VALUES (:usuario_id, :titulo, :mensaje, :tipo, :icono, 0, NOW())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':mensaje', $mensaje);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':icono', $icono);
        
        return $stmt->execute();
    }
}