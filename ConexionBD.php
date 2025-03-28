<?php
$conn = mysqli_init();

// Asegúrate de que el nombre de usuario y la contraseña estén correctamente configurados
$usuario = "muebleriavindas";  // Tu nombre de usuario
$contraseña = "Proyecto2025*."; // Tu contraseña

// Verifica si se está configurando correctamente el SSL
mysqli_ssl_set($conn, NULL, NULL, "./DigiCertGlobalRootG2.crt.pem", NULL, NULL);

// Intentar la conexión a la base de datos
if (!mysqli_real_connect($conn, "vindascraft.mysql.database.azure.com", $usuario, $contraseña, "muebleria", 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die('❌ Error de conexión a MySQL: ' . mysqli_connect_error());
} else {
    echo "✅ Conexión exitosa a MySQL en Azure";
}

// Cerrar la conexión
mysqli_close($conn);
?>
