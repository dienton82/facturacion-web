<html lang="es">
<head>
<meta charset="UTF-8">
<title>Consulta a la tabla contactos</title>
<meta name="description" content=" Ejemplo de uso de bases de datos con PHP y MySQL ">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/estilo2.css">
<title> </title>
<body>
<H1>Datos que contiene la tabla contactos</H1>
<?php
include("conexion.php");
$conexion=Conectarse();
$result=mysqli_query("select * from contactos",$conexion);
?>
<TABLE BORDER=1 CELLSPACING=1 CELLPADDING=1>
<TR>
<TD>&nbsp;Nombres</TD>
<TD>&nbsp;Apellidos</TD>
<TD>&nbsp;Dirección&nbsp;</TD>
<TD>&nbsp;TelefonoTrabajo&nbsp;</TD>
<TD>&nbsp;TelefonoMovil</TD>
<TD>&nbsp;Email&nbsp;</TD>
</TR>
<?php
while($row = mysqli_fetch_array($result)) {
printf("<tr><td>&nbsp;%s</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>&nbsp;%s&nbsp;</td>
<td>%s&nbsp;</td></tr>",
$row["Nombres"],$row["Apellidos"],$row["Direccion"],$row["TelefonoTrabajo
"],$row["TelefonoMovil"],$row["CorreoE"]);
}
mysqli_free_result($result);
?>
</table>
<br>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
</body>
</html>
