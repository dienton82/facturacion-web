<html lang="es">
<head>
<meta charset="UTF-8">
<title>::Modificacion de Contactos::</title>
<meta name="description" content=" Ejemplo de uso de bases de datos con PHP y MySQL ">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/estilo2.css">
<body>
<center><H1>Proceso de Modificacion</H1>
<br>
<br>
Datos Modificados
<?php
//haciendo requerimiento de archivo de conexion
require('conexion.php');
//llamando a la funcion de conexion
$conexion=Conectarse();
//capturando datos del formulario enterior
echo $idcontacto=$_POST['frmidContacto']."<br>";
echo $nombres=$_POST['frmnombres']."<br>";
echo $apellidos=$_POST['frmapellidos']."<br>";
echo $direccion=$_POST['frmdireccion']."<br>";
echo $telefonoTrabajo=$_POST['frmtelefonoTrabajo']."<br>";
echo $telefonoMovil=$_POST['frmtelefonoMovil']."<br>";
echo $correoE=$_POST['frmcorreoE']."<br>";
//definiendo los campos a ser actualizados en la consulta, Apellidos=$apellidos,TelefonoTrabajo=$telefonoTrabajo,TelefonoMovil=$telefonoMovil,CorreoE=$correoE
$qr="UPDATE contactos SET Nombres='$nombres' where
IdContacto='$idcontacto'";
//ejecutando la consulta
mysqli_query($qr,$conexion);
//verificando el numero de elementos afectados por la consulta hecha
$nr=mysqli_affected_rows();
if($nr>0){
echo "Los Registros se modificaron satisfactoriamente";
}
else{
echo "No se lograron actualizar los datos";
}
mysqli_close($conexion);
?>
<br>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
</body>
</html>
