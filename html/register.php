<?php
session_start();
$servername = "db";
$username = "sgssi_user";
$password = "sgssi_password";
$database = "videoclub_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y sanitizar datos
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Validar DNI (comprobar letra)
    function validarDNI($dni) {
        if (strlen($dni) != 10) return false;
        
        $numero = substr($dni, 0, 8);
        $letra = strtoupper(substr($dni, 9, 1));
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letra_correcta = $letras[$numero % 23];
        
        return $letra_correcta == $letra;
    }
    
    // Validaciones del servidor
    $errores = [];
    
    if (!validarDNI($dni)) {
        $errores[] = "DNI no válido";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email no válido";
    }
    
    if (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = "Teléfono debe tener 9 dígitos";
    }
    
    if (empty($errores)) {
        // Insertar usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nombre, $apellidos, $dni, $telefono, $fecha_nacimiento, $email, $username, $password);
        
        if ($stmt->execute()) {
            echo "<script>alert('Registro exitoso'); window.location.href = 'login.html';</script>";
        } else {
            echo "<script>alert('Error en el registro: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Errores: " . implode(', ', $errores) . "');</script>";
    }
}

$conn->close();
?>
