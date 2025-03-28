<?php

include('ConexionBD.php');


$categorias = $conn->query("SELECT * FROM categorias");


$estilos = $conn->query("SELECT * FROM estilos");

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

ob_start();
?>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="../assets/css/switch.css">

<div class="bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6 text-center">Galería de Proyectos</h1>

    <div class="mb-6">
        <input type="text" id="search-bar" class="w-full px-4 py-2 border rounded-md" placeholder="Buscar proyectos...">
    </div>

    <div class="mb-6 flex gap-4">
        <select id="filter-category" class="w-1/3 px-4 py-2 border rounded-md">
            <option value="">Todas las categorías</option>
            <?php while ($row = $categorias->fetch_assoc()) { ?>
                <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
            <?php } ?>
        </select>

        <select id="filter-style" class="w-1/3 px-4 py-2 border rounded-md">
            <option value="">Todos los estilos</option>
            <?php while ($row = $estilos->fetch_assoc()) { ?>
                <option value="<?= $row['nombre'] ?>"><?= $row['nombre'] ?></option>
            <?php } ?>
        </select>

        <!-- Switch para ver proyectos guardados -->
        <label class="switch">
            <input type="checkbox" id="toggle-guardados">
            <span class="slider round"></span>
        </label>
        <span class="py-2">Ver Guardados</span>
    </div>

    <div id="project-gallery" class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    </div>
</div>

<div id="modalDetalles" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
        <h2 id="modalTitulo" class="text-2xl font-bold mb-4"></h2>
        <p id="modalDescripcion" class="text-gray-700"></p>
        <button onclick="solicitarInformacion()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Solicitar Información</button>
        <button onclick="cerrarModal()" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Cerrar</button>
    </div>
</div>

<script>
let proyectoId = null;

function mostrarDetalles(nombre, detalles, id) {
    document.getElementById('modalTitulo').textContent = nombre;
    document.getElementById('modalDescripcion').textContent = detalles;
    document.getElementById('modalDetalles').classList.remove('hidden');
    proyectoId = id;
}

function cerrarModal() {
    document.getElementById('modalDetalles').classList.add('hidden');
}

function solicitarInformacion() {
    if (proyectoId) {
        window.location.href = `solicitar_info.php?id=${proyectoId}`;
    } else {
        alert("No se ha proporcionado un ID de proyecto válido.");
    }
}

function cargarProyectos() {
    let categoria = $("#filter-category").val();
    let estilo = $("#filter-style").val();
    let search = $("#search-bar").val();
    let verGuardados = $("#toggle-guardados").is(":checked");

    $.ajax({
        url: "Obtener_catalogo.php",
        type: "GET",
        data: { 
            categoria: categoria, 
            estilo: estilo, 
            search: search,
            verGuardados: verGuardados
        },
        success: function (data) {
            $("#project-gallery").html(data);
        },
        error: function () {
            alert("Error al cargar los proyectos.");
        }
    });
}

$(document).ready(function () {
    cargarProyectos();

    // Filtros y búsqueda
    $("#filter-category, #filter-style, #search-bar").on("change keyup", function () {
        cargarProyectos();
    });

    // Switch "Ver Guardados"
    $("#toggle-guardados").on("change", function () {
        cargarProyectos();
    });
});

function guardarProyecto(idProyecto) {
    $.ajax({
        url: "guardar_proyecto.php",
        type: "POST",
        data: { id_proyecto: idProyecto },
        success: function (response) {
            const result = JSON.parse(response);
            if (result.success) {
                mostrarToast(result.message, true);
                actualizarGaleria();
            } else {
                mostrarToast(result.message, false);
            }
        },
        error: function () {
            mostrarToast("Error al conectar con el servidor", false);
        }
    });
}

function eliminarProyecto(idProyecto) {
    $.ajax({
        url: "eliminar_proyecto_guardado.php",
        type: "POST",
        data: { id_proyecto: idProyecto },
        success: function (response) {
            const result = JSON.parse(response);
            if (result.success) {
                mostrarToast(result.message, true);
                actualizarGaleria();
            } else {
                mostrarToast(result.message, false);
            }
        },
        error: function () {
            mostrarToast("Error al conectar con el servidor", false);
        }
    });
}

function actualizarGaleria() {
    cargarProyectos(); // Vuelve a cargar los proyectos con la actualización de botones
}

function mostrarToast(mensaje, esExito) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-md text-white ${esExito ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

</script>

<?php

$contenido = ob_get_clean();


$_SESSION['contenido'] = $contenido;

include('layout.php');
?>
