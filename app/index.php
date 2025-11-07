<?php
// --- INICIO SOLUCIÓN COOKIES ---
// 1. Configurar las cookies ANTES de iniciar la sesión

session_start([
    'cookie_lifetime' => 3600, // 1 hora
    'cookie_path' => '/',
    'cookie_domain' => '', // Dominio actual
    'cookie_secure' => false, // Poner a 'true' si usaras HTTPS
    'cookie_httponly' => true, // <-- SOLUCIÓN HttpOnly
]);

// --- INICIO PARCHE SameSite PARA PHP < 7.3 ---
//
// Buscamos la cabecera Set-Cookie que PHP acaba de enviar
$cookie_header = null;
foreach (headers_list() as $header) {
    if (strpos($header, 'Set-Cookie: ' . session_name()) !== false) {
        $cookie_header = $header;
        break;
    }
}

// Si la encontramos, la borramos y la volvemos a añadir con SameSite
if ($cookie_header) {
    header_remove('Set-Cookie'); // Borra la original
    header($cookie_header . '; SameSite=Strict'); // Añade la nueva
}
// --- FIN PARCHE SameSite PARA PHP < 7.3 ---


// --- INICIO SOLUCIÓN CABECERAS DE SEGURIDAD ---
// (Estas se aplican a todas las páginas generadas por PHP)

// 3. Content Security Policy (CSP) - Versión Segura
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; base-uri 'self';");

// 4. Anti-Clickjacking
header("X-Frame-Options: DENY");

// 5. Prevenir MIME-sniffing
header("X-Content-Type-Options: nosniff");


// 6. Política de Referrer (Buena práctica)
header("Referrer-Policy: strict-origin-when-cross-origin");

// --- FIN SOLUCIÓN CABECERAS DE SEGURIDAD ---

/**
 * Función para verificar el token CSRF y detener la ejecución si falla.
 */
function verificar_csrf() {
    // Comprobar que el token exista en POST y en SESIÓN
    if (!isset($_POST['csrf_token']) || 
        !isset($_SESSION['csrf_token']) || 
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        
        // El token no es válido.
        die("Error de validación de seguridad (CSRF). La petición ha sido bloqueada.");
    }
}

// Definimos la ruta raíz del proyecto
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

// Obtenemos la URL de forma robusta
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
        // El .htaccess redirigirá aquí si el archivo no existe
        http_response_code(404);
        include ROOT_PATH . '/vistas/404.php';
        break;
}
?>