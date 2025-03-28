
<?php
include('ConexionBD.php');
session_start();

// Variables para almacenar las fechas
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Construir la consulta SQL
$sql = "SELECT * FROM auditoria WHERE 1";

// Agregar filtro por fecha
if ($fecha_inicio) {
    $sql .= " AND fecha >= '$fecha_inicio 00:00:00'";
}
if ($fecha_fin) {
    $sql .= " AND fecha <= '$fecha_fin 23:59:59'";
}

// Ordenar por fecha
$sql .= " ORDER BY fecha DESC";

$result = $conn->query($sql);
$auditoria = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $auditoria[] = $row;
    }
}
ob_start();
?>


    <div class="container">
        <h3 class="mt-5">Informe de rotación de inventario</h3>

        
        <form method="GET" action="InformeRotacion.php" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        
        
        <div style="overflow-x: auto;">
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Id Material</th>
                        <th>Acción</th>
                        <th>Nombre Material Anterior</th>
                        <th>Precio Anterior</th>
                        <th>Stock Anterior</th>
                        <th>Proveedor Anterior</th>
                        <th>Nombre Material Nuevo</th>
                        <th>Precio Nuevo</th>
                        <th>Stock Nuevo</th>
                        <th>Proveedor Nuevo</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($auditoria) > 0) {
                        foreach ($auditoria as $registro) {
                            echo "<tr>
                                    <td>{$registro['id_material']}</td>
                                    <td>{$registro['accion']}</td>
                                    <td>{$registro['nombre_material_anterior']}</td>
                                    <td>{$registro['precio_anterior']}</td>
                                    <td>{$registro['stock_anterior']}</td>
                                    <td>{$registro['proveedor_anterior']}</td>
                                    <td>{$registro['nombre_material_nuevo']}</td>
                                    <td>{$registro['precio_nuevo']}</td>
                                    <td>{$registro['stock_nuevo']}</td>
                                    <td>{$registro['proveedor_nuevo']}</td>
                                    <td>{$registro['fecha']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No hay registros para mostrar</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <form method="GET" action="InformeRotacion_procesar.php">
    <div class="form-row">
        <div class="col-md-6">
            <label for="fecha_inicio">Fecha de inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
        </div>
        <div class="col-md-6">
            <label for="fecha_fin">Fecha de fin</label>
            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-success" style="width: 200px; background-color: #2952ff; border-color">Descargar Informe en PDF</button>
</form>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();

// Incluye el layout
include('layout.php');
?>
