<?php
// Este archivo es incluido por index.php, la sesión y la conexión $conn ya están disponibles.
// La comprobación de que el usuario ha iniciado sesión ya se ha hecho en index.php.

// 1. Validar que tenemos un ID de item en la URL
if (!isset($_GET['item']) || !is_numeric($_GET['item'])) {
    header('Location: /');
    exit();
}

$item_id = (int)$_GET['item'];
$user_id = $_SESSION['user_id'];
$review_data = null;

// 2. Obtener los datos de la review y comprobar la autoría
$sql_select = "SELECT titulo_pelicula, user_id FROM reviews WHERE id = $item_id";
$result = mysqli_query($conn, $sql_select);

if ($result && mysqli_num_rows($result) > 0) {
    $review_data = mysqli_fetch_assoc($result);
    // ¡Comprobación de seguridad! El usuario actual debe ser el autor de la review.
    if ($review_data['user_id'] != $user_id) {
        die("Acceso denegado. No puedes eliminar esta review.");
    }
} else {
    // Si la review no existe, redirigir.
    header('Location: /');
    exit();
}


// 3. Si el usuario confirma la eliminación (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear y ejecutar la consulta de eliminación
    $sql_delete = "DELETE FROM reviews WHERE id = $item_id AND user_id = $user_id"; // Doble seguridad

    if (mysqli_query($conn, $sql_delete)) {
        // Si se borra correctamente, redirigir al catálogo
        header('Location: /');
        exit();
    } else {
        die("Error al eliminar la review: " . mysqli_error($conn));
    }
}

// 4. Incluir la cabecera e imprimir el mensaje de confirmación
$title = "Confirmar Eliminación - MovieReviews";
include 'partials/header.php';
?>

<main class="container">
    <div class="form-container confirmation-box">
        <h2>Confirmar Eliminación</h2>
        <p>¿Estás seguro de que quieres eliminar permanentemente la review de la película "<strong><?php echo htmlspecialchars($review_data['titulo_pelicula']); ?></strong>"?</p>
        <p>Esta acción no se puede deshacer.</p>

        <form action="/delete_item?item=<?php echo $item_id; ?>" method="post" id="item_delete_form">
            <div class="confirmation-actions">
                <button type="submit" class="button delete-confirm" id="item_delete_submit">Sí, eliminar</button>
                <a href="/" class="button cancel">No, cancelar</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>