<?php
include('ConexionBD.php');

if (!isset($_GET['id'])) {
    echo "ID de proyecto no especificado.";
    exit();
}
// Obtener el ID del proyecto desde la URL
$idProyecto = $_GET['id'];

// Consultar la base de datos para obtener los detalles del proyecto
$sql = "SELECT p.*, c.nombre AS categoria, e.nombre AS estilo 
        FROM proyectos p
        JOIN categorias c ON p.categoria_id = c.id_categoria
        JOIN estilos e ON p.estilo_id = e.id_estilo
        WHERE p.id_proyecto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idProyecto);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si el proyecto existe
if ($resultado->num_rows === 0) {
    echo "Proyecto no encontrado.";
    exit;
}

// Obtener los datos del proyecto
$proyecto = $resultado->fetch_assoc();

// Obtener el correo electrónico del usuario desde la sesión
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo "Debes iniciar sesión para solicitar información.";
    exit;
}

$idUsuario = $_SESSION['id_usuario'];
$sqlUsuario = "SELECT email FROM usuarios WHERE id_usuario = ?";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->bind_param("i", $idUsuario);
$stmtUsuario->execute();
$resultadoUsuario = $stmtUsuario->get_result();

if ($resultadoUsuario->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit;
}

$usuario = $resultadoUsuario->fetch_assoc();
$emailUsuario = $usuario['email'];

// Cerrar las consultas preparadas
$stmt->close();
$stmtUsuario->close();

// Configurar el correo electrónico
$asunto = "Información del Proyecto: " . $proyecto['nombre'];


// Obtener la ruta de la imagen desde la base de datos
$rutaRelativaImagen = $proyecto['imagen']; // Ejemplo: "../assets/images/1.png"

// Convertir la ruta relativa a una URL absoluta usando localhost
$urlImagen = "http://localhost/ProyectoMuebleria/ProyectoVindasCraft/" . str_replace("../", "", $rutaRelativaImagen);

// Cuerpo del correo en HTML
$mensaje = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Información del Proyecto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        #email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        #email-title {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        #email-message {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        #project-details {
            font-weight: bold;
            font-size: 18px;
            color: #2952ff;
        }
        #email-footer {
            font-size: 14px;
            color: #999;
            margin-top: 30px;
            text-align: center;
        }
        .project-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id='email-container'>
        <div id='email-header'>
            <h2 id='email-title'>Información del Proyecto</h2>
        </div>
        
        <p id='email-message'>
            Estimado usuario,<br><br>
            Queremos brindarle más información sobre el proyecto <strong>" . htmlspecialchars($proyecto['nombre']) . "</strong>.<br><br>
            A continuación, encontrará los detalles del proyecto:<br><br>
            <strong>Nombre del Proyecto:</strong> " . htmlspecialchars($proyecto['nombre']) . "<br>
            <strong>Categoría:</strong> " . htmlspecialchars($proyecto['categoria']) . "<br>
            <strong>Estilo:</strong> " . htmlspecialchars($proyecto['estilo']) . "<br>
            <strong>Descripción:</strong> " . htmlspecialchars($proyecto['detalles']) . "<br>
            <strong>Imagen:</strong> <a href='" . htmlspecialchars($urlImagen) . "'>Ver imagen</a><br><br>
            Por favor, indíquenos sus dudas sobre las características y posibilidades de personalización.<br><br>
            Saludos,<br>
            Equipo de soporte
        </p>
        
        <p id='email-footer'>
            Si no solicitaste esta información, por favor ignora este mensaje o contacta a nuestro soporte.<br><br>
            Saludos,<br>
            Mueblería Vindas
        </p>
    </div>
</body>
</html>
";

// Encabezados del correo
$headers = "From: no-reply@vindascraft.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Enviar el correo electrónico
if (mail($emailUsuario, $asunto, $mensaje, $headers)) {
    echo "Se ha enviado un correo electrónico con la información del proyecto.";
} else {
    echo "Hubo un problema al enviar el correo electrónico.";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>