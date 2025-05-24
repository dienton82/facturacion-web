<?php
// Incluir la conexión a la base de datos
include('conectardb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el email ingresado por el usuario
    $email = $_POST['email'];

    // Verificar si el correo electrónico existe en la base de datos
    $query = "SELECT * FROM alumnos WHERE email = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        // Enlazar el parámetro
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Comprobar si el correo electrónico está registrado
        if ($result->num_rows > 0) {
            // Si el correo está registrado, generar un token único
            $token = bin2hex(random_bytes(50)); // Genera un token aleatorio de 50 caracteres
            $expira = date("Y-m-d H:i:s", strtotime('+1 hour')); // Establece la expiración a 1 hora

            // Actualizar la base de datos con el token de restablecimiento
            $query = "UPDATE alumnos SET reset_token = ?, reset_token_expira = ? WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $token, $expira, $email);
            $stmt->execute();

            // Enviar un correo electrónico con el enlace de restablecimiento
            $reset_link = "https://tu-sitio.com/restablecer_contraseña.php?token=" . $token;
            $asunto = "Restablecer tu contraseña";
            $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $reset_link;
            $headers = "From: no-reply@tu-sitio.com";

            if (mail($email, $asunto, $mensaje, $headers)) {
                echo "Hemos enviado un enlace a tu correo electrónico para restablecer la contraseña.";
            } else {
                echo "Error al enviar el correo. Inténtalo nuevamente.";
            }
        } else {
            // Si el correo no está registrado
            echo "El correo electrónico no está registrado.";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }
}

$conn->close();
?>
