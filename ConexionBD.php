
<?php
$host = "vindascraft.mysql.database.azure.com";  // Nombre del servidor
$username = "muebleriavindas";                    // Tu nombre de usuario de la base de datos
$password = "Proyecto2025*.";                    // Tu contraseña
$dbname = "muebleria";                      // Nombre de la base de datos
$port = 3306;                                    // Puerto de MySQL

// Inicializa la conexión MySQL
$con = mysqli_init();

// Ruta al certificado CA (el archivo está en el mismo directorio)
$caCertPath = "BaltimoreCyberTrustRoot.crt.pem"; // Solo el nombre del archivo si está en el mismo directorio

// Configura la conexión SSL
mysqli_ssl_set($con, NULL, NULL, $caCertPath, NULL, NULL);

// Establece la conexión real
$connection = mysqli_real_connect($con, $host, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

// Verifica si la conexión fue exitosa
if ($connection) {
    echo "Conexión exitosa a la base de datos MySQL en Azure!";
} else {
    echo "Error en la conexión: " . mysqli_connect_error();
}

// Cierra la conexión
mysqli_close($con);
?>

