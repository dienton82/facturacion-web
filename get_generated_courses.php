<?php
header('Content-Type: application/json');

require_once 'factura.php'; // Asegúrate de incluir la clase Invoice

$invoice = new Invoice();
$alumnoId = isset($_POST['alumno_id']) ? intval($_POST['alumno_id']) : 0;

$courses = $invoice->getInvoices($alumnoId);

$courseArray = [];
foreach ($courses as $course) {
    $productDetails = json_decode($course['product_details'], true);
    foreach ($productDetails as $detail) {
        if (!in_array($detail['curso'], $courseArray)) {
            $courseArray[] = $detail['curso'];
        }
    }
}

echo json_encode([
    'status' => 'success',
    'courses' => $courseArray
]);
