<?php
// Inicia o reanuda una sesión para guardar datos del usuario
session_start();

// --- Configuración de la Conexión ---
$servername = "db"; // Nombre del servicio de la BBDD (de Docker Compose)
$username = "sgssi_user"; // Usuario de la BBDD
$password = "sgssi_password"; // Contraseña del usuario
$database = "videoclub_db"; // Nombre de la BBDD

// --- Conexión a la BBDD ---
$conn = new mysqli($servername, $username, $password, $database);

// Verifica si la conexión falló
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Lógica de Login ---

// Comprueba si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recoge y limpia los datos del formulario
    $username = trim($_POST['username']);
    $password_input = $_POST['password'];
    
    // Prepara una consulta SQL segura para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id, username, password FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" significa que $username es un string
    
    // Ejecuta la consulta
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica si se encontró exactamente 1 usuario
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc(); // Obtiene los datos del usuario
        
        // Compara la contraseña enviada con el hash guardado en la BBDD
        if (password_verify($password_input, $user['password'])) {
            
            // --- Éxito: Contraseña correcta ---
            $_SESSION['user_id'] = $user['id']; // Guarda el ID en la sesión
            $_SESSION['username'] = $user['username']; // Guarda el nombre en la sesión
            
            header("Location: items.php"); // Redirige al catálogo
            exit(); // Termina el script
            
        } else {
            // Error: Contraseña incorrecta
            echo "<script>alert('Contraseña incorrecta'); window.location.href = 'login.html';</script>";
        }
    } else {
        // Error: Usuario no encontrado
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.html';</script>";
    }
    
    // Cierra la consulta preparada
    $stmt->close();
}

// Cierra la conexión a la BBDD
$conn->close();
?>
