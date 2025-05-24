<?php 
session_start();
require 'simplesanitize.php';
require 'conectardb.php';

// Crear una instancia de SimpleSanitize para limpiar los datos
$post = new SimpleSanitize('post', 'strict', 64);

// Procesar el formulario si hay datos enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $post->get('email');
    $password = $post->get('password');
    $planeta = $post->get('planeta');

    if ($planeta === 'tierra') {
        if (!empty($password)) {
            // Consultar la base de datos
            $stmt = $conn->prepare("SELECT * FROM alumnos WHERE email = ?");
            if ($stmt === false) {
                die("Error en la preparación de la consulta: " . $conn->error);
            }
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $datos = $result->fetch_assoc();
            $stmt->close();

            // Verificar la contraseña cifrada
            if ($datos && password_verify($password, $datos["password"])) {
                $_SESSION['alumno_id'] = $datos['alumno_id'];
                $_SESSION['nombre'] = $datos['nombre'];

                // Verificar si el usuario ya generó facturas
                $stmt = $conn->prepare("SELECT COUNT(*) as factura_count FROM factura_orden WHERE alumno_id = ?");
                $stmt->bind_param('i', $_SESSION['alumno_id']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();

                // Redirigir según si hay facturas generadas
                if ($row['factura_count'] > 0) {
                    $_SESSION['mensaje'] = 'Ya has generado una factura. Puedes <strong>editarla</strong>, agregar nuevos cursos o <strong>editar tus datos básicos</strong>.';
                    header("Location: listado_factura.php");
                    exit();
                } else {
    $_SESSION['mensaje'] = 'Ingreso exitoso. Puedes <strong>crear tu factura</strong> y agregar cursos.';
    header("Location: creacion_factura.php");
    exit();
}
            } else {
                // Contraseña incorrecta o usuario no encontrado
                echo '<script>alert("Correo electrónico o contraseña incorrectos"); window.location.href="ingresar.php";</script>';
            }
        } else {
            // Contraseña vacía
            echo '<script>alert("La contraseña no puede estar vacía"); window.location.href="ingresar.php";</script>';
        }
		} else {
		// Respuesta incorrecta a la pregunta de seguridad
		echo '<script>alert("Respuesta incorrecta. El nombre del planeta es \'tierra\' en minúsculas."); window.location.href="ingresar.php";</script>';
	}

} else {
    // Redirigir si el método no es POST
    echo '<script>window.location.href="ingresar.php";</script>';
}
?>
