<?php
// EliminarPedido_procesar.php
include('ConexionBD.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Iniciar la transacción
    $conn->begin_transaction();
    
    try {
        // Eliminar registros en la tabla 'alertas' relacionados con el pedido
        $stmt_alertas = $conn->prepare("DELETE FROM alertas WHERE id_pedido = ?");
        if ($stmt_alertas) {
            $stmt_alertas->bind_param("i", $id);
            $stmt_alertas->execute();
            $stmt_alertas->close();
        } else {
            throw new Exception("Error al preparar la consulta para eliminar las alertas.");
        }

        // Ahora eliminar el pedido
        $stmt_pedido = $conn->prepare("DELETE FROM pedidos WHERE id = ?");
        if ($stmt_pedido) {
            $stmt_pedido->bind_param("i", $id);
            $stmt_pedido->execute();
            $stmt_pedido->close();
        } else {
            throw new Exception("Error al preparar la consulta para eliminar el pedido.");
        }

        // Confirmar la transacción
        $conn->commit();

        // Redirigir a index.php con un mensaje (opcional)
        header("Location: Pedido.php");
        exit();
    } catch (Exception $e) {
        // Si hubo un error, revertir la transacción
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se especificó el ID del pedido.";
}
?>

