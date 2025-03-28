<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
include('ConexionBD.php'); 

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>
            alert('Por favor, inicie sesión para ver su perfil.');
            window.location.href = 'InicioSesion.php';
          </script>";
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['id_usuario'];

// Inicializar las variables
$nombre = $email = '';

// Consulta para obtener la información del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); 
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encuentra el usuario
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nombre = $user['nombre']; 
    $email = $user['email']; 
} else {
    echo "<script>
            alert('No se pudo cargar la información del perfil.');
            window.location.href = 'InicioSesion.php';
          </script>";
    exit();
}
?>
