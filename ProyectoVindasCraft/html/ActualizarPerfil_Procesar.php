<?php
session_start();
include('ConexionBD.php'); 

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['mensaje'] = "Por favor, inicie sesión para acceder a esta página.";
    header("Location: InicioSesion.php");
    exit();
}

// Obtener los datos del formulario
$identificacion = $_POST['id'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];

// Verificar que los campos no estén vacíos
if (empty($identificacion) || empty($nombre) || empty($correo)) {
    $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
    header("Location: ActualizarPerfil.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario']; 

// Consulta para actualizar los datos del usuario
$sql = "UPDATE usuarios SET identificacion = ?, nombre = ?, email = ? WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssi', $identificacion, $nombre, $correo, $id_usuario); 
$_SESSION['nombre'] = $nombre;

if ($stmt->execute()) {
    $_SESSION['mensaje'] = "Datos actualizados correctamente.";
    header("Location: index.php"); // Redirigir a la página principal
} else {
    $_SESSION['mensaje'] = "Error al actualizar los datos.";
    header("Location: ActualizarPerfil.php");
}

$stmt->close();
$conn->close();
?>
