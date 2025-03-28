<?php

include('ConexionBD.php');




if (isset($_GET['id'])) {
    $id_proyecto = $_GET['id'];


    $query = "DELETE FROM proyectos WHERE id_proyecto = $id_proyecto";

    if ($conn->query($query) === TRUE) {
        header("Location: catalogo.php");
                exit();
    } else {
        echo "Error al eliminar el proyecto: " . $conn->error;
    }
} else {
    echo "No se ha proporcionado el ID del proyecto.";
}

$conn->close();
?>
