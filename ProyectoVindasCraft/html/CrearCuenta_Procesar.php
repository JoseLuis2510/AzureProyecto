<?php
// Iniciar sesión
session_start();

include('ConexionBD.php');

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y limpiar los datos de entrada
    $identificacion = $conn->real_escape_string(trim($_POST['identificacion']));
    $nombre = $conn->real_escape_string(trim($_POST['nombre']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash de la contraseña

    // Validar que los campos no estén vacíos
    if (empty($identificacion) || empty($nombre) || empty($email) || empty($_POST['password'])) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios y no pueden contener solo espacios.";
        header("Location: CrearCuenta.php");
        exit();
    }

    // Verificar si la identificación o el correo ya están registrados
    $checkUser = "SELECT id_usuario FROM usuarios WHERE identificacion = '$identificacion' OR email = '$email'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        // Si existe un usuario con esa identificación o correo
        $_SESSION['mensaje'] = "Información inválida: ya existe un usuario con esta información.";
        header("Location: CrearCuenta.php");
        exit();
    } else {
        // Insertar el usuario con el rol de cliente (id_rol = 1)
        $insertUser = "INSERT INTO usuarios (identificacion, nombre, email, password, id_rol)
                       VALUES ('$identificacion', '$nombre', '$email', '$password', 1)";
        if ($conn->query($insertUser)) {
            // Registro exitoso, redireccionar a la página de inicio de sesión
            echo "<script>
                    window.location.href = 'InicioSesion.php';
                  </script>";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    }
}

$conn->close();
?>
