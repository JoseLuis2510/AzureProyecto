<?php
// librerías necesarias
require('../src/fpdf186/fpdf.php');
require('../src/Exception.php');
require('../src/PHPMailer.php');
require('../src/SMTP.php');

// Conexión a la base de datos
include('ConexionBD.php');


$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Consulta SQL para obtener los registros de auditoría
$sql = "SELECT * FROM auditoria WHERE 1";


if ($fecha_inicio) {
    $sql .= " AND fecha >= '$fecha_inicio 00:00:00'";
}
if ($fecha_fin) {
    $sql .= " AND fecha <= '$fecha_fin 23:59:59'";
}

$sql .= " ORDER BY fecha DESC"; 


$result = $conn->query($sql);
$auditoria = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $auditoria[] = $row;
    }
}


$pdf = new FPDF();
$pdf->AddPage();


$logo = '../assets/images/logo.jpg'; 
$width_logo = 33; 
$pdf->Image($logo, 170, 8, $width_logo); 


$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Informe de Rotacion', 0, 1, 'C');
$pdf->Ln(10);


$pdf->SetFont('Arial', '', 12);


foreach ($auditoria as $registro) {

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Movimiento ID: ' . $registro['id_auditoria'], 0, 1);
    $pdf->Ln(2);


    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, "ID Material: " . $registro['id_material']);
    $pdf->MultiCell(0, 8, "Accion: " . $registro['accion']);
    $pdf->MultiCell(0, 8, "Material Anterior: " . $registro['nombre_material_anterior']);
    $pdf->MultiCell(0, 8, "Precio Anterior: " . number_format($registro['precio_anterior'], 2));
    $pdf->MultiCell(0, 8, "Stock Anterior: " . $registro['stock_anterior']);
    $pdf->MultiCell(0, 8, "Proveedor Anterior: " . $registro['proveedor_anterior']);
    $pdf->MultiCell(0, 8, "Material Nuevo: " . $registro['nombre_material_nuevo']);
    $pdf->MultiCell(0, 8, "Precio Nuevo: " . number_format($registro['precio_nuevo'], 2));
    $pdf->MultiCell(0, 8, "Proveedor Nuevo: " . $registro['proveedor_nuevo']);
    $pdf->MultiCell(0, 8, "Fecha: " . date('d/m/Y', strtotime($registro['fecha'])));


    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); 
    $pdf->Ln(5); 

    
    if ($pdf->GetY() > 250) {  
        $pdf->AddPage();
    }
}


$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'Página ' . $pdf->PageNo(), 0, 0, 'C');


$pdf->Output('D', 'Rotacion_Inventario.pdf'); 
?>
