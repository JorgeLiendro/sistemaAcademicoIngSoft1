<?php
require_once 'config/Database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $sql = file_get_contents('update_academico.sql');
    if ($sql === false) {
        die("Error reading update_academico.sql");
    }
    
    // Execute multiple queries
    $conn->exec($sql);
    echo "Base de datos actualizada con éxito.";
    
} catch (PDOException $e) {
    echo "Error ejecutando SQL: " . $e->getMessage();
}
