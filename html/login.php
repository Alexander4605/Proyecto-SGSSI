<?php
session_start();
$servername = "db";
$username = "sgssi_user";
$password = "sgssi_password";
$database = "videoclub_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password_input = $_POST['password'];
    
    // Buscar usuario en la base de datos
    $stmt = $conn->prepare("SELECT id, username, password FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verificar contraseña
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: items.php");
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href = 'login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario no encontrado'); window.location.href = 'login.html';</script>";
    }
    
    $stmt->close();
}

$conn->close();
?>
