<html lang="es">
<head>
<meta charset="UTF-8">
<title>::Eliminacion de Contactos::</title>
<meta name="description" content=" Ejemplo de uso de bases de datos con PHP y MySQL ">

<link rel="stylesheet" href="css/estilo2.css">
<title> </title>
<body>
<center><H1>Proceso de Eliminación</H1>
<br>
<br>
</center>
<?php
//haciendo requerimiento de archivo de conexion
require('conexion.php');
//llamando a la funcion de conexion
$conexion=Conectarse();
//creando cadena de consulta para eliminar datos
$qr="delete from contactos where Apellidos='".$_POST['frmcampo']."'";
//realizando la eliminacion del registro
mysqli_query($qr,$conexion);
//imprimmiendo en pantalla la cantidad de registros afectados
echo mysqli_affected_rows()."<br>";
mysqli_close($conexion)
?>
<br>
</body>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
</html>
