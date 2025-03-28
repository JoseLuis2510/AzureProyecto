<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert alert-info" role="alert">' . $_SESSION['mensaje'] . '</div>';
    unset($_SESSION['mensaje']); 
}
// Inicia la captura del contenido
ob_start();
?>
    
    <div class="container my-4">
        <h3>Agregar Categoría</h3>
        
        <form action="agregar_categoria.php" method="POST">
            <div class="form-group">
                <label for="categoria">Nombre de la Categoría:</label>
                <input type="text" id="categoria" name="categoria" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" >Agregar Categoría</button>
        </form>
    </div>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $categoria = $_POST['categoria'];

    include('ConexionBD.php');

    $query = "SELECT * FROM categorias WHERE nombre = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $categoria); 
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {

        echo "<div class='alert alert-warning'>La categoría '$categoria' ya existe.</div>";
    } else {
        $insert_query = "INSERT INTO categorias (nombre) VALUES (?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("s", $categoria); 
        $insert_stmt->execute();

        if ($insert_stmt->affected_rows > 0) {
            $mensaje = "<p class='success'>Categoria agregada correctamente.</p>";
        } else {
            $mensaje = "<p class='error'>Error al agregar la.</p>";
        }

        $insert_stmt->close();
    }

    $stmt->close();

    $conn->close();
}
?>


<div class="container mt-5">
    <h3>Categorías Registradas</h3>

    <?php

    include('ConexionBD.php');


    $query = "SELECT * FROM categorias";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<div class='table-responsive'>
                <table class='table'>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['nombre'] . "</td>
                    <td class='action-buttons'>
                        <a href='eliminar_categoria.php?id=" . $row['id_categoria'] . "'><button class='btn btn-danger btn-sm'>Eliminar</button></a>
                    </td>
                </tr>";
        }
        echo "</table>
            </div>";
    } else {
        echo "<p>No hay categorías registradas.</p>";
    }

    $conn->close();
    ?>
</div>

<?php

$contenido = ob_get_clean();

include('layout.php');
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 800px;
        margin-top: 30px;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h3 {
        color: #343a40;
        text-align: center;
    }

    label {
        font-weight: bold;
        color: #495057;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    table {
        margin-top: 30px;
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    .action-buttons a {
        margin-right: 10px;
    }


