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
INGRESE LOS DATOS
<?php
//haciendo requerimiento de archivo de conexion
require('conexion.php');
//llamando a la funcion de conexion
$conexion=Conectarse();
//haciendo la consulta de datos en base al contacto seleccionado

$qr="SELECT*FROM contactos WHERE Apellidos='".$_POST['frmcampo']."'";
//ejetanco la consutla
$result = mysqli_query($qr,$conexion);
//recorriendo la matriz de resultado y mostrando campos
$row=mysqli_fetch_array($result);
echo "<Form action='modificar2.php' method='post'
enctype='multipart/form-data'>";
echo "<table align='center' Border='0'>";
echo "<tr>";
echo "<td>";
echo "IdContacto:";
echo "</td>";
echo "<td>";
echo $row['IdContacto'];
echo "<input type='hidden' name='frmidContacto' size='8' maxlenght='8'
value='".$row['IdContacto']."' >";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Nombres:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmnombres' size='30' maxlenght='30'
value='".$row['Nombres']."'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Apellidos:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmapellidos' size='30' maxlenght='30'
value='".$row['Apellidos']."'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Direccion:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmdireccion' size='50' maxlenght='50'
value='".$row['Direccion']."'>";
echo "</td>";

echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Telefono Trabajo:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmtelefonoTrabajo' size='8' maxlenght='8'
value='".$row['TelefonoTrabajo']."'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Telefono Movil:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmtelefonoMovil' size='8' maxlenght='8'
value='".$row['TelefonoMovil']."'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "Correo Electronico:";
echo "</td>";
echo "<td>";
echo "<input type='text' name='frmcorreoE' size='50' maxlenght='50'
value='".$row['CorreoE']."'>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td align='center' colspan='2'>"; echo "<input type='submit'
value='Actualizar'>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
mysqli_close($conexion);
?>
<br>
<center>
<LI><A href="index.html">Ingresar datos</A>
<LI><A href="consultar.php">Consultar la tabla</A>
<LI><A href="eliminar.php">Eliminar contactos</A>
<LI><A href="modificar.php">Modificar contactos</A>
</center>
PHP-Guía 7 Pág. 17 de 18
</body>
</html>
