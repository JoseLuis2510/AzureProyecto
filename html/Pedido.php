<?php
// index.php
include('ConexionBD.php');
session_start();
if (!isset($_SESSION['id_usuario'])) {
  header("Location: InicioSesion.php"); 
  exit();
}

// Consultar pedidos pendientes
$query = "SELECT * FROM pedidos ORDER BY fecha_creacion DESC";
$result = $conn->query($query);

$pedidos = [];
if ($result) {
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);
}

// Inicia la captura del contenido
ob_start();
?>
<div class="container mt-5">
    <h1 class="mb-4">Lista de pedidos</h1>
    <a href="RegistrarPedido_procesar.php" class="btn btn-primary mb-4" style="width: 200px;">Registrar Nuevo Pedido</a>
    <a href="VerPedidosPendientes.php" class="btn btn-primary mb-4" style="width: 200px;">Ver pedidos pendientes</a>
    

    <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Fecha Creación</th>
            <th>Estimada de entrega</th>
            <th>Detalles</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($pedidos) > 0): ?>
            <?php foreach ($pedidos as $pedido): ?>
              <tr>
                <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                <td><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></td>
                <td><?php echo htmlspecialchars($pedido['fecha_creacion']); ?></td>
                <td><?php echo htmlspecialchars($pedido['fecha_estimada_entrega']); ?></td>
                <td><?php echo htmlspecialchars($pedido['detalles_producto']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($pedido['estado'])); ?></td>
                <td>
                  <a href="EditarPedido_procesar.php?id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-warning" style="margin-bottom: 10px; background-color: #2952ff; border-color: #2952ff; color: white">Editar Pedido</a>
                  <a href="EliminarPedido_procesar.php?id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-danger">Cancelar pedido</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">No hay pedidos pendientes.</td>
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
