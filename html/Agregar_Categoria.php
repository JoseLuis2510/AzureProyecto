<?php

include('ConexionBD.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $categoria = $_POST['categoria'];

    
    $query = "SELECT * FROM categorias WHERE nombre = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $categoria); 
    $stmt->execute();
    $stmt->store_result();

  
    if ($stmt->num_rows > 0) {
        echo "La categoría ya existe.";
    } else {
        
        $insert_query = "INSERT INTO categorias (nombre) VALUES (?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("s", $categoria); 
        $insert_stmt->execute();

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Datos actualizados correctamente.";
            header("Location: Categorias.php"); 
        } else {
            $_SESSION['mensaje'] = "Error al actualizar los datos.";
            header("Location: Categorias.php");
        }

       
        $insert_stmt->close();
    }

    
    $stmt->close();
}


$conn->close();
?>
<style>
    .message {
            text-align: center;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }
</style>
