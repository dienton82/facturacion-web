<?php
// Iniciar sesión para manejar el mensaje de la sesión
session_start();

// Mostrar mensaje flotante si existe en la URL o la sesión
if (isset($_GET['message'])) {
    switch ($_GET['message']) {
        case 'registration_success':
            $_SESSION['mensaje'] = '¡Registro exitoso! Ahora puedes iniciar sesión.';
            break;
        case 'email_exists':
            $_SESSION['mensaje'] = 'El correo electrónico ya está registrado. Por favor, inicia sesión.';
            break;
        case 'registration_error':
            $_SESSION['mensaje'] = 'Hubo un error en el registro. Inténtalo de nuevo.';
            break;
        case 'invalid_email':
            $_SESSION['mensaje'] = 'El correo electrónico no es válido. Por favor, corrige y vuelve a intentarlo.';
            break;
        case 'password_mismatch':
            $_SESSION['mensaje'] = 'Las contraseñas no coinciden. Por favor, revisa e intenta nuevamente.';
            break;
        default:
            $_SESSION['mensaje'] = 'Ocurrió un error desconocido. Por favor, intenta nuevamente.';
            break;
    }
}

// Mostrar el mensaje flotante si existe en la sesión
if (isset($_SESSION['mensaje'])) {
    echo '<div id="mensaje-flotante" class="mensaje-flotante">' . $_SESSION['mensaje'] . '</div>';
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Diplomados en Estética</title>
    <meta name="description" content="Diplomados online en estética.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/4estilos.css">
    <script src="js/jquery.js"></script>

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
    </style>

    <script>
        $(document).ready(function () {
            // Mostrar u ocultar el formulario de login
            $("#abierto").click(function () {
                $("#login-form").fadeToggle(300);
            });

            // Ocultar el mensaje flotante después de 5 segundos
            var mensajeFlotante = document.getElementById('mensaje-flotante');
            if (mensajeFlotante) {
                setTimeout(function () {
                    mensajeFlotante.classList.add('ocultar');
                }, 5000); // 5 segundos
            }
        });
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
        <h1>¡Inicia sesión para seguir avanzando!</h1>
        <p style="color: #efecec;">Introduce tus datos para ingresar a tu perfil, gestionar tus <br> cursos y facturas.</p>
    </div>
</div>

<div id="login">
    <p id="abierto" style="cursor: pointer; text-decoration: underline;">Haz clic aquí para mostrar el formulario de ingreso</p>
</div>

<div class="seccion">
    <div class='division70'>
        <h1 class="caption2">Ingresar</h1>
    </div>
</div>

<div class="seccion" style="color:#737477;">
    <div class="division3" style="padding-top:20px;">
        <div id="login-form" class="login-form" style="display:none;">
            <form action='ingresar2.php' method='post'>
                <div class="form-group">
                    <input 
                        type='email' 
                        name='email' 
                        placeholder='Email' 
                        class='form-control' 
                        required>
                </div>
                <div class="form-group">
                    <input 
                        type='password' 
                        name='password' 
                        placeholder='Password' 
                        class='form-control' 
                        required>
                </div>
                <div class="form-group">
                    <input 
                        type='text' 
                        name='planeta' 
                        placeholder='Nombre del planeta donde vivimos' 
                        class='form-control' 
                        required 
                        oninput="this.value = this.value.toLowerCase()">
                </div>
                <input type='submit' value='Enviar' class='button'>
				 <div style="margin-top: 20px;">
				 <a href="olvidar_contrasena.php" class="contacto button-link">Olvidé mi contraseña</a>
				 <div style="margin-top: 20px;">
                <a href="index.php" class="button-link">¿Aún no estás registrado? Regístrate aquí</a>
               </div>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- El resto del contenido del sitio -->
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

<script>
    function myFunction() {
        document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
    }
	 document.querySelector('input[name="planeta"]').addEventListener('input', function() {
            this.value = this.value.toLowerCase(); // Convierte a minúsculas
        });
</script>

</body>
</html>
