<?php
include('ConexionBD.php');
session_start();

// Obtener la fecha de inicio y fin de la semana actual
$fecha_inicio_semana = date('Y-m-d', strtotime('monday this week'));
$fecha_fin_semana = date('Y-m-d', strtotime('sunday this week'));

$prioridad = isset($_GET['prioridad']) ? $_GET['prioridad'] : '';

$query = "SELECT * FROM pedidos 
          WHERE fecha_estimada_entrega BETWEEN '$fecha_inicio_semana' AND '$fecha_fin_semana'";

if (!empty($prioridad)) {
    $query .= " AND prioridad = '$prioridad'";
}

$query .= " ORDER BY prioridad DESC, fecha_estimada_entrega ASC";

$result = $conn->query($query);

$pedidos = [];
if ($result) {
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);
}

ob_start();
?>

<div class="container mt-5">
    <h1 class="mb-4">Entregas Planificadas para la Semana</h1>
    <a href="Calendario.php" class="btn btn-primary mb-4" style="width: 200px;">Regresar</a> 

    <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Estimada de Entrega</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($pedidos) > 0): ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <?php
                        // Verificar si hay conflictos de fechas
                        $sql_conflicto = "SELECT COUNT(*) as count FROM pedidos WHERE fecha_estimada_entrega = '{$pedido['fecha_estimada_entrega']}' AND id != {$pedido['id']}";
                        $result_conflicto = $conn->query($sql_conflicto);
                        $row_conflicto = $result_conflicto->fetch_assoc();
                        $conflicto = $row_conflicto['count'] > 0;
                        ?>
                        <tr style="<?php echo $conflicto ? 'background-color: #ffcccc;' : ''; ?>">
                        <tr style="<?php echo $conflicto ? 'background-color: #ffcccc;' : ''; ?>">
                            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_creacion']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_estimada_entrega']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($pedido['prioridad'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></td>
                            <td>
                                <a href="EditarPedido_procesar.php?id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay entregas planificadas para esta semana.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
     <!-- Formulario de filtro por prioridad -->
     <form method="GET" action="">
        <label for="prioridad">Filtrar por prioridad:</label>
        <select name="prioridad" id="prioridad">
            <option value="" <?php echo (!isset($_GET['prioridad']) || $_GET['prioridad'] == '') ? 'selected' : ''; ?>>Todas</option>
            <option value="alta" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
            <option value="media" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'media') ? 'selected' : ''; ?>>Media</option>
            <option value="baja" <?php echo (isset($_GET['prioridad']) && $_GET['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary" style="width: 200px;" >Filtrar</button>
    </form>
</div>

<?php
$contenido = ob_get_clean();

include('layout.php');
?>