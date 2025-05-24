<?php
$servername = "localhost";
$username = "root";
$password = "";  // Asegúrate de que la contraseña sea correcta
$dbname = "cursos";

// Crear la conexión con el puerto por defecto
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>