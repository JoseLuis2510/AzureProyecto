<?php
include('ConexionBD.php');
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 2) {
    header("Location: InicioSesion.php");
    exit();
}

// Obtener alertas no leídas
$query = "SELECT a.id_alerta, a.mensaje, a.fecha, p.id, p.cliente_nombre 
          FROM alertas a
          JOIN pedidos p ON a.id_pedido = p.id
          WHERE a.leida = FALSE
          ORDER BY a.fecha DESC";
$result = $conn->query($query);

$alertas = [];
if ($result) {
    $alertas = $result->fetch_all(MYSQLI_ASSOC);
}

// Inicia la captura del contenido
ob_start();
?>

<div class="container mt-5">
    <h1 class="mb-4">Alertas de Conflictos de Fechas</h1>
    <a href="Pedido.php" class="btn btn-primary mb-4" style="width: 200px;">Volver a la lista de pedidos</a>

    <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID Alerta</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Pedido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($alertas) > 0): ?>
                    <?php foreach ($alertas as $alerta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($alerta['id_alerta']); ?></td>
                            <td><?php echo htmlspecialchars($alerta['mensaje']); ?></td>
                            <td><?php echo htmlspecialchars($alerta['fecha']); ?></td>
                            <td>
                                <a href="EditarPedido_procesar.php?id=<?php echo $alerta['id']; ?>">
                                    Pedido #<?php echo htmlspecialchars($alerta['id']); ?> - <?php echo htmlspecialchars($alerta['cliente_nombre']); ?>
                                </a>
                            </td>
                            <td>
                                <a href="MarcarAlertaLeida.php?id=<?php echo htmlspecialchars($alerta['id_alerta']); ?>" class="btn btn-sm btn-success">Marcar como leída</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay alertas pendientes.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();

// Incluye el layout
include('layout.php');
?>