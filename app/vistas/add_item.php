<?php
// Este archivo es incluido por index.php. La sesión y $conn ya están disponibles.
// La comprobación de que el usuario ha iniciado sesión ya se hizo en index.php.

$message = '';

// Si el formulario se envía, procesamos los datos directamente sin validación PHP.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del formulario (la validación ya se hizo con JS).
    $titulo_pelicula = mysqli_real_escape_string($conn, $_POST['titulo_pelicula']);
    $director = mysqli_real_escape_string($conn, $_POST['director']);
    $ano_estreno = (int)$_POST['ano_estreno'];
    $puntuacion = (int)$_POST['puntuacion'];
    $texto_review = mysqli_real_escape_string($conn, $_POST['texto_review']);
    $user_id = $_SESSION['user_id']; // Obtenemos el ID del usuario de la sesión

    // Preparamos y ejecutamos la consulta para insertar la nueva review
    $sql = "INSERT INTO reviews (titulo_pelicula, director, ano_estreno, puntuacion, texto_review, user_id) 
            VALUES ('$titulo_pelicula', '$director', $ano_estreno, $puntuacion, '$texto_review', $user_id)";

    if (mysqli_query($conn, $sql)) {
        header("Location: /"); // Si todo va bien, redirigimos al catálogo
        exit();
    } else {
        $message = "Error al guardar la review: " . mysqli_error($conn);
    }
}

// Incluimos la cabecera e imprimimos el formulario
$title = "Añadir Nueva Review - MovieReviews";
include 'partials/header.php';
?>

<main class="container">
    <div class="form-container">
        <h2>Añadir Nueva Review</h2>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="/add_item" method="post" id="item_add_form">

        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
            <div class="form-group">
                <label for="titulo_pelicula">Título de la Película</label>
                <input type="text" id="titulo_pelicula" name="titulo_pelicula" required>
            </div>
            <div class="form-group">
                <label for="director">Director</label>
                <input type="text" id="director" name="director" required>
            </div>
            <div class="form-group">
                <label for="ano_estreno">Año de Estreno</label>
                <input type="number" id="ano_estreno" name="ano_estreno" required>
            </div>
            <div class="form-group">
                <label for="puntuacion">Puntuación (sobre 10)</label>
                <input type="number" id="puntuacion" name="puntuacion" min="1" max="10" required>
            </div>
            <div class="form-group">
                <label for="texto_review">Tu Review</label>
                <textarea id="texto_review" name="texto_review" rows="6" required></textarea>
            </div>
            
            <button type="submit" class="button" id="item_add_submit">Guardar Review</button>
        </form>
    </div>
</main>

<!-- AÑADIMOS EL SCRIPT DE VALIDACIÓN AL FINAL -->
<script src="/assets/js/validation.js" defer></script>

</body>
</html>

