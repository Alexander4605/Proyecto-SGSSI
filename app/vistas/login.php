<?php
// Procesamiento del formulario de login
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos de forma segura
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_form = $_POST['password'];

    // Buscar al usuario por email en la BBDD
    $sql = "SELECT id, nombre, password FROM usuarios WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Verificar si se encontró al usuario
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Verificar si la contraseña coincide con la guardada en la BBDD
        if (password_verify($password_form, $user['password'])) {
            // ¡Contraseña correcta! Iniciar sesión.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            
            // Redirigir a la página principal
            header("Location: /");
            exit();
        } else {
            // Contraseña incorrecta
            $error_message = "El email o la contraseña son incorrectos.";
        }
    } else {
        // Usuario no encontrado
        $error_message = "El email o la contraseña son incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
        <h1>Iniciar Sesión</h1>

        <?php if (!empty($error_message)): ?>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form id="login_form" method="post" action="/login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" id="login_submit" class="button-primary">Entrar</button>
        </form>
    </div>

</body>
</html>
