<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Conexión a la base de datos
    include('conexion.php');

    // Verificar si el token es válido y no ha expirado
    $query = "SELECT * FROM alumnos WHERE reset_token = ? AND reset_token_expira > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Actualizar la contraseña en la base de datos
            $query = "UPDATE alumnos SET password = ?, reset_token = NULL, reset_token_expira = NULL WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            echo "Tu contraseña ha sido actualizada correctamente.";
        }
    } else {
        echo "El enlace para restablecer la contraseña ha expirado o es inválido.";
    }
} else {
    echo "Token no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h1>Restablecer Contraseña</h1>
    <form action="" method="post">
        <div class="form-group">
            <label for="password">Nueva Contraseña:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Restablecer Contraseña" class="form-control">
        </div>
    </form>
</body>
</html>
