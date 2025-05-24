<?php

class Invoice {
    private $host = "localhost";
    private $user = 'root';
    private $password = "";
    private $database = "cursos";
    private $alumnosTable = 'alumnos';
    private $invoiceOrderTable = 'factura_orden';

    private $dbConnect;

    public function __construct() {
        $this->dbConnect = new mysqli($this->host, $this->user, $this->password, $this->database);
        if ($this->dbConnect->connect_error) {
            die("Error al conectar a la base de datos MySQL: " . $this->dbConnect->connect_error);
        }
        $this->dbConnect->set_charset("utf8mb4");
    }

    private function getData($sqlQuery, $params = [], $types = '') {
        $stmt = $this->dbConnect->prepare($sqlQuery);
        if (!$stmt) {
            die('Error en la preparación de la consulta: ' . $this->dbConnect->error);
        }
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die('Error en la consulta: ' . $this->dbConnect->error);
        }
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;            
        }
        return $data;
    }

    public function getAlumno($alumnoId) {
        $sql = "SELECT * FROM " . $this->alumnosTable . " WHERE alumno_id = ?";
        $stmt = $this->dbConnect->prepare($sql);
        if (!$stmt) {
            die('Error en la preparación de la consulta de selección de alumno: ' . $this->dbConnect->error);
        }
        $stmt->bind_param("i", $alumnoId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

   public function getInvoice($invoiceId) {
    $sql = "
        SELECT f.*, a.email 
        FROM " . $this->invoiceOrderTable . " f
        JOIN " . $this->alumnosTable . " a ON f.alumno_id = a.alumno_id
        WHERE f.factura_id = ?";
        
    $stmt = $this->dbConnect->prepare($sql);
    if (!$stmt) {
        die('Error en la preparación de la consulta de selección de factura: ' . $this->dbConnect->error);
    }
    $stmt->bind_param("i", $invoiceId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

    public function getInvoices($alumnoId) {
        $sql = "
            SELECT f.*, a.email 
            FROM " . $this->invoiceOrderTable . " f
            JOIN " . $this->alumnosTable . " a ON f.alumno_id = a.alumno_id
            WHERE f.alumno_id = ?
            ORDER BY f.factura_id DESC";
            
        $stmt = $this->dbConnect->prepare($sql);
        if (!$stmt) {
            die('Error en la preparación de la consulta: ' . $this->dbConnect->error);
        }
        $stmt->bind_param("i", $alumnoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $invoices = [];
        while ($row = $result->fetch_assoc()) {
            $invoices[] = $row;
        }
        $stmt->close();
        return $invoices;
    }
	
	

    public function getInvoiceItems($invoiceId) {
        $sql = "SELECT product_details FROM " . $this->invoiceOrderTable . " WHERE factura_id = ?";
        $stmt = $this->dbConnect->prepare($sql);
        if (!$stmt) {
            die('Error en la preparación de la consulta: ' . $this->dbConnect->error);
        }
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        $result = $stmt->get_result();
        $invoice = $result->fetch_assoc();

        if ($invoice && !empty($invoice['product_details'])) {
            return json_decode($invoice['product_details'], true);
        }
        
        return [];
    }

    public function saveInvoice($POST) {
        // Validar y sanitizar los datos del POST
        $alumnoId = isset($POST['alumno_id']) ? intval($POST['alumno_id']) : 0;
        $nombreEstudiante = isset($POST['companyName']) ? $this->dbConnect->real_escape_string(trim($POST['companyName'])) : '';
        $documento = isset($POST['documento']) ? $this->dbConnect->real_escape_string(trim($POST['documento'])) : ''; 
        $telefono = isset($POST['telefono']) ? $this->dbConnect->real_escape_string(trim($POST['telefono'])) : ''; 
        $ciudad = isset($POST['ciudad']) ? $this->dbConnect->real_escape_string(trim($POST['ciudad'])) : ''; 
        $direccion = isset($POST['direccion']) ? $this->dbConnect->real_escape_string(trim($POST['direccion'])) : ''; 
        $comentario = isset($POST['comentario']) ? $this->dbConnect->real_escape_string(trim($POST['comentario'])) : ''; 

        // Validar y procesar los detalles del producto
        $productDetails = [];
        if (!empty($POST['curso']) && !empty($POST['cantidad_horas']) && !empty($POST['precio'])) {
            foreach ($POST['curso'] as $index => $curso) {
                // Verificar si el curso ya está guardado
                $sql = "SELECT COUNT(*) as count FROM " . $this->invoiceOrderTable . " WHERE curso = ?";
                $stmt = $this->dbConnect->prepare($sql);
                if (!$stmt) {
                    die('Error en la preparación de la consulta: ' . $this->dbConnect->error);
                }
                $stmt->bind_param("s", $curso);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row['count'] > 0) {
                    continue; // Salta al siguiente curso si el curso ya está guardado
                }

                $productDetails[] = [
                    'curso' => $this->dbConnect->real_escape_string(trim($curso)),
                    'cantidad_horas' => intval($POST['cantidad_horas'][$index]),
                    'precio' => floatval($POST['precio'][$index]),
                    'total' => floatval($POST['total'][$index])
                ];
            }
        }

        // Codificar los detalles del producto a JSON
        $productDetailsJson = json_encode($productDetails, JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al codificar JSON: " . json_last_error_msg());
        }

        $subTotal = isset($POST['sub_total']) ? floatval(trim($POST['sub_total'])) : 0.0; 
        $taxRate = isset($POST['tax_rate']) ? floatval(trim($POST['tax_rate'])) : 0.0; 
        $taxAmount = isset($POST['tax_amount']) ? floatval(trim($POST['tax_amount'])) : 0.0; 
        $totalAftertax = isset($POST['total_after_tax']) ? floatval(trim($POST['total_after_tax'])) : 0.0; 

        $fecha = date('Y-m-d H:i:s');

        // Iniciar transacción
        $this->dbConnect->begin_transaction();
        try {
            $sql = "INSERT INTO factura_orden 
                    (alumno_id, nombre_estudiante, documento, telefono, ciudad, direccion, comentario, sub_total, tax_rate, tax_amount, total_after_tax, fecha, product_details) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->dbConnect->prepare($sql);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->dbConnect->error);
            }

            $types = 'isssssssdddss'; 
            $params = [
                $alumnoId, 
                $nombreEstudiante, 
                $documento, 
                $telefono, 
                $ciudad, 
                $direccion, 
                $comentario, 
                $subTotal, 
                $taxRate, 
                $taxAmount, 
                $totalAftertax, 
                $fecha, 
                $productDetailsJson
            ];

            $bind_params = [];
            foreach ($params as $key => $value) {
                $bind_params[] = &$params[$key];
            }

            array_unshift($bind_params, $types);
            call_user_func_array([$stmt, 'bind_param'], $bind_params);

            if ($stmt->execute()) {
                $this->dbConnect->commit();
                return true;
            } else {
                throw new Exception("Error ejecutando la consulta: " . $stmt->error);
            }
        } catch (Exception $e) {
            $this->dbConnect->rollback();
            die($e->getMessage());
        }
    }

public function updateInvoice($invoiceId, $POST) {
    // Validar y sanitizar los datos del estudiante y la factura
    $nombreEstudiante = isset($POST['companyName']) ? $this->dbConnect->real_escape_string(trim($POST['companyName'])) : '';
    $documento = isset($POST['documento']) ? $this->dbConnect->real_escape_string(trim($POST['documento'])) : ''; 
    $telefono = isset($POST['telefono']) ? $this->dbConnect->real_escape_string(trim($POST['telefono'])) : ''; 
    $ciudad = isset($POST['ciudad']) ? $this->dbConnect->real_escape_string(trim($POST['ciudad'])) : ''; 
    $direccion = isset($POST['direccion']) ? $this->dbConnect->real_escape_string(trim($POST['direccion'])) : ''; 
    $comentario = isset($POST['notes']) ? $this->dbConnect->real_escape_string(trim($POST['notes'])) : ''; 

    // Procesar y validar los detalles de los productos
    $productDetails = [];
    if (!empty($POST['productName']) && is_array($POST['productName'])) {
        foreach ($POST['productName'] as $index => $curso) {
            if (!empty($curso) && !empty($POST['quantity'][$index]) && !empty($POST['price'][$index])) {
                $productDetails[] = [
                    'curso' => $this->dbConnect->real_escape_string(trim($curso)),
                    'cantidad_horas' => intval($POST['quantity'][$index]),
                    'precio' => floatval($POST['price'][$index]),
                    'total' => floatval($POST['quantity'][$index]) * floatval($POST['price'][$index]) // Calcular el total aquí
                ];
            }
        }
    }

    // Convertir los detalles del producto a JSON
    $productDetailsJson = json_encode($productDetails, JSON_UNESCAPED_UNICODE);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error al codificar JSON: " . json_last_error_msg());
    }

    // Validar los valores financieros de la factura
    $subTotal = isset($POST['subTotal']) ? floatval(trim($POST['subTotal'])) : 0.0; 
    $taxRate = isset($POST['taxRate']) ? floatval(trim($POST['taxRate'])) : 0.0; 
    $taxAmount = isset($POST['taxAmount']) ? floatval(trim($POST['taxAmount'])) : 0.0; 
    $totalAftertax = isset($POST['totalAftertax']) ? floatval(trim($POST['totalAftertax'])) : 0.0; 

    // Iniciar la transacción
    $this->dbConnect->begin_transaction();
    try {
        // Preparar la consulta SQL para actualizar la factura
        $sql = "UPDATE " . $this->invoiceOrderTable . "
                SET nombre_estudiante = ?, documento = ?, telefono = ?, ciudad = ?, direccion = ?, comentario = ?, 
                    sub_total = ?, tax_rate = ?, tax_amount = ?, total_after_tax = ?, product_details = ? 
                WHERE factura_id = ?";

        $stmt = $this->dbConnect->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $this->dbConnect->error);
        }

        // Vincular los parámetros para la consulta
        $types = 'ssssssssddsi';
        $params = [
            $nombreEstudiante, 
            $documento, 
            $telefono, 
            $ciudad, 
            $direccion, 
            $comentario, 
            $subTotal, 
            $taxRate, 
            $taxAmount, 
            $totalAftertax, 
            $productDetailsJson, 
            $invoiceId
        ];

        // Vincular los parámetros dinámicamente
        $bind_params = [];
        foreach ($params as $key => $value) {
            $bind_params[] = &$params[$key];
        }

        // Preparar la vinculación de parámetros
        array_unshift($bind_params, $types);
        call_user_func_array([$stmt, 'bind_param'], $bind_params);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Confirmar la transacción si todo sale bien
            $this->dbConnect->commit();
            return true;
        } else {
            throw new Exception("Error ejecutando la consulta: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Si hay un error, revertir la transacción
        $this->dbConnect->rollback();
        die($e->getMessage());
    }
}


    // Métodos restantes...

    public function __destruct() {
        $this->dbConnect->close();
    }
}
?>