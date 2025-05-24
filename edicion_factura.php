<?php 
session_start();
include('efectos.php');
include 'factura.php';

$invoice = new Invoice();
$invoiceValues = null;
$invoiceItems = [];

// Verificar si se está enviando el formulario para actualizar la factura
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de eliminar cualquier salida como var_dump
    // var_dump($_POST); // Esta línea debe comentarse o eliminarse

    if (!empty($_POST['companyName']) && !empty($_POST['invoiceId'])) {
        $invoiceId = intval($_POST['invoiceId']);
        
        try {
            // Actualizar la factura
            $invoice->updateInvoice($invoiceId, $_POST);
            
            // Establecer un mensaje de éxito en la sesión
            $_SESSION['mensaje'] = 'Los datos se actualizaron con éxito.';
            
            // Redirigir a listado_factura.php
            header("Location: listado_factura.php");
            exit();
        } catch (Exception $e) {
            echo "Error al actualizar la factura: " . $e->getMessage();
            exit();
        }
    } else {
        echo "Faltan datos necesarios para actualizar la factura.";
        exit();
    }
}

// Cargar los datos de la factura existente si se está editando
if (!empty($_GET['update_id'])) {
    $invoiceValues = $invoice->getInvoice($_GET['update_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['update_id']);
    
    if ($invoiceValues === false || $invoiceItems === false) {
        echo "No se encontraron detalles de la factura.";
        exit();
    }
} else {
    echo "No se proporcionó el ID de factura.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura Spandre & MySQL</title>
    <link href="css/4estilo.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/factura.js"></script>
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
			<li style='float:right;'><a href='listado_factura.php'>Factura Estudiante</a></li>
        </ul>
    </nav>
	  <div class="container content-invoice">
       <form action="edicion_factura.php" id="invoice-form" method="post" class="invoice-form" role="form" novalidate>
            <div class="load-animate animated fadeInUp">
                <h2 class="title"> <?php 
                if (isset($_SESSION['alumno_id'])) {
                    echo "<style='float:center;'><a>" . htmlspecialchars($_SESSION['nombre']) . "</a>";
                }
                ?></h2>
				 <input type="hidden" name="invoiceId" value="<?php echo $invoiceValues['factura_id']; ?>">
				</div>
	<div class="seccion">
      <div class='division70'>
        <h1 class="caption2">Editar Factura</h1>
    </div>
</div>

					   <div class="row">
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
					<h3>Datos Estudiante:</h3>
					<div class="form-group">
						<input type="text" class="form-control input-transparente" name="companyName" id="companyName" placeholder="Nombre del Estudiante" value="<?php echo htmlspecialchars($invoiceValues['nombre_estudiante']); ?>" autocomplete="off" required readonly>
					</div>
                    <div class="form-group">
                        <input type="text" class="form-control input-transparente" name="documento" id="documento" placeholder="Número de Documento" value="<?php echo htmlspecialchars($invoiceValues['documento']); ?>" autocomplete="off" required readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($invoiceValues['telefono']); ?>" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="ciudad" id="ciudad" placeholder="Ciudad" value="<?php echo htmlspecialchars($invoiceValues['ciudad']); ?>" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" name="direccion" id="direccion" placeholder="Dirección Residencia"><?php echo htmlspecialchars($invoiceValues['direccion']); ?></textarea>
                    </div>
					 <div class="form-group">
                        <textarea class="form-control" rows="3" name="notes" id="notes" placeholder="Comentario (Opcional)"><?php echo isset($invoiceValues['comentario']) ? htmlspecialchars($invoiceValues['comentario']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <table class="table table-bordered table-hover" id="invoiceItem">    
                        <tr class="ocultar-en-pequenas"> 
    <th width="0.1%"><input id="checkAll" class="form-control" type="checkbox"></th>
    <th width="6%">Nombre del Curso</th>
    <th width="6%">Cantidad de horas</th>
    <th width="6%">Precio</th>                                
    <th width="6%">Total</th>
                        </tr>                            
                        <?php foreach ($invoiceItems as $item): ?>
                            <tr>
                                <td data-label="Seleccionar">
            <input class="itemRow" type="checkbox">
        </td>
        <td data-label="Nombre del Curso">
            <select name="productName[]" class="form-control">
                <option value="" disabled>Seleccionar curso</option>
                <option value="Estetica Corporal" data-price="50000" <?php echo ($item['curso'] == 'Estetica Corporal') ? 'selected' : ''; ?>>Estetica Corporal</option>
                <option value="Estetica Facial" data-price="60000" <?php echo ($item['curso'] == 'Estetica Facial') ? 'selected' : ''; ?>>Estetica Facial</option>
                <option value="Masaje reductivo" data-price="70000" <?php echo ($item['curso'] == 'Masaje reductivo') ? 'selected' : ''; ?>>Masaje reductivo</option>
                <option value="Drenaje Linfatico" data-price="80000" <?php echo ($item['curso'] == 'Drenaje Linfatico') ? 'selected' : ''; ?>>Drenaje Linfatico</option>
            </select>
        </td>
        <td data-label="Cantidad de horas">
            <select name="quantity[]" class="form-control">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo ($i == $item['cantidad_horas']) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </td>
        <td data-label="Precio">
            <input type="text" class="form-control" name="price[]" value="<?php echo htmlspecialchars($item['precio']); ?>" readonly>
        </td>
        <td data-label="Total">
            <input type="text" class="form-control" name="total[]" value="<?php echo htmlspecialchars($item['total']); ?>" readonly>
        </td>
    </tr>
                        <?php endforeach; ?>
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
                            <td><input type="text" class="form-control" name="subTotal" id="subTotal" readonly value="<?php echo htmlspecialchars($invoiceValues['sub_total']); ?>"></td>
                        </tr>
                        <tr>
                            <th>Impuesto (%)</th>
                            <td><input type="text" class="form-control" name="taxRate" id="taxRate" value="<?php echo htmlspecialchars($invoiceValues['tax_rate']); ?>"></td>
                        </tr>
                        <tr>
                            <th>Impuesto</th>
                            <td><input type="text" class="form-control" name="taxAmount" id="taxAmount" readonly value="<?php echo htmlspecialchars($invoiceValues['tax_amount']); ?>"></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td><input type="text" class="form-control" name="totalAftertax" id="totalAftertax" readonly value="<?php echo htmlspecialchars($invoiceValues['total_after_tax']); ?>"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <button type="submit" class="btn btn-primary">Actualizar Factura</button>
                </div>
            </div>
        </form>
    </div>
	
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
