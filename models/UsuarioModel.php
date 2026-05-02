<?php
class UsuarioModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrarUsuarios($busqueda = '', $rol = '', $itemsPorPagina = 10, $pagina = 1) {
        $offset = ($pagina - 1) * $itemsPorPagina;
        
        $sql = "SELECT * FROM usuarios WHERE 1=1";
        $params = array();
        
        if (!empty($busqueda)) {
            $sql .= " AND (nombre_completo LIKE ? OR carnet LIKE ? OR correo_electronico LIKE ?)";
            $busquedaParam = "%$busqueda%";
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
        }
        
        if (!empty($rol)) {
            $sql .= " AND rol = ?";
            $params[] = $rol;
        }
        
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $itemsPorPagina;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarUsuariosFiltrados($busqueda = '', $rol = '') {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE 1=1";
        $params = array();
        
        if (!empty($busqueda)) {
            $sql .= " AND (nombre_completo LIKE ? OR carnet LIKE ? OR correo_electronico LIKE ?)";
            $busquedaParam = "%$busqueda%";
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
            $params[] = $busquedaParam;
        }
        
        if (!empty($rol)) {
            $sql .= " AND rol = ?";
            $params[] = $rol;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>