<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cursos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Actualizar el campo password a una cadena vacía
$sql = "UPDATE alumnos SET password = ''";
if ($conn->query($sql) === TRUE) {
    echo "Contraseñas actualizadas a vacío con éxito.";
} else {
    echo "Error al actualizar contraseñas: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>