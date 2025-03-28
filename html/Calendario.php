<?php
include('ConexionBD.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}
ob_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Entregas</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Calendario de Entregas</h1>
        <a href="EntregasSemanales.php" class="btn btn-primary btn-sm" style="width: 200px;">Ver Entregas Semanales</a>


       <!-- Contenedor del calendario -->
       <div id="calendario"></div>

<!-- Modal para mostrar detalles del pedido -->
<div class="modal fade" id="modalPedido" tabindex="-1" aria-labelledby="modalPedidoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPedidoLabel">Detalles del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Cliente:</strong> <span id="clienteNombre"></span></p>
                <p><strong>Email:</strong> <span id="clienteEmail"></span></p>
                <p><strong>Teléfono:</strong> <span id="clienteTelefono"></span></p>
                <p><strong>Detalles del Producto:</strong> <span id="detallesProducto"></span></p>
                <p><strong>Prioridad:</strong> <span id="prioridad"></span></p>
                <p><strong>Estado:</strong> <span id="estado"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Script para inicializar FullCalendar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            var url = 'obtener_evento.php?start=' + fetchInfo.start.toISOString() + '&end=' + fetchInfo.end.toISOString();

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    successCallback(data); 
                    setTimeout(() => colorearDiasPedidos(data), 500); // Esperar que los eventos se rendericen
                })
                .catch(error => failureCallback(error));
        }
    });

    calendar.render();

    function colorearDiasPedidos(eventos) {
        document.querySelectorAll('.fc-daygrid-day').forEach(celda => {
            var fechaCelda = celda.getAttribute('data-date'); // Formato YYYY-MM-DD
            var eventosDelDia = eventos.filter(event => event.start.split('T')[0] === fechaCelda);

            if (eventosDelDia.length > 0) {
                var prioridad = eventosDelDia[0].prioridad;
                var color = prioridad === 'alta' ? '#ff4444' : (prioridad === 'media' ? '#ffbb33' : '#00C851');

                celda.style.backgroundColor = color;
                celda.style.color = 'white';
            }
        });
    }
});


</script>
</body>
</html>

<?php
$contenido = ob_get_clean();

include('layout.php');
?>