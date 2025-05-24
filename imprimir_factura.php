<?php
session_start();
include 'factura.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['alumno_id'])) {
    die("No tienes permiso para acceder a esta página.");
}

// Incluir Dompdf y su autoloader
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf; // Mover la declaración use al principio, después del autoloader

// Verificar si se proporciona el ID de la factura
if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    $invoice = new Invoice();
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);

    // Verifica si se obtuvieron detalles de la factura
    if ($invoiceValues) {
        $invoiceDate = date("d/M/Y, H:i:s", strtotime($invoiceValues['fecha']));
        $output = '<table width="100%" border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td colspan="2" align="center" style="font-size:18px"><b>FACTURA</b></td>
        </tr>
        <tr>
            <td colspan="2">
            <table width="100%" cellpadding="5">
            <tr>
                <td width="65%">
                Para,<br />
                <b>RECEPTOR (FACTURA A)</b><br />
                Nombres : ' . htmlspecialchars($invoiceValues['nombre_estudiante']) . '<br /> 
                Documento: ' . htmlspecialchars($invoiceValues['documento']) . '<br />
				 Teléfono: ' . htmlspecialchars($invoiceValues['telefono']) . '<br />
                Dirección: ' . htmlspecialchars($invoiceValues['direccion']) . '<br />
				  Ciudad: ' . htmlspecialchars($invoiceValues['ciudad']). '<br /> <!-- Muestra la ciudad del alumno -->
               Correo electrónico: ' . htmlspecialchars($invoiceValues['email']) . '<br /> <!-- Muestra el correo del alumno -->
				
                </td>
                <td width="35%">         
                Factura No. : ' . htmlspecialchars($invoiceValues['factura_id']) . '<br />
                Fecha de Creación : ' . $invoiceDate . '<br />
                </td>
            </tr>
            </table>
            <br />
            <table width="100%" border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th align="left">Sr No.</th>
                <th align="left">Curso</th>
                <th align="left">Cantidad de horas</th>
                <th align="left">Precio</th>
                <th align="left">Total</th> 
            </tr>';
        
        // Obtener los detalles de los productos (cursos)
        $productDetails = json_decode($invoiceValues['product_details'], true);
        $count = 0;

        if (is_array($productDetails)) {
            foreach ($productDetails as $product) {
                $count++;
                $output .= '
                <tr>
                    <td align="left">' . $count . '</td>
                    <td align="left">' . htmlspecialchars($product['curso']) . '</td>
                    <td align="left">' . htmlspecialchars($product['cantidad_horas']) . '</td>
                    <td align="left">' . htmlspecialchars($product['precio']) . '</td>
                    <td align="left">' . htmlspecialchars($product['total']) . '</td>
                </tr>';
            }
        }

        // Agregar los totales e impuestos
        $output .= '
            <tr>
                <td align="right" colspan="4"><b>Sub Total</b></td>
                <td align="left"><b>' . htmlspecialchars($invoiceValues['sub_total']) . '</b></td>
            </tr>
            <tr>
                <td align="right" colspan="4"><b>Impuesto (3%)</b></td>
                <td align="left">' . htmlspecialchars($invoiceValues['tax_amount']) . '</td>
            </tr>
            <tr>
                <td align="right" colspan="4"><b>Total después de impuestos</b></td>
                <td align="left">' . htmlspecialchars($invoiceValues['total_after_tax']) . '</td>
            </tr>';

        $output .= '
            </table>
            </td>
        </tr>
        </table>';

        // Generar PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml(html_entity_decode($output));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $invoiceFileName = 'Invoice-' . htmlspecialchars($invoiceValues['factura_id']) . '.pdf';
        $dompdf->stream($invoiceFileName, array("Attachment" => false));
    } else {
        echo "Factura no encontrada.";
    }
} else {
    echo "ID de factura no proporcionado.";
}
?>
