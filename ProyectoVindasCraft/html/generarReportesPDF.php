<?php
session_start();
include('ConexionBD.php');

// Verificar sesión y rol
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
    header("Location: InicioSesion.php");
    exit();
}

$fecha_inicio = $_GET['fecha_inicio'] ?? '2000-01-01';
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');

// Validar que la fecha de inicio no sea mayor que la de fin
if ($fecha_inicio > $fecha_fin) {
    $_SESSION['mensaje'] = "La fecha de inicio no puede ser mayor que la fecha de fin.";
    $_SESSION['mensaje_tipo'] = "alert-warning";
    header("Location: Presupuestos.php");
    exit();
}

$sql = "SELECT p.id_presupuesto, u.nombre AS usuario, p.detalles, p.nombre_proyecto, p.monto_estimado, p.estado, p.fecha_creacion 
        FROM presupuestos p 
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.fecha_creacion BETWEEN ? AND ? 
        ORDER BY p.fecha_creacion DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$result = $stmt->get_result();

// Si no hay resultados
if ($result->num_rows === 0) {
    $_SESSION['mensaje'] = "No hay presupuestos en el rango seleccionado ($fecha_inicio - $fecha_fin).";

    $_SESSION['mensaje_tipo'] = "alert-warning";
    header("Location: Presupuestos.php");
    exit();
}

// Incluir la librería FPDF
require('../src/fpdf186/fpdf.php');

// Crear una instancia de FPDF en formato horizontal
$pdf = new FPDF('L'); // 'L' para orientación horizontal (landscape)
$pdf->AddPage();

// Agregar el logo
$logo = '../assets/images/logo.jpg'; // Ruta del logo
$width_logo = 33; // Ancho del logo
$pdf->Image($logo, 250, 8, $width_logo); // Posición (x, y) y tamaño (ajustado para horizontal)

// Título del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Reporte de Presupuestos", 0, 1, 'C');
$pdf->Ln(10); // Salto de línea
 
// Datos del reporte en líneas separadas
$pdf->SetFont('Arial', '', 12);
while ($row = $result->fetch_assoc()) {
    $pdf->Cell(0, 10, "ID: " . $row['id_presupuesto'], 0, 1);
    $pdf->Cell(0, 10, "Usuario: " . utf8_decode($row['usuario']), 0, 1);
    $pdf->Cell(0, 10, "Detalles: " . utf8_decode($row['detalles']), 0, 1);
    $pdf->Cell(0, 10, "Nombre del proyecto: " . utf8_decode($row['nombre_proyecto']), 0, 1);
    $pdf->Cell(0, 10, "Monto estimado: " . $row['monto_estimado'], 0, 1);
    $pdf->Cell(0, 10, "Estado: " . $row['estado'], 0, 1);
    $pdf->Cell(0, 10, "Fecha de creación: " . $row['fecha_creacion'], 0, 1);
   
    $pdf->Ln(5); // Línea en blanco para separar cada reporte
}

// Pie de página
$pdf->Ln(10); // Salto de línea
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Este es un reporte generado automaticamente.', 0, 1, 'C');

// Generar el archivo PDF
$pdf_filename = "Reporte_Presupuestos_" . date('Ymd_His') . ".pdf";
$pdf->Output('D', $pdf_filename); // 'D' para descargar directamente
exit();
?>