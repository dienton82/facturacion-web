<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/4estilo.css"> <!-- Ruta hacia la hoja de estilos -->
</head>
<body>

<nav>
    <ul class="topnav">
        <li style="max-height:64px;">
            <img src="img/logo1.png" alt="logo" class="logo">
        </li>
        <li style="float:right;">
            <a href="ingresar.php">Ingresar</a>
        </li>
        <li style="float:right;">
            <a href="registrar.php">Registrarse</a>
        </li>
    </ul>
</nav>

<div class="container">
    <h1>Restablecer Contraseña</h1>
    <form action="procesar_restablecer.php" method="post">
        <div class="form-group">
            <input type="password" name="password" placeholder="Nueva Contraseña" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" class="form-control" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Restablecer Contraseña" class="form-control">
        </div>
    </form>
</div>

<script>
    function myFunction() {
        document.getElementsByClassName("topnav")[0].classList.toggle("responsive");
    }
</script>
</body>
</html>
