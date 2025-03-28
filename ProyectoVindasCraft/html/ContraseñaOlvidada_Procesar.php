<?php
include('ConexionBD.php');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo electrónico del formulario
    $email = $_POST['email'];

    // Verificar que el correo electrónico no esté vacío
    if (empty($email)) {
        echo "El campo de correo electrónico es obligatorio.";
        exit;
    }

    // Consulta SQL para buscar el correo electrónico en la base de datos
    $sql = "SELECT * FROM usuarios WHERE email = ?"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verificar si el correo electrónico existe en la base de datos
    if ($resultado->num_rows > 0) {
        // El correo está registrado, generar una nueva contraseña temporal
        $temporal_password = bin2hex(random_bytes(6)); // Contraseña temporal aleatoria
        $hashed_password = password_hash($temporal_password, PASSWORD_DEFAULT); // Hashear la contraseña temporal

        // Actualizar la contraseña en la base de datos 
        $sql_update = "UPDATE usuarios SET password = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $hashed_password, $email);
        $stmt_update->execute();

        //Obtener nombre del usuario
        $sql = "SELECT nombre FROM usuarios WHERE email = ?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($sql_nombre); 
        $stmt->fetch();


        // Enviar un correo electrónico con la nueva contraseña 
        $asunto = "Recuperación de Contraseña";
        //IMPORTANTE: Todo el style del correo se hace aqui mismo ya que el servidor de correo no perimite la carga externa de un archivo CSS
        $mensaje = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Recuperación de Contraseña</title>
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
                #temp-password {
                    font-weight: bold;
                    font-size: 18px;
                    color:#2952ff;
                }
                #email-footer {
                    font-size: 14px;
                    color: #999;
                    margin-top: 30px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
        
            <div id='email-container'>
                <div id='email-header'>
                </div>
                
                <h2 id='email-title'>Recuperación de Contraseña</h2>
                
                <p id='email-message'>
                    Hola, ".htmlspecialchars($sql_nombre)." <br>
                    Hemos generado una nueva contraseña para tu cuenta: <span id='temp-password'>" . htmlspecialchars($temporal_password) . "</span><br><br>
                    Te recomendamos cambiarla lo antes posible desde la configuración de tu cuenta.
                </p>
                
                <p id='email-footer'>
                    Si no solicitaste este cambio, por favor ignora este mensaje o contacta a nuestro soporte.<br><br>
                    Saludos,<br>
                    Mueblería Vindas
                </p>
            </div>
        
        </body>
        </html>
        ";
        

        // Encabezados para enviar el correo como HTML
        $headers = "From: recuperacionacceso.vindascraft@gmail.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Función para enviar el correo
        if (mail($email, $asunto, $mensaje, $headers)) {
            echo "Se ha enviado un correo electrónico con tu nueva contraseña";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'InicioSesion.php';
                }, 5000); // 5000 ms = 5 segundos
            </script>";
        } else {
            echo "Hubo un problema al enviar el correo electrónico.";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'InicioSesion.php';
                }, 5000); // 5000 ms = 5 segundos
            </script>";
        }
    } else {
        // El correo no existe en la base de datos
        echo "La información proporcionada no es válida. Por favor, verifique el correo electrónico.";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'ContraseñaOlvidada.php';
                }, 5000); // 5000 ms = 5 segundos
            </script>";
    }
}
?>
