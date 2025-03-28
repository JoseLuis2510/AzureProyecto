<?php
session_start();
include('ConexionBD.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario']; 
    $contrasennaAnterior = $_POST['contrasennaAnterior'];
    $nuevaContrasenna = $_POST['nuevaContrasenna'];
    $confirmarContrasenna = $_POST['confirmarContrasenna'];

    // Validar campos vacíos
    if (empty($contrasennaAnterior) || empty($nuevaContrasenna) || empty($confirmarContrasenna)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        header("Location: CambiarContraseña.php");
        exit();
    }

    // Validar que la nueva contraseña coincida con la confirmación
    if ($nuevaContrasenna !== $confirmarContrasenna) {
        $_SESSION['mensaje'] = "La nueva contraseña y la confirmación no coinciden.";
        header("Location: CambiarContraseña.php");
        exit();
    }

    // Obtener la contraseña actual del usuario desde la base de datos
    $query = "SELECT password FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        $stmt->close();

        // Verificar si la contraseña ingresada coincide con la almacenada
        if (!password_verify($contrasennaAnterior, $hashedPassword)) {
            $_SESSION['mensaje'] = "La contraseña anterior es incorrecta.";
            header("Location: CambiarContraseña.php");
            exit();
        }

        // Actualizar la contraseña en la base de datos
        $nuevoHash = password_hash($nuevaContrasenna, PASSWORD_BCRYPT);
        $updateQuery = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if ($updateStmt) {
            $updateStmt->bind_param("si", $nuevoHash, $idUsuario);

            if ($updateStmt->execute()) {
                $_SESSION['mensaje'] = "Contraseña actualizada con éxito.";
                header("Location: CambiarContraseña.php"); 
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al actualizar la contraseña. Inténtelo nuevamente.";
                header("Location: CambiarContraseña.php");
                exit();
            }

            $updateStmt->close();
        } else {
            $_SESSION['mensaje'] = "Error en la consulta de actualización.";
            header("Location: CambiarContraseña.php");
            exit();
        }
    } else {
        $_SESSION['mensaje'] = "Error al obtener los datos del usuario.";
        header("Location: CambiarContraseña.php");
        exit();
    }

    $conn->close();
}
?>