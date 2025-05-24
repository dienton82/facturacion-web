<?php 
// Verificar si hay un mensaje en la URL (por ejemplo, si el usuario no está registrado)
if (isset($_GET['message']) && $_GET['message'] === 'not_registered') {
    echo "<p style='color: red;'>Aún no estás registrado. Por favor, regístrate para acceder a esta sección.</p>";
}
?>

<?php
// Iniciar sesión para manejar el mensaje de la sesión
session_start();
if (isset($_SESSION['mensaje'])) {
    echo '<div id="mensaje-flotante" class="mensaje-flotante">' . $_SESSION['mensaje'] . '</div>';
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturador Full-Stack: Gestión de Cursos y Facturas</title>
    <meta name="description" content="Diplomados online en estética">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/4estilos.css"> <!-- Cambié el enlace al CSS -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="js/jquery.js"></script>
	<link rel="icon" type="image/x-icon" href="img/favicon.ico">

    <style>
        /* Barra de mensaje flotante */
        .mensaje-flotante {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            background-color: #48c4bf; /* Color de fondo */
            color: #ffffff; /* Color del texto */
            text-align: center;
            font-weight: bold;
            z-index: 9999; /* Para que siempre esté encima de todo */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra para darle profundidad */
            transition: top 0.5s ease-in-out; /* Animación de deslizamiento */
        }

        /* Clase para ocultar el mensaje flotante */
        .mensaje-flotante.ocultar {
            top: -100px; /* Fuera de la pantalla */
        }

        /* Estilos para los mensajes de error */
        .error {
             color: #D3B17B;
            font-size: 14px;
            text-align: left;
            padding-left: 2rem;
            padding-bottom: 1rem;
        }

        .exito {
            color: #48c4bf;
            font-size: 14px;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Mostrar/ocultar el formulario de login al hacer clic en el enlace
            $("#abierto").click(function() {
                $("#login-form").fadeToggle(300); // Mostrar/ocultar el formulario de registro
            });

            // Ocultar el mensaje flotante después de 5 segundos
            var mensajeFlotante = document.getElementById('mensaje-flotante');
            if (mensajeFlotante) {
                setTimeout(function() {
                    mensajeFlotante.classList.add('ocultar');
                }, 5000); // 5 segundos
            }
        });

        // Función para validar la contraseña en tiempo real
        function validarPassword() {
            const password = document.getElementById("password").value;
            const mensaje = document.getElementById("mensaje-password");

            // Expresiones regulares para las validaciones
            const mayuscula = /[A-Z]/;
            const minuscula = /[a-z]/;
            const numero = /\d/;

            // Inicializar el mensaje
            let mensajeTexto = "La contraseña debe cumplir con los siguientes requisitos:<br>";
            let esValida = true;

            // Validar longitud mínima
            if (password.length < 8) {
                mensajeTexto += "- Tener al menos 8 caracteres.<br>";
                esValida = false;
            }

            // Validar que tenga al menos una mayúscula
            if (!mayuscula.test(password)) {
                mensajeTexto += "- Contener al menos una letra mayúscula.<br>";
                esValida = false;
            }

            // Validar que tenga al menos una minúscula
            if (!minuscula.test(password)) {
                mensajeTexto += "- Contener al menos una letra minúscula.<br>";
                esValida = false;
            }

            // Validar que tenga al menos un número
            if (!numero.test(password)) {
                mensajeTexto += "- Contener al menos un número.<br>";
                esValida = false;
            }

            // Mostrar el mensaje si la contraseña no es válida
            if (!esValida) {
                mensaje.innerHTML = mensajeTexto;
                mensaje.className = "error"; // Cambiar a rojo si no es válida
            } else {
                mensaje.innerHTML = "La contraseña cumple con todos los requisitos.";
                mensaje.className = "exito"; // Cambiar a verde si es válida
            }
        }
    </script>
</head>
<body>
    <nav>
        <ul class="topnav">
            <li class="logo-container"> <!-- Contenedor del logo y el texto -->
                <img src="img/logo1.png" alt="logo" class="logo">
                <p class="diplomados">Diplomados en Estética</p> <!-- Texto debajo del logo -->
            </li>
            <li style="float:right;">
                <a href="ingresar.php">Ingresar</a>
            </li>
            <li style="float:right;">
                <a href="index.php">Registrarse</a>
            </li>
        </ul>
    </nav>

<div class="contenido">
    <div class="principal">
	<br><br>
        <h1>¡Regístrate para acceder a nuestros cursos!</h1>
        <p style="color: #efecec;">Regístrate en nuestra plataforma para inscribirte en los diplomados y gestionar <br> tu factura y detalles del curso.</p>
      </div>
</div>

<div id="login">
    <p id="abierto" style="cursor: pointer; text-decoration: underline;">Haz clic aquí para mostrar el formulario de registro</p>
</div>

<div class="seccion">
    <div class='division70'>
        <h1 class="caption2">Registrarse</h1>
    </div>
</div>

<div class="seccion" style="color:#737477;">
    <div class="division3" style="padding-top:20px;">
        <div id="login-form" class="login-form" style="display:none;">
            <form action='registrar2.php' method='post'>
                <div class="form-group">
                    <input 
                        type='text' 
                        name='nombre' 
                        placeholder='Nombre Completo' 
                        class='form-control' 
                        required 
                        oninput="this.value = this.value.toUpperCase();">
                </div>
                <div class="form-group">
                    <input type='email' name='email' placeholder='Email' class='form-control' required>
                </div>
                <div class="form-group">
                    <input type='password' id='password' name='password' placeholder='Password' class='form-control' oninput="validarPassword()" required>
                </div>
                <div id="mensaje-password" class="error">
                    El Password debe cumplir con los siguientes requisitos:<br>
                    - Tener al menos 8 caracteres.<br>
                    - Contener al menos una letra mayúscula.<br>
                    - Contener al menos una letra minúscula.<br>
                    - Contener al menos un número.<br>
                </div>
                <div class="form-group">
                    <input type='password' name='password2' placeholder='Repetir Password' class='form-control' required>
                </div>
                <input type='submit' value='Enviar' class='button'>
				 <div style="margin-top: 20px;">
                <a href="ingresar.php" class="button-link">¿Ya estás registrado? Ingresar aquí</a>
            </div>
            </form>
           
        </div>
    </div>
</div>

<div class="seccion" id="contacto" style="padding-top:10px;">
    <h2 class="caption1">Contacto</h2>
</div>

<div class="seccion" style="padding:20px 0;">
    <div class="division2">
        <p style="text-align:center;">
            PBX: (601) 7186664<br><br>
            CEL: (+57) 3115528609<br><br>
            info@spandre.com.co<br><br>
            Carrera 114f #145-45 Apto. 302<br><br>
            Bogotá, Colombia
        </p>
    </div>
</div>

<div class="seccion" style="padding:18px 0px;margin:0px;">
    <div class="division2">
        <a href="index.php" style="color:#FFFFFF">Copyright © 2022 Diplomados En Estética</a>
    </div>
</div>

</body>
</html>