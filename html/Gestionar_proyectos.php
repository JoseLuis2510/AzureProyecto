<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Proyectos - Mueblería</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.23/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
   
        <div class="tabs flex mb-6">
            <div class="tab flex-1 text-center py-3 cursor-pointer bg-white shadow-md border-b-2 border-blue-500 text-blue-600" id="cliente-tab">
                Cliente
            </div>
            <a href="Gestionar_proyectos.php">
                <div class="tab flex-1 text-center py-3 cursor-pointer bg-white shadow-md" id="admin-tab">
                    Administrador
                </div>
            </a>
        </div>

        <div class="section" id="cliente-section">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6 text-center">Galería de Proyectos</h1>

                <div class="mb-6">
                    <input type="text" id="search-input" class="w-full px-4 py-2 border rounded-md mb-4" placeholder="Buscar proyecto...">
                    <div class="flex gap-4">
                        <select id="filter-category" class="w-1/3 px-4 py-2 border rounded-md">
                            <option value="">Categoría</option>
                            <option value="Cocinas">Cocinas</option>
                            <option value="Dormitorios">Dormitorios</option>
                            <option value="Oficinas">Oficinas</option>
                        </select>
                        <select id="filter-style" class="w-1/3 px-4 py-2 border rounded-md">
                            <option value="">Estilo</option>
                            <option value="Moderno">Moderno</option>
                            <option value="Contemporáneo">Contemporáneo</option>
                            <option value="Rústico">Rústico</option>
                        </select>
                        <input type="date" id="filter-date" class="w-1/3 px-4 py-2 border rounded-md" placeholder="Fecha">
                    </div>
                </div>

                <div id="project-gallery" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                        include('ConexionBD.php'); 

                        // Consulta para obtener los proyectos
                        $query = "SELECT * FROM proyectos";
                        $result = mysqli_query($conn, $query);

                        // Mostrar los proyectos
                        while ($project = mysqli_fetch_assoc($result)) {
                            echo '<div class="bg-white shadow-md rounded-lg overflow-hidden project-card" data-nombre="' . $project['nombre'] . '" data-categoria="' . $project['categoria'] . '" data-estilo="' . $project['estilo'] . '" data-detalles="' . $project['detalles'] . '">';
                            echo '<img src="' . $project['imagen'] . '" alt="Proyecto" class="w-full h-48 object-cover">';
                            echo '<div class="p-4">';
                            echo '<h3 class="font-bold text-xl mb-2">' . $project['nombre'] . '</h3>';
                            echo '<div class="flex justify-between items-center">';
                            echo '<button class="ver-detalles text-blue-500 hover:underline flex items-center" data-id="' . $project['id'] . '"><i class="ti ti-eye mr-2"></i>Ver Detalles</button>';
                            echo '<button class="guardar-proyecto text-green-500 hover:underline flex items-center"><i class="ti ti-bookmark mr-2"></i>Guardar</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white w-11/12 md:w-3/4 lg:w-1/2 rounded-lg shadow-xl p-6">
            <h2 id="modal-title" class="text-2xl font-bold mb-4 text-center"></h2>
            <div id="modal-content"></div>
            <div class="flex justify-center mt-6">
                <button id="back-button" class="bg-gray-500 text-white px-4 py-2 rounded">Volver</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const clienteTab = document.getElementById('cliente-tab');
            const adminTab = document.getElementById('admin-tab');
            const clienteSection = document.getElementById('cliente-section');
            const adminSection = document.getElementById('admin-section');
            const modal = document.getElementById('modal');
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');

            clienteTab.addEventListener('click', () => {
                clienteTab.classList.add('border-blue-500', 'text-blue-600');
                adminTab.classList.remove('border-blue-500', 'text-blue-600');
                clienteSection.classList.remove('hidden');
                adminSection.classList.add('hidden');
            });

            adminTab.addEventListener('click', () => {
                adminTab.classList.add('border-blue-500', 'text-blue-600');
                clienteTab.classList.remove('border-blue-500', 'text-blue-600');
                adminSection.classList.remove('hidden');
                clienteSection.classList.add('hidden');
            });

            document.querySelectorAll('.ver-detalles').forEach(button => {
                button.addEventListener('click', (e) => {
                    const projectCard = e.target.closest('.project-card');
                    const projectName = projectCard.dataset.nombre;
                    const projectDetails = projectCard.dataset.detalles;

                    modalTitle.textContent = `Detalles de: ${projectName}`;
                    modalContent.innerHTML = `<p>${projectDetails}</p>`;
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html>
