<?php
$conn = mysqli_init();
mysqli_ssl_set($con,NULL,NULL, "./DigiCertGlobalRootG2.crt.pem", NULL, NULL);
if (!mysqli_real_connect($conn, "vindascraft.mysql.database.azure.com", "muebleriavindas", "Proyecto2025*.", "muebleria", 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die('❌ Error al conectar a MySQL: ' . mysqli_connect_error());
} else {
    echo "✅ Conexión exitosa a MySQL en Azure";
}

// Retornar la conexión si la necesitas más tarde
return $conn;
?>

