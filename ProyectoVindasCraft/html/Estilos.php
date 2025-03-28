<?php
// Conectar a la base de datos
include('ConexionBD.php');



// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtén los datos del formulario
    $nombre_estilo = trim($_POST['nombre_estilo']);

    if (!empty($nombre_estilo)) {
        // Verificar si el estilo ya existe
        $query = "SELECT * FROM estilos WHERE nombre = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nombre_estilo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensaje = "<p class='error'>El estilo ya existe.</p>";
        } else {
            // Insertar el nuevo estilo
            $insert_query = "INSERT INTO estilos (nombre) VALUES (?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("s", $nombre_estilo);
            $insert_stmt->execute();

            if ($insert_stmt->affected_rows > 0) {
                $mensaje = "<p class='success'>Estilo agregado correctamente.</p>";
            } else {
                $mensaje = "<p class='error'>Error al agregar el estilo.</p>";
            }

            $insert_stmt->close();
        }

        $stmt->close();
    } else {
        $mensaje = "<p class='error'>El nombre del estilo no puede estar vacío.</p>";
    }
}

// Eliminar estilo
if (isset($_GET["eliminar"])) {
    $id_estilo = intval($_GET["eliminar"]);
    $delete_query = "DELETE FROM estilos WHERE id_estilo = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id_estilo);

    if ($stmt->execute()) {
        $mensaje = "<p class='success'>Estilo eliminado correctamente.</p>";
    } else {
        $mensaje = "<p class='error'>Error al eliminar el estilo.</p>";
    }

    $stmt->close();
}

// Obtener estilos existentes
$query = "SELECT * FROM estilos ORDER BY nombre ASC";
$result = $conn->query($query);
?>
<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

// Inicia la captura del contenido
ob_start();
?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

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

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Gestión de Estilos</h2>
    


    <!-- Formulario para agregar estilo -->
    <form action="" method="POST">
        <label for="nombre_estilo">Nombre del Estilo:</label>
        <input type="text" id="nombre_estilo" name="nombre_estilo" required>
        <div class="text-center">
            <input type="submit" value="Agregar Estilo" style="width: 200px; margin-bottom: 20px;">

        </div>  
    </form>

    <!-- Lista de estilos -->
    <h3>Lista de Estilos</h3>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['nombre']) . "</td>
                    <td>
                        <a href='?eliminar=" . $row['id_estilo'] . "'><button class='btn-delete'>Eliminar</button></a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay estilos registrados.</p>";
    }

    // Cerrar conexión
    $conn->close();
    ?>
</div>
<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
// Incluye el layout
include('layout.php');
?>
