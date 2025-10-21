<?php
// Procesamiento del formulario de registro SIN validación en el servidor
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoger datos directamente del formulario
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Hashear la contraseña por seguridad
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario directamente, confiando en la validación de JS
    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, password) 
            VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fecha_nacimiento', '$email', '$password_hashed')";

    if (mysqli_query($conn, $sql)) {
        // Si el registro es exitoso, redirigir a la página de login
        header("Location: /login");
        exit();
    } else {
        // Si hay un error de la BBDD (ej: email duplicado), mostrar un error genérico
        // Esto podría pasar si alguien desactiva el JS e intenta registrar un email que ya existe
        $error_message = "Hubo un error al registrar el usuario. Es posible que el DNI o el email ya estén en uso.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">MovieReviews</a>
            <nav class="main-nav">
                <a href="/login" class="nav-button">Iniciar Sesión</a>
                <a href="/register" class="nav-button">Registrarse</a>
            </nav>
        </div>
    </header>
    
    <div class="form-container">
        <h1>Crear una Cuenta</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form id="register_form" method="post" action="/register" novalidate>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ej: Jon" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="Ej: Doe" required>
            </div>
            <div class="form-group">
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" placeholder="Ej: 12345678A" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Ej: 600123456" required>
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Ej: ejemplo@dominio.com" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" id="register_submit" class="button-primary">Registrarse</button>
        </form>
    </div>

    <!-- AQUÍ ESTÁ LA MAGIA: Enlazamos el archivo de validación -->
    <script src="/assets/js/validation.js"></script>

</body>
</html>