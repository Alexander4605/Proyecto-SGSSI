<?php
// session_start() ya se llama en index.php, así que tenemos acceso a la sesión.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- El título se definirá en cada página individual -->
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>

    <header class="main-header">
        <div class="container">
            <a href="/" class="logo">MovieReviews</a>
            <nav class="main-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="welcome-message">Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></span>
                    <a href="/modify_user" class="nav-button">Mis Datos</a>
                    <a href="/logout" class="nav-button">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="/login" class="nav-button">Iniciar Sesión</a>
                    <a href="/register" class="nav-button">Registrarse</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>