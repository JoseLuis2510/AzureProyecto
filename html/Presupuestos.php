<?php
session_start();
include('ConexionBD.php');

//librería FPDF para generar el PDF
require('../src/fpdf186/fpdf.php');
require('../src/Exception.php');
require('../src/PHPMailer.php'); 
require('../src/SMTP.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php"); 
    exit();
}

// Obtener ID de usuario y rol desde la sesión
$id_usuario = $_SESSION['id_usuario'];
$id_rol = $_SESSION['id_rol']; 


// Función para enviar el correo electrónico con PDF adjunto
function enviarCorreo($email_usuario, $pdf_filename) {
    $subject = "Presupuesto Aprobado";
    $message = "Estimado usuario,\n\nSu presupuesto ha sido aprobado. Adjunto encontrará el PDF con los detalles.\n\nSaludos.";
    $headers = "From: soporte.vindascraft@gmail.com";
    
    
    $boundary = md5(time());
    $headers .= "\nMIME-Version: 1.0";
    $headers .= "\nContent-Type: multipart/mixed; boundary=\"$boundary\"";
    
   
    $body = "--$boundary\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\n";
    $body .= "Content-Transfer-Encoding: 7bit\n\n";
    $body .= $message . "\n";
    
  
    $file = fopen($pdf_filename, "rb");
    $data = fread($file, filesize($pdf_filename));
    fclose($file);
    
    $body .= "--$boundary\n";
    $body .= "Content-Type: application/pdf; name=\"" . basename($pdf_filename) . "\"\n";
    $body .= "Content-Transfer-Encoding: base64\n";
    $body .= "Content-Disposition: attachment; filename=\"" . basename($pdf_filename) . "\"\n\n";
    $body .= chunk_split(base64_encode($data)) . "\n";
    $body .= "--$boundary--";
    
    
    mail($email_usuario, $subject, $body, $headers);
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['eliminar'])) {
        $id_presupuesto = $_POST['id_presupuesto'];

        // Consulta para eliminar el presupuesto
        $sql_delete = "DELETE FROM presupuestos WHERE id_presupuesto = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param('i', $id_presupuesto);
        
       
        if ($stmt_delete->execute()) {
            echo "Presupuesto eliminado exitosamente.";
        } else {
            echo "Error al eliminar presupuesto: " . $stmt_delete->error;
        }

        $stmt_delete->close();
    } 
    
    elseif (isset($_POST['rechazar'])) {
        $id_presupuesto = $_POST['id_presupuesto'];

        // Consulta para actualizar el estado a "Rechazado"
        $sql_rechazar = "UPDATE presupuestos SET estado = 'Rechazado' WHERE id_presupuesto = ?";
        $stmt_rechazar = $conn->prepare($sql_rechazar);
        $stmt_rechazar->bind_param('i', $id_presupuesto);
        
      
        if ($stmt_rechazar->execute()) {
            echo "Presupuesto rechazado exitosamente.";
        } else {
            echo "Error al rechazar presupuesto: " . $stmt_rechazar->error;
        }

        $stmt_rechazar->close();
    }
   
    elseif (isset($_POST['aprobar'])) {
        $id_presupuesto = $_POST['id_presupuesto'];


        // Actualizar el estado del presupuesto
        $sql_aprobar = "UPDATE presupuestos SET estado = 'Aprobado' WHERE id_presupuesto = ?";
        $stmt_aprobar = $conn->prepare($sql_aprobar);
        $stmt_aprobar->bind_param('i', $id_presupuesto);

        // Consulta para obtener el presupuesto
        $sql_presupuesto = "SELECT id_presupuesto, nombre_proyecto, estado, fecha_creacion, monto_estimado, id_usuario FROM presupuestos WHERE id_presupuesto = ?";
        $stmt_presupuesto = $conn->prepare($sql_presupuesto);
        $stmt_presupuesto->bind_param('i', $id_presupuesto);
        $stmt_presupuesto->execute();
        $result_presupuesto = $stmt_presupuesto->get_result();
        $presupuesto = $result_presupuesto->fetch_assoc();
        $stmt_presupuesto->close();

        
        
       
        if ($stmt_aprobar->execute()) {
            
            $sql_usuario = "SELECT email FROM usuarios WHERE id_usuario = ?";
            $stmt_usuario = $conn->prepare($sql_usuario);
            $stmt_usuario->bind_param('i', $presupuesto['id_usuario']);
            $stmt_usuario->execute();
            $result_usuario = $stmt_usuario->get_result();
            $usuario = $result_usuario->fetch_assoc();
            $stmt_usuario->close();

            // Generar el PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $logo = '../assets/images/logo.jpg'; $width_logo = 33; 
            $pdf->Image($logo, 170, 8, $width_logo); 
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, "Presupuesto Aprobado");
            $pdf->Ln(20);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 10, "Descripcion del servicio " . $presupuesto['nombre_proyecto']);
            $pdf->Ln(10);
            $pdf->Cell(40, 10, "Monto Estimado: " . $presupuesto['monto_estimado']);
            $pdf->Ln(10);
            $pdf->MultiCell(0, 10, "Fecha estimada: El tiempo estimado para la entrega es de 15 dias a partir de la recepcion del pedido formal. Esto nos permite garantizar que podamos procesar y preparar el pedido con la calidad y precision que usted espera.");
            $pdf->Ln(10);
            $pdf->Cell(40, 10, "Fecha de Creacion: " . $presupuesto['fecha_creacion']);
            $pdf_filename = "presupuesto_" . $id_presupuesto . ".pdf";
            $pdf->Output('F', $pdf_filename);

        
        enviarCorreo($usuario['email'], $pdf_filename);

        
        unlink($pdf_filename);

        echo "Presupuesto aprobado y correo enviado exitosamente.";
    } else {
        echo "Error al aprobar presupuesto: " . $stmt_aprobar->error;
    }

    $stmt_aprobar->close();
    }
    
    elseif (isset($_POST['actualizar_monto'])) {
        $id_presupuesto = $_POST['id_presupuesto'];
        $nuevo_monto_estimado = $_POST['nuevo_monto_estimado'];

        // Consulta para actualizar el monto estimado
        $sql_update = "UPDATE presupuestos SET monto_estimado = ? WHERE id_presupuesto = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('di', $nuevo_monto_estimado, $id_presupuesto);
        
        if ($stmt_update->execute()) {
            echo "Monto estimado actualizado exitosamente.";
        } else {
            echo "Error al actualizar monto estimado: " . $stmt_update->error;
        }

        $stmt_update->close();
    }
   
    else {
        $nombre_proyecto = $_POST['nombre_proyecto'];
        $detalles = $_POST['detalle'];
        $monto_estimado = $_POST['monto_estimado']; 

        
        $sql_insert = "INSERT INTO presupuestos (id_usuario, detalles, nombre_proyecto, monto_estimado) 
                       VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param('issd', $id_usuario, $detalles, $nombre_proyecto, $monto_estimado);
        
        
        if ($stmt_insert->execute()) {
            echo "Presupuesto agregado exitosamente.";
        } else {
            echo "Error al agregar presupuesto: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }
}




if ($id_rol == 1) { 
    $sql_presupuestos = "SELECT id_presupuesto, nombre_proyecto, estado, fecha_creacion, detalles, monto_estimado FROM presupuestos WHERE id_usuario = ?";
    $stmt_presupuestos = $conn->prepare($sql_presupuestos);
    $stmt_presupuestos->bind_param('i', $id_usuario); 
} else { 
    $sql_presupuestos = "SELECT id_presupuesto, nombre_proyecto, estado, fecha_creacion, detalles, monto_estimado FROM presupuestos";
    $stmt_presupuestos = $conn->prepare($sql_presupuestos); 
}

$stmt_presupuestos->execute();
$result_presupuestos = $stmt_presupuestos->get_result();

$presupuestos = [];
while ($row = $result_presupuestos->fetch_assoc()) {
    $presupuestos[] = $row;
}

$stmt_presupuestos->close();

?>

<?php
// Inicia la captura del contenido
ob_start();
?>


<div class="tabs">
<?php if ($id_rol == 1): ?>
    <div class="tab active" id="cliente-tab">Cliente</div>
<?php endif; ?>   
    
    <?php if ($id_rol == 2): ?>
        <div class="tab" id="admin-tab">Administrador</div>
    <?php endif; ?>
</div>


<?php if ($id_rol == 1): ?>
    <div class="section active" id="cliente-section">
    <h2>Solicitar Presupuesto Personalizado</h2>
    <form id="form-solicitud" method="POST">
        <label for="nombre_proyecto">Nombre del proyecto:</label>
        <input type="text" id="nombre_proyecto" name="nombre_proyecto" style="width:100%; margin-bottom:10px;" required>
        
        <label for="detalle">Detalles de la solicitud:</label><br>
        <textarea id="detalle" name="detalle" rows="4" style="width:100%; margin-bottom:10px;" required></textarea>
        
        <label for="monto_estimado">Monto estimado:</label>
        <input type="number" id="monto_estimado" name="monto_estimado" style="width:100%; margin-bottom:10px;" step="0.01" required>

        <button type="submit">Enviar Solicitud</button>
    </form>

    <h2>Mis Presupuestos</h2>
    <table id="tabla-presupuestos-cliente">
    <thead>
        <tr>
            <th>ID</th>
            <th>Estado</th>
            <th>Detalles</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($presupuestos as $presupuesto): ?>
            <tr>
                <td data-label="ID"><?php echo $presupuesto['id_presupuesto']; ?></td>
                <td data-label="Estado"><?php echo $presupuesto['estado']; ?></td>
                <td data-label="Detalles"><?php echo $presupuesto['detalles']; ?></td>
                <td data-label="Fecha"><?php echo $presupuesto['fecha_creacion']; ?></td>
                <td data-label="Acciones">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_presupuesto" value="<?php echo $presupuesto['id_presupuesto']; ?>">
                        <button type="submit" name="eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>
<?php endif; ?>

<!-- Sección para Administrador -->
<?php if ($id_rol == 2): ?>
    <div class="table-responsive">

        <?php
            // Mostrar mensaje si existe
            if (isset($_SESSION['mensaje'])) {
                echo "<div class='alert {$_SESSION['mensaje_tipo']} text-center' style='margin: 20px auto; width: 50%;'>
                        {$_SESSION['mensaje']}
                        </div>";
                unset($_SESSION['mensaje']);
                unset($_SESSION['mensaje_tipo']);
            }
        ?>
<style>
        .form-responsive {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-responsive label {
    margin-bottom: 5px;
}

.form-responsive input {
    padding: 8px;
    margin-bottom: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.button-container {
    display: flex;
    gap: 10px;
    justify-content: space-between;
}

.btn {
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn:hover {
    opacity: 0.8;
}

/* Responsividad */
@media (max-width: 768px) {
    .form-responsive {
        padding: 15px;
    }

    .button-container {
        flex-direction: column;
        align-items: stretch;
    }

    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
    </style>

        <!-- Formulario para generar reportes -->
        <form method="GET" action="generarReportes.php" class="form-responsive">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <div class="button-container">
                <button type="submit" name="reporte" value="csv" class="btn btn-primary">Generar Reporte CSV</button>
                <button type="submit" name="reporte" value="pdf" class="btn btn-danger">Generar Reporte PDF</button>
            </div>
        </form>

    
    <table id="tabla-presupuestos-admin">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Detalles</th>
                <th>Monto Estimado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($presupuestos as $presupuesto): ?>
                <tr>
                    <td data-label="ID"><?php echo $presupuesto['id_presupuesto']; ?></td>
                    <td data-label="Proyecto"><?php echo $presupuesto['nombre_proyecto']; ?></td>
                    <td data-label="Estado"><?php echo $presupuesto['estado']; ?></td>
                    <td data-label="Fecha"><?php echo $presupuesto['fecha_creacion']; ?></td>
                    <td data-label="Detalles" class="detalles-celda"><?php echo $presupuesto['detalles']; ?></td>
                    <td data-label="Monto Estimado"><?php echo $presupuesto['monto_estimado']; ?></td>
                    <td data-label="Acciones">
                        <div class="acciones-container">
                            <form method="POST">
                                <input type="hidden" name="id_presupuesto" value="<?php echo $presupuesto['id_presupuesto']; ?>">
                                <label>Nuevo Monto:</label>
                                <input type="number" name="nuevo_monto_estimado" step="0.01" value="<?php echo $presupuesto['monto_estimado']; ?>" required>
                                <button type="submit" name="actualizar_monto" class="btn actualizar">Actualizar</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="id_presupuesto" value="<?php echo $presupuesto['id_presupuesto']; ?>">
                                <button type="submit" name="aprobar" class="btn aprobar">Aprobar</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="id_presupuesto" value="<?php echo $presupuesto['id_presupuesto']; ?>">
                                <button type="submit" name="rechazar" class="btn rechazar">Rechazar</button>
                            </form>

                            <form method="POST">
                                <input type="hidden" name="id_presupuesto" value="<?php echo $presupuesto['id_presupuesto']; ?>">
                                <button type="submit" name="eliminar" class="btn eliminar">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>



<?php

$contenido = ob_get_clean();
 
// Incluye el layout
include('layout.php');
?>

<script>
    
    <?php if ($id_rol == 2): ?>
        document.getElementById("admin-tab").classList.add("active");
        document.getElementById("admin-section").classList.add("active");
    <?php endif; ?>

    
    document.getElementById("cliente-tab").onclick = function() {
        document.getElementById("cliente-section").classList.add("active");
        document.getElementById("admin-section").classList.remove("active");
        document.getElementById("cliente-tab").classList.add("active");
        document.getElementById("admin-tab").classList.remove("active");
    };

    <?php if ($id_rol == 2): ?>
        document.getElementById("admin-tab").onclick = function() {
            document.getElementById("admin-section").classList.add("active");
            document.getElementById("cliente-section").classList.remove("active");
            document.getElementById("admin-tab").classList.add("active");
            document.getElementById("cliente-tab").classList.remove("active");
        };
    <?php endif; ?>

    
</script>