<html>
<head>
<title>conexion a base de datos con PHP</title>
</head>
<body>

<?php
function Conectarse()
{
$servername="localhost:3308";
$username="root";
$passwords ='';
$dbname='contacto';


$conexion=mysqli_connect($servername,$username);
{
echo "Error conectando a la base de datos.";
exit();
}
mysqli_select_db("control",$conexion );
{
echo "Error seleccionando la base de datos, verifique que el nombre de usuario utilizado este asociado a la base de datos.";
exit();
}
return $conexion;
}



