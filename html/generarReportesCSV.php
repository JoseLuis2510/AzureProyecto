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

// Request SQL
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

// Generar nombre del archivo CSV
$nombreArchivo = "Reporte_Presupuestos_" . date('Ymd_His') . ".csv";

// Generar archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $nombreArchivo); // Nombre dinámico del archivo
$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Usuario', 'Detalles', 'Nombre del proyecto', 'Monto estimado', 'Estado', 'Fecha de creacion']);

// Sanitizar y escribir datos en CSV
while ($row = $result->fetch_assoc()) {
    // Asegurar que los datos no contengan saltos de línea que rompan el CSV
    array_walk($row, function(&$val) {
        $val = str_replace(["\r", "\n", '"', ','], ' ', $val);
    });
    fputcsv($output, $row);
}

fclose($output);
exit();
?>