<?php
// Inicia la sesión
session_start();

// Definimos la ruta raíz del proyecto (ej. /var/www/html)
define('ROOT_PATH', __DIR__);

// Conexión a la BBDD
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";
$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Obtenemos la URL de forma robusta, entendiendo peticiones de proxy
$url_components = parse_url($_SERVER['REQUEST_URI']);
$request_uri = $url_components['path'];

// Decidimos qué página mostrar
switch ($request_uri) {
    case '/':
    case '/items':
        // Página de inicio / listado de reviews
        include ROOT_PATH . '/vistas/items_list.php';
        break;

    case '/register':
        // Muestra el formulario de registro
        include ROOT_PATH . '/vistas/register.php';
        break;

    case '/login':
        // Muestra el formulario de login
        include ROOT_PATH . '/vistas/login.php';
        break;
        
    case '/logout':
        // Cierra la sesión del usuario
        session_destroy();
        header('Location: /'); // Redirige a la página principal
        exit();
        break;

    case '/add_item':
        // Muestra el formulario para añadir una review
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include ROOT_PATH . '/vistas/add_item.php';
        break;
        
    case '/modify_user':
        // Lógica para modificar los datos del usuario
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include ROOT_PATH . '/vistas/modify_user.php';
        break;
        
    case '/modify_item':
        // Lógica para modificar una review
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include ROOT_PATH . '/vistas/modify_item.php';
        break;

    case '/delete_item':
        // Lógica para eliminar una review
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include ROOT_PATH . '/vistas/delete_item.php';
        break;

    default:
        // Página no encontrada
        http_response_code(404);
        include ROOT_PATH . '/vistas/404.php';
        break;
}
?>