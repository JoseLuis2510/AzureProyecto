<?php
session_start();
include('ConexionBD.php');

if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : "";
$estilo = isset($_GET['estilo']) ? $_GET['estilo'] : "";
$search = isset($_GET['search']) ? $_GET['search'] : "";
$verGuardados = isset($_GET['verGuardados']) ? filter_var($_GET['verGuardados'], FILTER_VALIDATE_BOOLEAN) : false;

$query = "SELECT p.*, c.nombre AS categoria, e.nombre AS estilo,
                (SELECT COUNT(*) FROM usuarios_proyectos up WHERE up.id_usuario = $id_usuario AND up.id_proyecto = p.id_proyecto) AS guardado
          FROM proyectos p
          JOIN categorias c ON p.categoria_id = c.id_categoria
          JOIN estilos e ON p.estilo_id = e.id_estilo";

if ($verGuardados) {
    // Filtrar solo los proyectos guardados por el usuario
    $query .= " JOIN usuarios_proyectos up ON p.id_proyecto = up.id_proyecto
                WHERE up.id_usuario = $id_usuario";
} else {
    // Filtros normales
    $query .= " WHERE 1=1";
}


if ($categoria != "") {
    $query .= " AND c.nombre = '" . $conn->real_escape_string($categoria) . "'";
}


if ($estilo != "") {
    $query .= " AND e.nombre = '" . $conn->real_escape_string($estilo) . "'";
}

if ($search != "") {
    $query .= " AND p.nombre LIKE '%" . $conn->real_escape_string($search) . "%'"; // Búsqueda por nombre
}

$resultado = $conn->query($query);

if ($resultado === false) {
    // Manejar errores de la consulta
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    exit();
}

if ($resultado->num_rows > 0) {
    while ($proyecto = $resultado->fetch_assoc()) {
        $estaGuardado = $proyecto['guardado'] > 0;

        echo '<div class="bg-white shadow-md rounded-lg overflow-hidden">';
        echo '<img src="' . $proyecto['imagen'] . '" class="w-full h-48 object-cover">';
        echo '<div class="p-4">';
        echo '<h3 class="font-bold text-xl mb-2">' . $proyecto['nombre'] . '</h3>';
        echo '<p class="text-gray-600">Categoría: ' . $proyecto['categoria'] . ' | Estilo: ' . $proyecto['estilo'] . '</p>';
    
        
        echo '<div class="flex gap-4 mt-4">';
        
        // Botón "Ver Detalles"
        echo '<button class="hover:underline flex items-center" 
            onclick="mostrarDetalles(\'' . htmlspecialchars($proyecto['nombre']) . '\', \'' . htmlspecialchars($proyecto['detalles']) . '\', ' . $proyecto['id_proyecto'] . ')">
            <i class="ti ti-eye mr-2"></i>Ver Detalles
        </button>';

        // Si el proyecto NO está guardado, mostrar botón "Guardar"
        if (!$estaGuardado) {
            echo '<button class="hover:underline flex items-center" onclick="guardarProyecto(' . $proyecto['id_proyecto'] . ')">
                <i class="ti ti-bookmark mr-2"></i>Guardar
            </button>';
        } else { 
            // Si el proyecto está guardado, mostrar botón "Eliminar"
            echo '<button class="bg-red-500 hover:bg-red-600 flex items-center" onclick="eliminarProyecto(' . $proyecto['id_proyecto'] . ')">
                <i class="ti ti-trash mr-2"></i>No Guardar
            </button>';
        }
        
        echo '</div>'; // Cierre del contenedor de botones
        
        echo '</div></div>';
    }
    
} else {
    echo "<p class='text-gray-500'>No hay proyectos disponibles.</p>";
}


$conn->close();
?>








