<?php

$host = "localhost";
$user = 'root';
$password = "";
$database = "cursos";

$dbConnect = new mysqli($host, $user, $password, $database);
if ($dbConnect->connect_error) {
    die("Error al conectar a la base de datos MySQL: " . $dbConnect->connect_error);
}
$dbConnect->set_charset("utf8mb4");

// Consultar cursos disponibles
$sql = "SELECT DISTINCT curso AS name, curso_id AS id FROM factura_orden";
$result = $dbConnect->query($sql);

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode(['courses' => $courses]);

$dbConnect->close();
?>
