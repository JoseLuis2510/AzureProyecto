<?php
$con = mysqli_init();
mysqli_ssl_set($con, NULL, NULL, "DigiCertGlobalRootG2.crt.pem", NULL, NULL);

if (!mysqli_real_connect($con, "vindascraft.mysql.database.azure.com", "muebleriavindas", "Proyecto2025*.", "muebleria", 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "Conexión exitosa a la base de datos en Azure";

?>
