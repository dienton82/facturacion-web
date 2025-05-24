<?php 
session_start();
include('efectos.php');
include 'factura.php';

// Asegúrate de que el usuario esté autenticado
if (!isset($_SESSION['alumno_id'])) {
    die("No tienes permiso para acceder a esta página.");
}

// Obtén el ID del alumno desde la sesión
$alumnoId = $_SESSION['alumno_id'];

// Crea una instancia de la clase Invoice
$invoice = new Invoice();

// Obtén las facturas para el alumno actual
$invoiceList = $invoice->getInvoices($alumnoId);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Spandre & MySQL</title>
    <script src="js/factura.js"></script>
    <link href="css/3estilo.css" rel="stylesheet">
    <style>
        /* Estilos para el mensaje flotante */
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

        /* Estilos para los botones de acción */
        .action-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            padding: 5px;
            margin: 0 auto;
            background-color: #f5f5f5;
            border-radius: 5px;
            cursor: pointer;
			}
			
		@media screen and (max-width: 768px) {
        .action-btn {
         margin-left: 6rem;
        }
		}
        .action-btn img {
            width: 100%;
            height: auto;
        }

        /* Estilos adicionales para hover */
        .action-btn:hover {
            background-color: #ddd;
        }

        /* Estilos específicos para cada acción */
        .btn-imprimir {
            background-color: #4CAF50; /* Verde para imprimir */
        }

        .btn-editar {
            background-color: #2196F3; /* Azul para editar */
        }

        .btn-borrar {
            background-color: #f44336; /* Rojo para borrar */
        }

        /* Cambiar el color del icono al pasar el cursor */
        .btn-imprimir:hover img,
        .btn-editar:hover img,
        .btn-borrar:hover img {
            filter: brightness(0.8);
        }

        /* Estilos para los detalles de los productos */
        .product-details {
            font-size: 0.9em;
            color: #fff;
        }

        .product-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .product-details li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
   <nav>
        <ul class="topnav">
            <li class="logo-container"> <!-- Contenedor del logo y el texto -->
                <img src="img/logo1.png" alt="logo" class="logo">
                <p class="diplomados">Diplomados en Estética</p> <!-- Texto debajo del logo -->
            </li>
			      <li style="float:right;">
                <a href="logout.php">Cerrar Sesión</a>
            </li>
			
        </ul>
    </nav>
    
    <div class="container content-invoice">
        <div class="load-animate animated fadeInUp">
            <h2 class="title"> 
                <?php 
                if (isset($_SESSION['alumno_id'])) {
                    echo "<style='float:center;'><a>" . htmlspecialchars($_SESSION['nombre']) . "</a>";
                }
                ?>
            </h2>
        </div>

        <div class="seccion">
            <div class='division70'>
                <h1 class="caption2">Factura Spandre</h1>
            </div>
        </div>

        <!-- Mensaje flotante -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div id="mensaje-flotante" class="mensaje-flotante">
                <?php 
                echo htmlspecialchars(strip_tags($_SESSION['mensaje'])); // Limpiar etiquetas HTML
                unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <!-- Tabla de facturas con estructura responsiva -->
        <table id="data-table" class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th>N° Factura</th>
                    <th>Fecha de Creación</th>
                    <th>Nombre del Estudiante Registrado</th>
                    <th>Correo Registrado</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Detalles del Curso</th>
                    <th>Impuesto (3%)</th>
                    <th>Total Factura</th>
                    <th>Editar Factura</th>
                    <th>Imprimir Factura</th>
                </tr>
            </thead>
        
       
         <?php
if ($invoiceList && is_array($invoiceList)) {
    foreach ($invoiceList as $invoiceDetails) {
        $timestamp = strtotime($invoiceDetails["fecha"]);
        $invoiceDate = ($timestamp === false) ? "Fecha inválida" : date("d/M/Y, H:i:s", $timestamp);
        $productDetails = json_decode($invoiceDetails["product_details"], true);

        $productDetailsHtml = '<div class="product-details">';
        if (is_array($productDetails)) {
            $productDetailsHtml .= '<ul>';
            foreach ($productDetails as $product) {
                $productDetailsHtml .= '<li>' 
                    . 'Curso: ' . htmlspecialchars($product['curso']) . ' | '
                    . 'Horas: ' . htmlspecialchars($product['cantidad_horas']) . ' | '
                    . 'Precio: ' . htmlspecialchars($product['precio']) . ' | '
                    . 'Total: ' . htmlspecialchars($product['total']) . '</li>';
            }
            $productDetailsHtml .= '</ul>';
        }
        $productDetailsHtml .= '</div>';

        echo '
        <tr>
            <td data-label="N° Factura">' . htmlspecialchars($invoiceDetails["factura_id"]) . '</td>
            <td data-label="Fecha de Creación">' . htmlspecialchars($invoiceDate) . '</td>
            <td data-label="Nombre del Estudiante">' . htmlspecialchars($invoiceDetails["nombre_estudiante"]) . '</td>
            <td data-label="Correo">' . htmlspecialchars($invoiceDetails["email"]) . '</td>
            <td data-label="Documento">' . htmlspecialchars($invoiceDetails["documento"]) . '</td>
            <td data-label="Teléfono">' . htmlspecialchars($invoiceDetails["telefono"]) . '</td>
            <td data-label="Detalles del Curso">' . $productDetailsHtml . '</td>
            <td data-label="Impuesto (3%)">' . htmlspecialchars($invoiceDetails["tax_amount"]) . '</td>
            <td data-label="Total Factura">' . htmlspecialchars($invoiceDetails["total_after_tax"]) . '</td>
            <td data-label="Editar Factura">
                <a href="edicion_factura.php?update_id=' . htmlspecialchars($invoiceDetails["factura_id"]) . '" title="Editar Factura">
                    <div class="action-btn btn-editar">
                        <img src="img/edit.png" alt="Editar">
                    </div>
                </a>
            </td>
            <td data-label="Imprimir Factura">
                <a href="imprimir_factura.php?invoice_id=' . htmlspecialchars($invoiceDetails["factura_id"]) . '" title="Imprimir Factura" target="_blank">
                    <div class="action-btn btn-imprimir">
                        <img src="img/printer.png" alt="Imprimir">
                    </div>
                </a>
            </td>
        </tr>';
                }
            } else {
                echo '<tr><td colspan="8">No hay facturas disponibles.</td></tr>';
            }
            ?>
        </tbody>
    </table>
    <?php include('footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Ocultar el mensaje flotante después de 5 segundos
            var mensajeFlotante = $('#mensaje-flotante');
            if (mensajeFlotante.length) {
                setTimeout(function () {
                    mensajeFlotante.addClass('ocultar');
                }, 5000); // 5 segundos
            }
        });
    </script>
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
