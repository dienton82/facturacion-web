<html lang="es">
<head>
<meta charset="UTF-8">
<title>::Modificacion de Contactos::</title>
<meta name="description" content=" Ejemplo de uso de bases de datos con PHP y MySQL ">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/estilo2.css">
</head>
<body>
<center><H1>Proceso de Modificacion</H1>
<br>
<br>
SELECCIONE UN CONTACTO
</center>
<?php
//haciendo requerimiento de archivo de conexion
require('conexion.php');
//llamando a la funcion de conexion
$conexion=Conectarse();
//realizando la cadena de consulta para buscar los contactos
$qr="SELECT Apellidos FROM contactos ORDER BY IdContacto ASC";
//ejecutando la consulta
$result=mysqli_query($qr,$conexion);
//verificando el numero de filas que fueron afectadas
$nr=mysqli_affected_rows();
if($nr>0){
echo "<form action='modificar1.php' method='post'
enctype='multipart/form-data'>";
echo "<table align='center' border='1'>";
echo "<tr>";
echo "<td>";

echo "<select name='frmcampo'>";
while($row=mysqli_fetch_array($result)){
printf("<option value='%s'>%s",$row["Apellidos"],$row["Apellidos"]);
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<th colspan='2' align='center'>";
echo "<input type='submit' value='Enviar'>";
echo "</th>";
echo "</table>";
echo "</form>";
mysqli_close($conexion);
}
else{
echo "<center><font color='red'>NO SE ENCONTRO NINGUN REGISTRO EN
LA BD</center>";
}
?>
</body>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
</html>
