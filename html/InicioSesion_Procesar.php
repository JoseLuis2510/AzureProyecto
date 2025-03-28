<?php
// Iniciar sesión
session_start();

// Conexión a la base de datos
include('ConexionBD.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificacion = trim($_POST['identificacion']);
    $password = trim($_POST['password']);

    // Validar que los campos no estén vacíos
    if (empty($identificacion) || empty($password)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        header("Location: InicioSesion.php");
        exit();
    }

    // Consulta para verificar credenciales
    $sql = "SELECT * FROM usuarios WHERE identificacion = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $identificacion);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar si el usuario tiene una contraseña hash
        if (password_verify($password, $user['password'])) {
            // Iniciar sesión y redirigir
            $_SESSION['id_usuario'] = $user['id_usuario'];  // Almacenar el ID del usuario en la sesión
            $_SESSION['id_rol'] = $user['id_rol'];  // Almacenar el ID del rol en la sesión
            $_SESSION['nombre'] = $user['nombre'];  // Almacenar el nombre del rol en la sesión

            // Redirigir al perfil
            header('Location: Index.php'); 
            exit();
        } else {
            $_SESSION['mensaje'] = "Contraseña incorrecta";
            header("Location: InicioSesion.php");
            exit();
        }

        
    } else {
        $_SESSION['mensaje'] = "Identificacion no encontrada";
            header("Location: InicioSesion.php");
            exit();
    }
}
?>
