<?php
// Iniciar conexión
$con = mysqli_init();

// Configurar SSL
mysqli_ssl_set($con, NULL, NULL, "DigiCertGlobalRootG2.crt.pem", NULL, NULL);

// Conectar a MySQL en Azure
$servidor = "vindascraft.mysql.database.azure.com";
$usuario = "muebleriavindas";
$contraseña = "Proyecto2025*.";
$base_datos = "muebleria";

if (!mysqli_real_connect($con, $servidor, $usuario, $contraseña, $base_datos, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "✅ Conexión exitosa a la base de datos en Azure";

// Cerrar conexión después de usarla
mysqli_close($con);
?>
