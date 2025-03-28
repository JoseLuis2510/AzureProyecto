<?php
header('Content-Type: application/json');

session_start();
include('ConexionBD.php');


if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
    echo json_encode(['error' => 'Acceso denegado.']);
    exit();
}


if ($conn->connect_error) {
    echo json_encode(['error' => 'Error en la conexión a la base de datos: ' . $conn->connect_error]);
    exit();
}


$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;

if (!$start || !$end) {
    echo json_encode(['error' => 'Faltan parámetros de fecha.', 'start' => $start, 'end' => $end]);
    exit();
}


$sql = "SELECT id, cliente_nombre, cliente_email, cliente_telefono, detalles_producto, fecha_estimada_entrega, prioridad, estado 
        FROM pedidos 
        WHERE fecha_estimada_entrega BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
    exit();
}


$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];

while ($row = $result->fetch_assoc()) {
    $eventos[] = [
        'id' => $row['id'],
        'title' => "Pedido de " . $row['cliente_nombre'], 
        'start' => $row['fecha_estimada_entrega'],
        'prioridad' => $row['prioridad'],
        'estado' => $row['estado'],
        'cliente_nombre' => $row['cliente_nombre'],
        'cliente_email' => $row['cliente_email'],
        'cliente_telefono' => $row['cliente_telefono'],
        'detalles_producto' => $row['detalles_producto']
    ];
}

// Cerrar conexión
$stmt->close();
$conn->close();


echo json_encode(empty($eventos) ? ['message' => 'No hay pedidos en este rango de fechas.'] : $eventos);
?>
