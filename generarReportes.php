<?php
session_start();
include('ConexionBD.php');

// Verificar sesión y rol
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
    header("Location: InicioSesion.php");
    exit();
}

// Recoger fechas del formulario
$fecha_inicio = $_GET['fecha_inicio'] ?? '2000-01-01';
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');

// Validar que la fecha de inicio no sea mayor que la de fin
if ($fecha_inicio > $fecha_fin) {
    $_SESSION['mensaje'] = "La fecha de inicio no puede ser mayor que la fecha de fin.";
    $_SESSION['mensaje_tipo'] = "alert-warning";
    header("Location: Presupuestos.php");
    exit();
}

// Verificar qué tipo de reporte se quiere generar
$tipo_reporte = $_GET['reporte'] ?? '';

// Si se seleccionó CSV, redirigir a la generación de CSV
if ($tipo_reporte === 'csv') {
    // Generar reporte CSV
    header('Location: generarReportesCSV.php?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin);
    exit();
} elseif ($tipo_reporte === 'pdf') {
    header('Location: generarReportesPDF.php?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin);
    exit();
} else {
    // Si no se ha seleccionado un tipo de reporte, redirigir o mostrar error
    $_SESSION['mensaje'] = "Por favor, seleccione un tipo de reporte.";
    $_SESSION['mensaje_tipo'] = "alert-warning";
    header("Location: Presupuestos.php");
    exit();
}
?>
