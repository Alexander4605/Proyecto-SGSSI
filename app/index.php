<?php

// Muestra un título en la página para confirmar que el PHP se está ejecutando.
echo '<h1>Yeah, it works!<h1>';

// --- Configuración de la Conexión a la Base de Datos ---

// El 'hostname' es 'db'. Este es el nombre del servicio de MariaDB
// definido en docker-compose.yml.
$hostname = "db";

// Credenciales de acceso (usuario y contraseña)
$username = "admin";
$password = "test";

// El nombre de la base de datos a la que nos queremos conectar
$db = "database";

// --- Conexión a la Base de Datos ---

// Intenta establecer la conexión usando las variables definidas arriba
$conn = mysqli_connect($hostname, $username, $password, $db);

// Comprueba si la conexión falló
if ($conn->connect_error) {
  // Si falla, detiene la ejecución del script y muestra el error
  die("Database connection failed: " . $conn->connect_error);
}

// --- Consulta a la Base de Datos ---

// Prepara y ejecuta una consulta SQL para seleccionar todos los datos de la tabla 'usuarios'
$query = mysqli_query($conn, "SELECT * FROM usuarios")
  // Si la consulta SQL falla (p.ej., la tabla no existe), detiene el script y muestra el error de MySQL
  or die(mysqli_error($conn));

// --- Muestra de Resultados ---

// Inicia un bucle 'while' que recorre cada fila de resultados obtenida de la consulta
while ($row = mysqli_fetch_array($query)) {

  // Por cada fila, imprime en pantalla el 'id' y el 'nombre'
  // envueltos en etiquetas de fila (<tr>) y celda (<td>) de una tabla HTML.
  echo
  "<tr>
    <td>{$row['id']}</td>
    <td>{$row['nombre']}</td>
   </tr>";
}

// Cierra el bloque de código PHP
?>
