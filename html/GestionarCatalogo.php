<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

// Inicia la captura del contenido
ob_start();
?>


    <div class="container my-4">
        <h3>Agregar Proyecto</h3>
        <form action="agregar_proyecto.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Proyecto:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="detalles">Detalles del Proyecto:</label>
                <textarea id="detalles" name="detalles" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria_id" class="form-control" required>
                    <?php
                    // Conectar a la base de datos y obtener las categorías
                    include('ConexionBD.php');
                    $query = "SELECT * FROM categorias";
                    $result = $conn->query($query);

                    // Mostrar las categorías existentes
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_categoria'] . "'>" . $row['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="estilo">Estilo:</label>
                <select id="estilo" name="estilo_id" class="form-control" required>
                    <?php
                    // Obtener los estilos disponibles
                    $query = "SELECT * FROM estilos";
                    $result = $conn->query($query);

                    // Mostrar los estilos existentes
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id_estilo'] . "'>" . $row['nombre'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen del Proyecto:</label>
                <input type="file" id="imagen" name="imagen" max-width: 100px;
            border-radius: 5px; class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 200px;">Agregar Proyecto</button>
        </form>
    </div>

    <!-- Mostrar proyectos -->
    <div class="container mt-5">
        <h3>Proyectos Registrados</h3>

        <?php
        // Conectar a la base de datos
        include('ConexionBD.php');

        // Consulta para obtener todos los proyectos
        $query = "SELECT p.id_proyecto, p.nombre, p.detalles, c.nombre AS categoria, e.nombre AS estilo, p.imagen
                FROM proyectos p
                JOIN categorias c ON p.categoria_id = c.id_categoria
                JOIN estilos e ON p.estilo_id = e.id_estilo";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table'>
                        <tr>
                            <th>Nombre</th>
                            <th>Detalles</th>
                            <th>Categoría</th>
                            <th>Estilo</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['nombre'] . "</td>
                        <td>" . $row['detalles'] . "</td>
                        <td>" . $row['categoria'] . "</td>
                        <td>" . $row['estilo'] . "</td>
                        <td><img src='" . $row['imagen'] . "' alt='" . $row['nombre'] . "' width='100' height='100'></td>

                        <td class='action-buttons'>
                            
                            <a href='eliminar_proyecto.php?id=" . $row['id_proyecto'] . "'><button class='btn btn-danger btn-sm'>Eliminar</button></a>
                        </td>
                    </tr>";
            }
            echo "</table>
                </div>";
        } else {
            echo "<p>No hay proyectos registrados.</p>";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </div>




<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
// Incluye el layout
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

        input[type="file"] {
            padding: 6px;
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

        /* Estilo para hacer la tabla responsive */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
