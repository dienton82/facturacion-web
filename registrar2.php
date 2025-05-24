<?php 
session_start();
require 'simplesanitize.php';
require 'conectardb.php';

$post = new SimpleSanitize('post', 'strict', 64);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $post->get('nombre');
    $email = $post->get('email');
    $password = $post->get('password');
    $password2 = $post->get('password2');

    // Verificar que las contraseñas coincidan y no estén vacías
    if ($password === $password2 && !empty($password)) {
        // Validar la longitud de la contraseña (mínimo 8 caracteres)
        if (strlen($password) < 8) {
            $_SESSION['mensaje'] = "La contraseña debe tener al menos 8 caracteres.";
            header("Location: index.php");
            exit();
        }

        // Validar que la contraseña contenga al menos una letra mayúscula, una letra minúscula y un número
        if (!preg_match('/[A-Z]/', $password)) {
            $_SESSION['mensaje'] = "La contraseña debe contener al menos una letra mayúscula.";
            header("Location: index.php");
            exit();
        }

        if (!preg_match('/[a-z]/', $password)) {
            $_SESSION['mensaje'] = "La contraseña debe contener al menos una letra minúscula.";
            header("Location: index.php");
            exit();
        }

        if (!preg_match('/[0-9]/', $password)) {
            $_SESSION['mensaje'] = "La contraseña debe contener al menos un número.";
            header("Location: index.php");
            exit();
        }

        // Sanitizar los datos de entrada
        $nombre = htmlspecialchars(trim($nombre));
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = trim($password);

        // Validar el email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Hashear la contraseña antes de almacenarla
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Consultar si el correo ya está registrado
            $stmt = $conn->prepare("SELECT email FROM alumnos WHERE email = ?");
            if ($stmt === false) {
                $_SESSION['mensaje'] = "Error en la preparación de la consulta: " . $conn->error;
                header("Location: index.php");
                exit();
            }
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                // Si el email ya está registrado
                $_SESSION['mensaje'] = "El correo electrónico ya está registrado.";
                header("Location: index.php");
                exit();
            } else {
                // Insertar el nuevo usuario en la base de datos
                $stmt = $conn->prepare("INSERT INTO alumnos (nombre, email, password) VALUES (?, ?, ?)");
                if ($stmt === false) {
                    $_SESSION['mensaje'] = "Error en la preparación de la consulta: " . $conn->error;
                    header("Location: index.php");
                    exit();
                }
                $stmt->bind_param('sss', $nombre, $email, $hashedPassword);

                if ($stmt->execute()) {
                    // Redirigir a la página de login con un mensaje de éxito
                    header("Location: ingresar.php?message=registration_success");
                    exit();
                } else {
                    $_SESSION['mensaje'] = "Error en la ejecución de la consulta: " . $stmt->error;
                    header("Location: index.php");
                    exit();
                }
            }
        } else {
            $_SESSION['mensaje'] = "Correo electrónico no válido.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "Las contraseñas no coinciden o están vacías.";
        header("Location: index.php");
        exit();
    }
}
?>
