<?php
session_start();
include('ConexionBD.php');

// Consulta para obtener todos los materiales
$sql = "SELECT * FROM inventario ORDER BY stock DESC";
$result = $conn->query($sql);
$materiales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materiales[] = $row;
    }
}
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php
// Inicia la captura del contenido
ob_start();
?>
<div class="container">
    <h3 class="mt-5">Catálogo de Materiales</h3>
    <button class="btn mb-3" data-toggle="modal" data-target="#modalAgregarMaterial" style="width: 200px; background-color: #2952ff; border-color: #2952ff; color: white;">Agregar Material</button>


    <ul class="list-group" id="listaCatalogo">
    <?php
    foreach ($materiales as $material) {
        $alertaStock = ($material['stock'] < 10) ? "<span class='badge badge-danger'>¡Stock bajo!</span>" : "";
        echo "
        <li class='list-group-item'>
            <h5>{$material['nombre_material']}</h5>
            <div class='d-flex justify-content-between align-items-center'>
                <span>Precio: ₡{$material['precio']} | Stock: {$material['stock']} | Proveedor: {$material['proveedor']} {$alertaStock}</span>
                <div>
                   <button class='btn btn-sm' onclick='editarMaterial({$material['id_material']}, \"{$material['nombre_material']}\", {$material['precio']}, {$material['stock']}, \"{$material['proveedor']}\")' data-toggle='modal' data-target='#modalEditarMaterial' style='background-color: #2952ff; border-color: #2952ff; color: white; margin-bottom: 10px;'>Editar</button>


                    <a href='EliminarInventario_procesar.php?id={$material['id_material']}' class='btn btn-danger btn-sm'>Eliminar</a>
                </div>
            </div>
        </li>";
    }
    ?>
    </ul>
</div>

<!-- Modal para agregar material -->
<div class="modal fade" id="modalAgregarMaterial" tabindex="-1" aria-labelledby="modalAgregarMaterialLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarMaterialLabel">Agregar Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="AgregarInventario_procesar.php">
          <div class="form-group">
            <label for="nombreMaterial">Nombre del Material</label>
            <input type="text" class="form-control" name="nombreMaterial" id="nombreMaterial" required>
          </div>
          <div class="form-group">
            <label for="precioMaterial">Precio</label>
            <input type="number" class="form-control" name="precioMaterial" id="precioMaterial" required>
          </div>
          <div class="form-group">
            <label for="stockMaterial">Stock</label>
            <input type="number" class="form-control" name="stockMaterial" id="stockMaterial" required>
          </div>
          <div class="form-group">
            <label for="proveedorMaterial">Proveedor</label>
            <input type="text" class="form-control" name="proveedorMaterial" id="proveedorMaterial" required>
          </div>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para editar material -->
<div class="modal fade" id="modalEditarMaterial" tabindex="-1" aria-labelledby="modalEditarMaterialLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarMaterialLabel">Editar Material</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="EditarInventario_procesar.php">
          <input type="hidden" name="idMaterial" id="idMaterial">

          <div class="form-group">
            <label for="nombreMaterialEditar">Nombre del Material</label>
            <input type="text" class="form-control" name="nombreMaterial" id="nombreMaterialEditar" required>
          </div>
          <div class="form-group">
            <label for="precioMaterialEditar">Precio</label>
            <input type="number" class="form-control" name="precioMaterial" id="precioMaterialEditar" required>
          </div>
          <div class="form-group">
            <label for="stockMaterialEditar">Stock</label>
            <input type="number" class="form-control" name="stockMaterial" id="stockMaterialEditar" required>
          </div>
          <div class="form-group">
            <label for="proveedorMaterialEditar">Proveedor</label>
            <input type="text" class="form-control" name="proveedorMaterial" id="proveedorMaterialEditar" required>
          </div>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
// Función para cargar los datos en el modal de edición
function editarMaterial(id, nombre, precio, stock, proveedor) {
    document.getElementById('idMaterial').value = id;
    document.getElementById('nombreMaterialEditar').value = nombre;
    document.getElementById('precioMaterialEditar').value = precio;
    document.getElementById('stockMaterialEditar').value = stock;
    document.getElementById('proveedorMaterialEditar').value = proveedor;
}
</script>

<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
 
// Incluye el layout
include('layout.php');
