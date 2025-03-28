<?php

include('ConexionBD.php');

if (isset($_GET['id'])) {
    $id_categoria = $_GET['id'];


    $query = "DELETE FROM categorias WHERE id_categoria = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_categoria);


    if ($stmt->execute()) {
        header("Location: categorias.php");
                exit();
    } else {
        echo "Error al eliminar categoría: " . $stmt->error;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
