<html lang="es">
<head>
<meta charset="UTF-8">
<title>::Eliminacion de Contactos::</title>
<meta name="description" content=" Ejemplo de uso de bases de datos con PHP y MySQL ">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/estilo2.css">
<title> </title>
<body>
<center><H1>Proceso de Eliminación</H1>
<br>
<br>
SELECCIONE UN CONTACTO
</center>
<?php
//haciendo requerimiento de archivo de conexion
require('conexion.php');
//llamando a la funcion de conexion
$conexion=Conectarse();
//generando la consuta a la base de datos
$qr="SELECT Apellidos FROM contactos ORDER BY IdContacto ASC";
//ejecutando consulta
$result=mysqli_query($qr,$conexion);
//obteniendo las filas afectadas
$nr=mysqli_affected_rows();
if($nr>0){
//generando el listado si es que hay elementos que borrar
echo "<form
action=\"eliminar_query.php\"method=\"post\"enctype=\"multipart/formdata\">";
echo "<table align=\"center\"border=\"1\">";
echo "<tr>";
echo "<td>";
echo "<select name=\"frmcampo\">";
while($row=mysqli_fetch_array($result)){
printf("<option value=\"%s\">%s",$row["Apellidos"],$row["Apellidos"]);
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<td>";
echo "<input type=\"submit\"value=\"Eliminar\">";
echo "</td>";
echo "</table>";
echo "</form>";
}
mysqli_close($conexion);
?>
</body>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
</html>
