<?php
// Inicia la sesión para poder guardar si un usuario está logueado o no
session_start();

// Conexión a la BBDD (la necesitas en casi todas las páginas)
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";
$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Obtenemos la URL que ha pedido el usuario, sin los parámetros (ej. /login)
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// Decidimos qué página mostrar
switch ($request_uri) {
    case '/':
    case '/items':
        // Página de inicio / listado de reviews
        include 'vistas/items_list.php';
        break;

    case '/register':
        // Muestra el formulario de registro
        include 'vistas/register.php';
        break;

    case '/login':
        // Muestra el formulario de login
        include 'vistas/login.php';
        break;
    
    case '/logout':
        // Cierra la sesión del usuario
        session_destroy();
        header('Location: /'); // Redirige a la página principal
        exit();
        break;

    case '/add_item':
        // Muestra el formulario para añadir una review
        // Comprobación de seguridad: solo usuarios logueados pueden añadir
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); // Si no ha iniciado sesión, a la página de login
            exit();
        }
        include 'vistas/add_item.php';
        break;
    
    case '/modify_user':
        // Lógica para modificar los datos del usuario
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include 'vistas/modify_user.php';
        break;
    
    case '/modify_item':
        // Lógica para modificar una review
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include 'vistas/modify_item.php';
        break;

    case '/delete_item':
        // Lógica para eliminar una review
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        include 'vistas/delete_item.php';
        break;

    default:
        // Página no encontrada
        http_response_code(404);
        include 'vistas/404.php';
        break;
}
?>