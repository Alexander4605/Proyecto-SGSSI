<?php

// 1. Validar que tenemos un ID de item en la URL
if (!isset($_GET['item']) || !is_numeric($_GET['item'])) {
    header('Location: /'); // Si no hay ID, redirigir al inicio
    exit();
}

$item_id = (int)$_GET['item'];
$user_id = $_SESSION['user_id'];
$message = '';
$review_data = null;

// 2. Obtener los datos de la review y comprobar la autoría (Esto es seguridad, no validación de datos, así que se queda)
$sql_select = "SELECT * FROM reviews WHERE id = $item_id";
$result = mysqli_query($conn, $sql_select);

if ($result && mysqli_num_rows($result) > 0) {
    $review_data = mysqli_fetch_assoc($result);
    if ($review_data['user_id'] != $user_id) {
        die("Acceso denegado. No eres el autor de esta review.");
    }
} else {
    header('Location: /');
    exit();
}


// 3. Procesar el formulario si se envían cambios (sin validación PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo_pelicula = mysqli_real_escape_string($conn, $_POST['titulo_pelicula']);
    $director = mysqli_real_escape_string($conn, $_POST['director']);
    $ano_estreno = (int)$_POST['ano_estreno'];
    $puntuacion = (int)$_POST['puntuacion'];
    $texto_review = mysqli_real_escape_string($conn, $_POST['texto_review']);

    // Crear y ejecutar la consulta de actualización
    $sql_update = "UPDATE reviews SET
                    titulo_pelicula = '$titulo_pelicula',
                    director = '$director',
                    ano_estreno = $ano_estreno,
                    puntuacion = $puntuacion,
                    texto_review = '$texto_review'
                   WHERE id = $item_id AND user_id = $user_id";

    if (mysqli_query($conn, $sql_update)) {
        header('Location: /');
        exit();
    } else {
        $message = "Error al actualizar la review: " . mysqli_error($conn);
    }
}


$title = "Modificar Review - MovieReviews";
include 'partials/header.php';
?>

<main class="container">
    <div class="form-container">
        <h2>Modificar Review</h2>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="/modify_item?item=<?php echo $item_id; ?>" method="post" id="item_modify_form">

        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
            <div class="form-group">
                <label for="titulo_pelicula">Título de la Película</label>
                <input type="text" id="titulo_pelicula" name="titulo_pelicula" value="<?php echo htmlspecialchars($review_data['titulo_pelicula']); ?>" required>
            </div>
            <div class="form-group">
                <label for="director">Director</label>
                <input type="text" id="director" name="director" value="<?php echo htmlspecialchars($review_data['director']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ano_estreno">Año de Estreno</label>
                <input type="number" id="ano_estreno" name="ano_estreno" value="<?php echo htmlspecialchars($review_data['ano_estreno']); ?>" required>
            </div>
            <div class="form-group">
                <label for="puntuacion">Puntuación (sobre 10)</label>
                <input type="number" id="puntuacion" name="puntuacion" min="1" max="10" value="<?php echo htmlspecialchars($review_data['puntuacion']); ?>" required>
            </div>
            <div class="form-group">
                <label for="texto_review">Tu Review</label>
                <textarea id="texto_review" name="texto_review" rows="6" required><?php echo htmlspecialchars($review_data['texto_review']); ?></textarea>
            </div>
            
            <button type="submit" class="button" id="item_modify_submit">Guardar Cambios</button>
        </form>
    </div>
</main>


<script src="/assets/js/validation.js" defer></script>

</body>
</html>