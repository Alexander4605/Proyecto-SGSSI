<?php
// Este archivo es incluido por index.php. La sesión y $conn ya están disponibles.
// La comprobación de que el usuario ha iniciado sesión ya se hizo en index.php.

$user_id = $_SESSION['user_id'];
$message = '';
$user_data = null;

// Procesar el formulario si se envía por POST (sin validación PHP)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Crear la consulta SQL para actualizar los datos
    $sql_update = "UPDATE usuarios SET 
                    nombre = '$nombre', 
                    apellidos = '$apellidos', 
                    dni = '$dni', 
                    telefono = '$telefono', 
                    fecha_nacimiento = '$fecha_nacimiento', 
                    email = '$email' 
                   WHERE id = $user_id";

    if (mysqli_query($conn, $sql_update)) {
        $message = "¡Tus datos se han actualizado correctamente!";
        $_SESSION['user_nombre'] = $nombre;
    } else {
        $message = "Error al actualizar los datos: " . mysqli_error($conn);
    }
}

// Cargar los datos actuales del usuario para mostrarlos en el formulario
$sql_select = "SELECT nombre, apellidos, dni, telefono, fecha_nacimiento, email FROM usuarios WHERE id = $user_id";
$result = mysqli_query($conn, $sql_select);
if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    die("Error: No se pudieron cargar los datos del usuario.");
}

$title = "Modificar Mis Datos - MovieReviews";
include 'partials/header.php';
?>

<main class="container">
    <div class="form-container">
        <h2>Modificar Mis Datos</h2>
        <?php if (!empty($message)): ?>
            <p class="success-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="/modify_user" method="post" id="user_modify_form">

        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($user_data['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($user_data['apellidos']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($user_data['dni']); ?>" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($user_data['telefono']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($user_data['fecha_nacimiento']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
            </div>
            
            <button type="submit" class="button" id="user_modify_submit">Guardar Cambios</button>
        </form>
    </div>
</main>

<!-- AÑADIMOS EL SCRIPT DE VALIDACIÓN AL FINAL -->
<script src="/assets/js/validation.js" defer></script>

</body>
</html>