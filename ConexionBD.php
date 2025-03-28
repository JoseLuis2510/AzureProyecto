<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

$con = mysqli_init();
mysqli_ssl_set($con, NULL, NULL, "BaltimoreCyberTrustRoot.crt.pem", NULL, NULL);

// Usar la misma variable $con en mysqli_real_connect
if (mysqli_real_connect($con, "vindascraft.mysql.database.azure.com", "muebleriavindas", "Proyecto2025*.", "muebleria", 3306, NULL, MYSQLI_CLIENT_SSL)) {
    echo "Conexión exitosa a la base de datos MySQL en Azure!";
} else {
    echo "Error en la conexión: " . mysqli_connect_error();
}

// Cerrar la conexión
mysqli_close($con);
?>
