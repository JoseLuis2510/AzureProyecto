<?php
session_start();
include('ConexionBD.php');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_proyecto = isset($_POST['id_proyecto']) ? intval($_POST['id_proyecto']) : 0;

if ($id_proyecto > 0) {
    $stmt = $conn->prepare("DELETE FROM usuarios_proyectos WHERE id_usuario = ? AND id_proyecto = ?");
    $stmt->bind_param("ii", $id_usuario, $id_proyecto);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Proyecto eliminado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el proyecto']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID de proyecto no válido']);
}
?>
