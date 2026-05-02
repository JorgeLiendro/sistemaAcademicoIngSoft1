<?php
session_start();
require_once 'config/database.php';
require_once 'models/Database.php';
require_once 'models/AuthModel.php';
require_once 'controllers/AuthController.php';

// Autoload para controladores y modelos
spl_autoload_register(function ($class_name) {
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    
    $paths = [
        'controllers/' . $class_name . '.php',
        'models/' . $class_name . '.php',
        'config/' . $class_name . '.php',
        'libs/' . $class_path,
        'vendor/' . $class_path
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
    // No lanzamos excepción aquí para permitir que class_exists maneje el error elegantemente
});

// Enrutamiento básico
$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'Auth';


if ($controller === 'Administrador') {
    $controller = 'Admin';
}

$controller_class = $controller . 'Controller';

if (class_exists($controller_class)) {
    $controller_instance = new $controller_class();
    
    if (method_exists($controller_instance, $action)) {
        $controller_instance->$action();
    } else {
        if (file_exists('views/errors/404.php')) {
            require_once 'views/errors/404.php';
        } else {
            echo "Error 404: Acción no encontrada.";
        }
    }
} else {
    if (file_exists('views/errors/404.php')) {
        require_once 'views/errors/404.php';
    } else {
        echo "Error 404: El controlador '$controller_class' no existe.";
    }
}
?>