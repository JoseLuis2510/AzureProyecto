<?php

include('ConexionBD.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categoria = $_POST['id_categoria'];
    $categoria = $_POST['categoria'];

    
    $checkQuery = "SELECT COUNT(*) FROM categorias WHERE nombre = ? AND id_categoria != ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $categoria, $id_categoria);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close(); 

    if ($count > 0) {
        
        echo "La categoría '$categoria' ya existe. Por favor, ingrese un nombre diferente.";
    } else {
        
        $query = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $categoria, $id_categoria);

        
        if ($stmt->execute()) {
            header("Location: categorias.php");
            exit();
        } else {
            echo "Error al actualizar categoría: " . $stmt->error;
        }
        $stmt->close(); 
    }

   
    $conn->close();
}
?>
