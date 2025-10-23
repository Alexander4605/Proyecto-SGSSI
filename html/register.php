<?php
// Inicia o reanuda la sesión
session_start();

// --- Configuración de la BBDD ---
$servername = "db"; // Host (servicio de Docker)
$username = "sgssi_user"; // Usuario
$password = "sgssi_password"; // Contraseña
$database = "videoclub_db"; // Nombre de la BBDD

// --- Conexión a la BBDD ---
$conn = new mysqli($servername, $username, $password, $database);

// Comprueba si la conexión falló
if ($conn->connect_error) {
    die("Connection failed: " " . $conn->connect_error);
}

// Comprueba si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Recoge y limpia los datos del formulario ---
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $telefono = trim($_POST['telefono']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    // Encripta la contraseña para guardarla de forma segura
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // --- Función de validación de DNI ---
    function validarDNI($dni) {
        if (strlen($dni) != 10) return false; // Comprueba longitud (8 números + guion + letra)
        
        $numero = substr($dni, 0, 8); // Extrae los números
        $letra = strtoupper(substr($dni, 9, 1)); // Extrae la letra (en mayúscula)
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE"; // Letras válidas
        $letra_correcta = $letras[$numero % 23]; // Calcula la letra correcta
        
        return $letra_correcta == $letra; // Compara si la letra es correcta
    }
    
    // --- Validaciones del Lado del Servidor ---
    $errores = []; // Array para guardar mensajes de error
    
    // Valida el DNI
    if (!validarDNI($dni)) {
        $errores[] = "DNI no válido";
    }
    
    // Valida el formato del email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email no válido";
    }
    
    // Valida que el teléfono tenga 9 dígitos
    if (!preg_match('/^\d{9}$/', $telefono)) {
        $errores[] = "Teléfono debe tener 9 dígitos";
    }
    
    // --- Inserción en la BBDD (si no hay errores) ---
    if (empty($errores)) {
        // Prepara la consulta SQL (evita inyección SQL)
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        // Asigna las variables a los '?' (todos son strings "s")
        $stmt->bind_param("ssssssss", $nombre, $apellidos, $dni, $telefono, $fecha_nacimiento, $email, $username, $password);
        
        // Ejecuta la consulta
        if ($stmt->execute()) {
            // Éxito: Registro completado
            echo "<script>alert('Registro exitoso'); window.location.href = 'login.html';</script>";
        } else {
            // Error: Fallo al insertar (ej. usuario o email duplicado)
            echo "<script>alert('Error en el registro: " . $stmt->error . "');</script>";
        }
        $stmt->close(); // Cierra la consulta preparada
    } else {
        // Error: Muestra los errores de validación
        echo "<script>alert('Errores: " . implode(', ', $errores) . "');</script>";
    }
}

// Cierra la conexión a la BBDD
$conn->close();
?>
