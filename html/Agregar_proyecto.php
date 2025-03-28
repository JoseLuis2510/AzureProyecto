<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    include('ConexionBD.php');

    
   

   
    $nombre = $_POST['nombre'];
    $detalles = $_POST['detalles'];
    $categoria_id = $_POST['categoria_id'];
    $estilo_id = $_POST['estilo_id'];
    $id_usuario = 1; 

    
    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];
    $ruta_imagen = '../assets/images/' . $imagen;
    move_uploaded_file($imagen_tmp, $ruta_imagen);

    
    $sql = "INSERT INTO proyectos (nombre, detalles, categoria_id, imagen, estilo_id, id_usuario)
            VALUES ('$nombre', '$detalles', '$categoria_id', '$ruta_imagen', '$estilo_id', '$id_usuario')";

    if ($conn->query($sql) === TRUE) {
        header("Location: GestionarCatalogo.php");
                exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    
    $conn->close();
}
?>



