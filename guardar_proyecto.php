<?php
session_start();
include('ConexionBD.php');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

if (!isset($_POST['id_proyecto'])) {
    echo json_encode(['success' => false, 'message' => 'ID de proyecto no proporcionado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_proyecto = $_POST['id_proyecto'];

// Verificar si el proyecto ya está guardado por el usuario
$query = "SELECT * FROM usuarios_proyectos WHERE id_usuario = ? AND id_proyecto = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_usuario, $id_proyecto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El proyecto ya está guardado']);
    exit();
}

// Insertar el proyecto en la tabla usuarios_proyectos
$query = "INSERT INTO usuarios_proyectos (id_usuario, id_proyecto) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $id_usuario, $id_proyecto);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Proyecto guardado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el proyecto']);
}

$stmt->close();
$conn->close();
?>