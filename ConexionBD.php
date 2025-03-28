<?php
// Datos de la base de datos
$servidor = "vindascraft.mysql.database.azure.com";  // Asegúrate que este es el servidor correcto
$usuario = "muebleriavindas";  // Usuario con privilegios para acceder a la base de datos
$contraseña = "Proyecto2025*.";  // La contraseña correcta
$base_datos = "muebleria";  // El nombre de la base de datos que estás utilizando

// Crear la conexión
$conn = mysqli_init();

// Configurar SSL
mysqli_ssl_set($conn, NULL, NULL, "./DigiCertGlobalRootG2.crt.pem", NULL, NULL);

// Intentar la conexión
if (!mysqli_real_connect($conn, $servidor, $usuario, $contraseña, $base_datos, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    echo('❌ Error al conectar a MySQL: ' . mysqli_connect_error());
} else {
    echo "✅ Conexión exitosa a MySQL en Azure";
}

// Cerrar la conexión al final
mysqli_close($conn);
?>
