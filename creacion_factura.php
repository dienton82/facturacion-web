<?php 
session_start(); 
include('efectos.php'); 
include 'factura.php'; 

$invoice = new Invoice();
$companyName = isset($invoiceDetails['nombre_estudiante']) ? $invoiceDetails['nombre_estudiante'] : '';

// Verifica si el usuario está logueado
if (!isset($_SESSION['alumno_id'])) {
    header("Location: index.php?message=not_registered");
    exit();
}
// Mostrar el mensaje flotante si existe en la sesión
if (isset($_SESSION['mensaje'])) {
    echo '<div id="mensaje-flotante" class="mensaje-flotante">' . $_SESSION['mensaje'] . '</div>';
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}


// Inicializar variables para persistencia de datos después de guardar
$companyName = '';
$documento = '';
$telefono = '';
$ciudad = '';
$direccion = '';
$comentario = '';
$subTotal = '';
$taxRate = '3'; // Valor predeterminado
$taxAmount = '';
$totalAftertax = '';

// Recuperar los datos del usuario si está logueado
if (isset($_SESSION['alumno_id'])) {
    $alumno = $invoice->getAlumno($_SESSION['alumno_id']);
    if ($alumno) {
        $companyName = $alumno['nombre'] ?? '';
        $documento = $alumno['documento'] ?? '';
        $telefono = $alumno['telefono'] ?? '';
        $ciudad = $alumno['ciudad'] ?? '';
        $direccion = $alumno['direccion'] ?? '';
    }
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar los datos
    $companyName = isset($_POST['companyName']) ? htmlspecialchars(trim($_POST['companyName'])) : '';
    $documento = isset($_POST['documento']) ? htmlspecialchars(trim($_POST['documento'])) : '';
    $telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : '';
    $ciudad = isset($_POST['ciudad']) ? htmlspecialchars(trim($_POST['ciudad'])) : '';
    $direccion = isset($_POST['direccion']) ? htmlspecialchars(trim($_POST['direccion'])) : '';
    $comentario = isset($_POST['notes']) ? htmlspecialchars(trim($_POST['notes'])) : '';

    $subTotal = isset($_POST['subTotal']) ? filter_var($_POST['subTotal'], FILTER_VALIDATE_FLOAT) : 0;
    $taxRate = isset($_POST['taxRate']) ? filter_var($_POST['taxRate'], FILTER_VALIDATE_FLOAT) : 3;
    $taxAmount = isset($_POST['taxAmount']) ? filter_var($_POST['taxAmount'], FILTER_VALIDATE_FLOAT) : 0;
    $totalAftertax = isset($_POST['totalAftertax']) ? filter_var($_POST['totalAftertax'], FILTER_VALIDATE_FLOAT) : 0;

    // Datos de los cursos
    $productName = isset($_POST['productName']) ? $_POST['productName'] : [];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $price = isset($_POST['price']) ? $_POST['price'] : [];
    $total = isset($_POST['total']) ? $_POST['total'] : [];

    // Verificar que los datos de curso son válidos
    if (!empty($companyName) && !empty($telefono) && !empty($ciudad) && !empty($productName) && count($productName) > 0) {
        $result = $invoice->saveInvoice([
            'alumno_id' => $_SESSION['alumno_id'],
            'companyName' => $companyName,
            'documento' => $documento,
            'telefono' => $telefono,
            'ciudad' => $ciudad,
            'direccion' => $direccion,
            'comentario' => $comentario,
            'sub_total' => $subTotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'total_after_tax' => $totalAftertax,
            'curso' => $productName,
            'cantidad_horas' => $quantity,
            'precio' => $price,
            'total' => $total
        ]);

        if ($result) {
            // Establecer un mensaje de éxito en la sesión
            $_SESSION['mensaje'] = 'Factura guardada con éxito.';
            // Redirigir a listado_factura.php después de guardar
            header("Location: listado_factura.php");
            exit(); // Asegurarse de que el script se detenga
        } else {
            $error = 'Error al guardar la factura. Por favor, inténtelo de nuevo.';
        }
    } else {
        $error = 'Por favor, complete todos los campos requeridos.';
    }
}

// Mostrar mensaje de error si existe
if (isset($error)) {
    echo "<script>alert('$error');</script>";
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Spandre & MySQL</title>
    <link href="css/4estilo.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/factura.js"></script>
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
        <form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate>
            <div class="load-animate animated fadeInUp">
                <h2 class="title"> <?php 
                if (isset($_SESSION['alumno_id'])) {
                    echo "<style='float:center;'><a>" . htmlspecialchars($_SESSION['nombre']) . "</a>";
                }
                ?></h2>
				
            </div>
               
            
				<div class="seccion">
    <div class='division70'>
        <h1 class="caption2">Crear Factura</h1>
    </div>
</div>

                
            <div class="row">
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
        <h3>Datos Estudiante:</h3>
        <div class="form-group">
            <input type="text" class="form-control input-transparente" name="companyName" id="companyName" placeholder="Nombre del Estudiante" value="<?php echo htmlspecialchars($companyName); ?>" autocomplete="off" required readonly>
        </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="documento" id="documento" placeholder="Número de Documento" value="<?php echo htmlspecialchars($documento); ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($telefono); ?>" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad" value="<?php echo htmlspecialchars($ciudad); ?>" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" name="direccion" id="direccion" placeholder="Dirección Residencia"><?php echo htmlspecialchars($direccion); ?></textarea>
				    </div>
					 <div class="form-group">
                        <textarea class="form-control" rows="3" name="notes" id="notes" placeholder="Comentario (Opcional)"><?php echo isset($invoiceValues['comentario']) ? htmlspecialchars($invoiceValues['comentario']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <table class="table table-bordered table-hover" id="invoiceItem">    
                        <thead>
                            <tr class="ocultar-en-pequenas"> 
                                <th width="0.1%"><input id="checkAll" class="form-control" type="checkbox"></th>
                                <th width="6%">Nombre del Curso</th>
                                <th width="6%">Cantidad de horas</th>
                                <th width="6%">Precio</th>                                
                                <th width="6%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
							 <td data-label="Seleccionar">
                                <input class="itemRow" type="checkbox"></td>
                                <td data-label="Nombre del Curso">
                                    <select name="productName[]" class="form-control">
                                        <option value="" disabled selected>Seleccionar curso</option>
                                        <option value="Estetica Corporal" data-price="50000">Estetica Corporal</option>
                                        <option value="Estetica Facial" data-price="60000">Estetica Facial</option>
                                        <option value="Masaje reductivo" data-price="70000">Masaje reductivo</option>
                                        <option value="Drenaje Linfatico" data-price="80000">Drenaje Linfatico</option>
                                    </select>
                                </td>
                                <td data-label="Cantidad de horas">
                                    <select name="quantity[]" class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </td>
                                <td data-label="Precio">
								<input type="text" class="form-control" id="price_1" name="price[]" readonly></td>
                                
								<td data-label="Total">
								<input type="text" class="form-control" id="total_1" name="total[]" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <button type="button" id="addRows" class="btn btn-primary">Añadir Curso</button>
                            <button type="button" id="removeRows" class="btn btn-danger">Eliminar Curso(s)</button>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>Sub Total</th>
                            <td><input type="text" class="form-control" name="subTotal" id="subTotal" readonly value="<?php echo htmlspecialchars($subTotal); ?>"></td>
                        </tr>
                        <tr>
                            <th>Impuesto (%)</th>
                            <td><input type="text" class="form-control" name="taxRate" id="taxRate" value="<?php echo htmlspecialchars($taxRate); ?>"></td>
                        </tr>
                        <tr>
                            <th>Impuesto</th>
                            <td><input type="text" class="form-control" name="taxAmount" id="taxAmount" readonly value="<?php echo htmlspecialchars($taxAmount); ?>"></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td><input type="text" class="form-control" name="totalAftertax" id="totalAftertax" readonly value="<?php echo htmlspecialchars($totalAftertax); ?>"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <button type="submit" class="btn btn-primary">Guardar Factura</button>
                </div>
            </div>
        </form>
    </div>
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

   <script>
    $(document).ready(function() {
        // Manejo de eventos y cálculos
        $("#addRow").on("click", function() {
            var rowCount = $("#invoiceItem tr").length;
            var newRow = "<tr><td><input class='itemRow' type='checkbox'></td><td><select name='productName[]' id='productName_" + rowCount + "' class='form-control'><option value='Estetica Corporal' data-price='50000'>Estetica Corporal</option><option value='Estetica Facial' data-price='60000'>Estetica Facial</option><option value='Masaje reductivo' data-price='70000'>Masaje reductivo</option><option value='Drenaje Linfatico' data-price='80000'>Drenaje Linfatico</option></select></td><td><select name='quantity[]' id='quantity_" + rowCount + "' class='form-control'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select></td><td><input type='text' name='price[]' id='price_" + rowCount + "' class='form-control' readonly></td><td><input type='text' name='total[]' id='total_" + rowCount + "' class='form-control' readonly></td></tr>";
            $("#invoiceItem").append(newRow);
        });

        $("#invoiceItem").on("change", "select", function() {
            calculateRow(this);
            calculateTotal();
        });

        function calculateRow(element) {
            var row = $(element).closest("tr");
            var price = $(row).find("select[name='productName[]'] option:selected").data("price");
            var quantity = $(row).find("select[name='quantity[]']").val();
            var total = price * quantity;
            $(row).find("input[name='price[]']").val(price.toFixed(2));
            $(row).find("input[name='total[]']").val(total.toFixed(2));
        }

        function calculateTotal() {
            var subTotal = 0;
            $("#invoiceItem tr").each(function() {
                var total = parseFloat($(this).find("input[name='total[]']").val()) || 0;
                subTotal += total;
            });
            $("#subTotal").val(subTotal.toFixed(2));

            var taxRate = parseFloat($("#taxRate").val()) || 3;
            var taxAmount = subTotal * (taxRate / 100);
            $("#taxAmount").val(taxAmount.toFixed(2));

            var totalAfterTax = subTotal + taxAmount;
            $("#totalAftertax").val(totalAfterTax.toFixed(2));
        }

        // Funcionalidad para "checkAll"
        $('#checkAll').click(function() {
            var isChecked = $(this).prop('checked');
            $('.itemRow').prop('checked', isChecked); // Seleccionar/deseleccionar todos
        });

        // Si se desmarca algún checkbox individual, desmarcar "checkAll"
        $('#invoiceItem').on('change', '.itemRow', function() {
            if ($('.itemRow:checked').length == $('.itemRow').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
        }
        });

    });
</script>

</body>
</html>
