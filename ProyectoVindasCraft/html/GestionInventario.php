<?php

// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre'])) {
    header("Location: InicioSesion.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$nombre = $_SESSION['nombre']; 

// Conexión a la base de datos
include('ConexionBD.php');

// Inicia la captura del contenido
ob_start();
?>
<div class="container my-4">
    
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarMaterial">
      Agregar Material
    </button>

   
    <div class="modal fade" id="modalAgregarMaterial" tabindex="-1" aria-labelledby="modalAgregarMaterialLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAgregarMaterialLabel">Agregar Material al Catálogo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="form-agregar">
              <div class="mb-3">
                <label for="nombreMaterial" class="form-label">Nombre del Material</label>
                <input type="text" class="form-control" id="nombreMaterial" placeholder="Ej: Roble" required>
              </div>
              <div class="mb-3">
                <label for="precioMaterial" class="form-label">Precio</label>
                <input type="number" class="form-control" id="precioMaterial" placeholder="Ej: 50000" required>
              </div>
              <div class="mb-3">
                <label for="stockMaterial" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stockMaterial" placeholder="Ej: 10" required>
              </div>
              <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    
    <section id="catalogo-materiales" class="mb-5">
      <h3>Catálogo de Materiales</h3>
      <button class="btn btn-secondary mb-3" id="ordenarAsc">Ordenar por Stock: Menor a Mayor</button>
      <ul class="list-group" id="listaCatalogo"></ul>

      
      <div id="materialMinStock" class="mt-4">
        <h4>Material con menor stock</h4>
        <p id="detalleMinStock" class="alert alert-info">Cargando...</p>
      </div>
    </section>
  </div>

  


  
    
   
<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
 // Incluye el layout
include('Layout.php');



