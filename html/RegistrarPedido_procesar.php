<?php
include('ConexionBD.php');
session_start();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $cliente_nombre         = trim($_POST['cliente_nombre'] ?? '');
    $cliente_email          = trim($_POST['cliente_email'] ?? '');
    $cliente_telefono       = trim($_POST['cliente_telefono'] ?? '');
    $detalles_producto      = trim($_POST['detalles_producto'] ?? '');
    $fecha_estimada_entrega = trim($_POST['fecha_estimada_entrega'] ?? '');
    $prioridad              = trim($_POST['prioridad'] ?? '');

    // Validar campos requeridos
    if (!empty($cliente_nombre) && !empty($fecha_estimada_entrega)) {
        // Verificar conflicto de fechas
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM pedidos WHERE fecha_estimada_entrega = ?");
        $stmt->bind_param("s", $fecha_estimada_entrega);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] > 0) {
            // Conflicto de fechas detectado
            $mensaje = "Conflicto de fechas detectado. Por favor, elija otra fecha.";

            // Sugerir fechas alternativas
            $stmt_alt = $conn->prepare("SELECT fecha_estimada_entrega FROM pedidos WHERE fecha_estimada_entrega > ? ORDER BY fecha_estimada_entrega ASC LIMIT 3");
            $stmt_alt->bind_param("s", $fecha_estimada_entrega);
            $stmt_alt->execute();
            $result_alt = $stmt_alt->get_result();
            
            while ($fila = $result_alt->fetch_assoc()) {
                $mensaje .= " Fecha alternativa: " . $fila['fecha_estimada_entrega'] . "<br>";
            }
            $stmt_alt->close();

            // Enviar notificación por correo electrónico
            $destinatario = "soporte.vindascraft@gmail.com"; // Correo del administrador
            $asunto = "Conflicto de fechas detectado";
            $cuerpo = "Se ha detectado un conflicto de fechas para el pedido con fecha de entrega: $fecha_estimada_entrega.\n\n$mensaje";
            mail($destinatario, $asunto, $cuerpo);
        } else {
            // No hay conflicto, guardar el pedido
            $stmt_pedido = $conn->prepare("INSERT INTO pedidos (cliente_nombre, cliente_email, cliente_telefono, detalles_producto, fecha_estimada_entrega, prioridad) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_pedido->bind_param("ssssss", $cliente_nombre, $cliente_email, $cliente_telefono, $detalles_producto, $fecha_estimada_entrega, $prioridad);
            
            if ($stmt_pedido->execute()) {
                $mensaje = "Pedido registrado exitosamente.";
            } else {
                $mensaje = "Error al registrar el pedido.";
            }
            $stmt_pedido->close();
        }
    } else {
        $mensaje = "Por favor complete los campos requeridos.";
    }
}

// Captura el contenido en una variable
ob_start();
?>

<div class="container mt-5">
    <h1 class="mb-4">Registrar Nuevo Pedido</h1>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>
    <form action="RegistrarPedido_procesar.php" method="POST">
        <div class="mb-3">
            <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
            <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre" required>
        </div>
        <div class="mb-3">
            <label for="cliente_email" class="form-label">Email del Cliente</label>
            <input type="email" class="form-control" id="cliente_email" name="cliente_email">
        </div>
        <div class="mb-3">
            <label for="cliente_telefono" class="form-label">Teléfono del Cliente</label>
            <input type="tel" class="form-control" id="cliente_telefono" name="cliente_telefono">
        </div>
        <div class="mb-3">
            <label for="detalles_producto" class="form-label">Detalles del Producto</label>
            <textarea class="form-control" id="detalles_producto" name="detalles_producto" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-control" id="prioridad" name="prioridad" required>
                <option value="baja">Baja</option>
                <option value="media" selected>Media</option>
                <option value="alta">Alta</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_estimada_entrega" class="form-label">Fecha Estimada de Entrega</label>
            <input type="date" class="form-control" id="fecha_estimada_entrega" name="fecha_estimada_entrega" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 200px;">Registrar Pedido</button>
        <a href="Pedido.php" class="btn btn-secondary" style="width: 200px;">Volver a la lista</a>
    </form>
</div>

<?php
$contenido = ob_get_clean();
include('layout.php');
?>
