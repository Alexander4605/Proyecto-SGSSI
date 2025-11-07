<?php
// Este archivo es incluido por index.php, por lo que ya tiene acceso a:
// $conn (conexión a BBDD)
// la sesión ($_SESSION)
// la función verificar_csrf()

// 1. Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si alguien intenta acceder por GET, lo echamos
    header('Location: /items');
    exit();
}

// 2. Verificar el Token CSRF
// (La función ya está definida en index.php)
verificar_csrf();

// 3. Verificar que tengamos un ID
if (!isset($_POST['item_id'])) {
    echo "Error: No se ha proporcionado un ID de item.";
    header('Location: /items');
    exit();
}

// 4. Preparar los datos (¡IMPORTANTE!)
$item_id = $_POST['item_id'];
$user_id = $_SESSION['user_id']; // Ya sabemos que el user está logueado (index.php lo comprueba)

// 5. Preparar la consulta SQL (Usar sentencias preparadas)
// Esto previene Inyección SQL en el borrado.
$sql = "DELETE FROM reviews WHERE id = ? AND user_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Asociamos las variables a la consulta
    // "ii" significa que ambas variables son Enteros (Integers)
    mysqli_stmt_bind_param($stmt, "ii", $item_id, $user_id);
    
    // Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        // Borrado con éxito
        // echo "Review eliminada correctamente.";
    } else {
        // Error al borrar
        echo "Error al eliminar la review: " . mysqli_stmt_error($stmt);
    }
    
    // Cerramos la sentencia
    mysqli_stmt_close($stmt);

} else {
    echo "Error al preparar la consulta: " . mysqli_error($conn);
}

// 6. Redirigir de vuelta al catálogo
header('Location: /items');
exit();
?>