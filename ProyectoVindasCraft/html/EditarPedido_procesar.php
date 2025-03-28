<?php
include('ConexionBD.php');
session_start();

if (!isset($_GET['id'])) {
    die("No se especificó el ID del pedido.");
}

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_nombre         = $_POST['cliente_nombre'] ?? '';
    $cliente_email          = $_POST['cliente_email'] ?? '';
    $cliente_telefono       = $_POST['cliente_telefono'] ?? '';
    $detalles_producto      = $_POST['detalles_producto'] ?? '';
    $fecha_estimada_entrega = $_POST['fecha_estimada_entrega'] ?? '';
    $estado                 = $_POST['estado'] ?? '';
    $prioridad              = $_POST['prioridad'] ?? '';

    if (!empty($cliente_nombre) && !empty($fecha_estimada_entrega) && !empty($estado)) {
        // Verificar conflicto de fechas
        $sql = "SELECT COUNT(*) as count FROM pedidos WHERE fecha_estimada_entrega = '$fecha_estimada_entrega' AND id != $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            // Hay un conflicto
            $mensaje = "Conflicto de fechas detectado. Por favor, elija otra fecha.";
            // Sugerir fechas alternativas
            $sql_alternativas = "SELECT fecha_estimada_entrega FROM pedidos WHERE fecha_estimada_entrega > '$fecha_estimada_entrega' ORDER BY fecha_estimada_entrega ASC LIMIT 3";
            $result_alternativas = $conn->query($sql_alternativas);
            while ($fila = $result_alternativas->fetch_assoc()) {
                $mensaje .= " Fecha alternativa: " . $fila['fecha_estimada_entrega'] . "<br>";
            }

            // Guardar la alerta en la base de datos
            $mensaje_alerta = "Conflicto de fechas detectado para el pedido con fecha de entrega: $fecha_estimada_entrega.";
            $stmt_alerta = $conn->prepare("INSERT INTO alertas (id_pedido, mensaje) VALUES (?, ?)");
            $stmt_alerta->bind_param("is", $id, $mensaje_alerta);
            $stmt_alerta->execute();
            $stmt_alerta->close();

            // Enviar notificación por correo electrónico
            $destinatario = "soporte.vindascraft@gmail.com"; // Correo del administrador
            $asunto = "Conflicto de fechas detectado";
            $cuerpo = "Se ha detectado un conflicto de fechas para el pedido con fecha de entrega: $fecha_estimada_entrega.\n\n$mensaje";
            mail($destinatario, $asunto, $cuerpo);
        } else {
            // No hay conflicto, actualizar el pedido
            $stmt = $conn->prepare("UPDATE pedidos SET cliente_nombre = ?, cliente_email = ?, cliente_telefono = ?, detalles_producto = ?, fecha_estimada_entrega = ?, estado = ?, prioridad = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("sssssssi", $cliente_nombre, $cliente_email, $cliente_telefono, $detalles_producto, $fecha_estimada_entrega, $estado, $prioridad, $id);
                if ($stmt->execute()) {
                    $mensaje = "Pedido actualizado exitosamente.";
                } else {
                    $mensaje = "Error al actualizar el pedido.";
                }
                $stmt->close();
            } else {
                $mensaje = "Error en la preparación de la consulta.";
            }
        }
    } else {
        $mensaje = "Por favor, complete los campos requeridos.";
    }
}

$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();
$stmt->close();

if (!$pedido) {
    die("Pedido no encontrado.");
}

ob_start();
?>

<div class="container mt-5">
    <h1 class="mb-4">Editar Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h1>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="EditarPedido_procesar.php?id=<?php echo $pedido['id']; ?>" method="POST">
        <div class="mb-3">
            <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
            <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" value="<?php echo htmlspecialchars($pedido['cliente_nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="cliente_email" class="form-label">Email del Cliente</label>
            <input type="email" class="form-control" id="cliente_email" name="cliente_email" value="<?php echo htmlspecialchars($pedido['cliente_email']); ?>">
        </div>
        <div class="mb-3">
            <label for="cliente_telefono" class="form-label">Teléfono del Cliente</label>
            <input type="tel" class="form-control" id="cliente_telefono" name="cliente_telefono" value="<?php echo htmlspecialchars($pedido['cliente_telefono']); ?>">
        </div>
        <div class="mb-3">
            <label for="detalles_producto" class="form-label">Detalles del Producto</label>
            <textarea class="form-control" id="detalles_producto" name="detalles_producto" rows="3"><?php echo htmlspecialchars($pedido['detalles_producto']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="fecha_estimada_entrega" class="form-label">Fecha Estimada de Entrega</label>
            <input type="date" class="form-control" id="fecha_estimada_entrega" name="fecha_estimada_entrega" value="<?php echo htmlspecialchars($pedido['fecha_estimada_entrega']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="pendiente" <?php echo ($pedido['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="procesado" <?php echo ($pedido['estado'] == 'procesado') ? 'selected' : ''; ?>>Procesado</option>
                <option value="completado" <?php echo ($pedido['estado'] == 'completado') ? 'selected' : ''; ?>>Completado</option>
                <option value="cancelado" <?php echo ($pedido['estado'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Prioridad</label>
            <select class="form-select" id="estado" name="prioridad" required>
                <option value="baja" <?php echo ($pedido['prioridad'] == 'baja') ? 'selected' : ''; ?>>Baja</option>
                <option value="media" <?php echo ($pedido['prioridad'] == 'media') ? 'selected' : ''; ?>>Media</option>
                <option value="alta" <?php echo ($pedido['prioridad'] == 'alta') ? 'selected' : ''; ?>>Alta</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
        <a href="Pedido.php" class="btn btn-secondary">Volver a la lista</a>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include('layout.php');
?>