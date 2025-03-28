<?php
$con = mysqli_init();
mysqli_ssl_set($con,NULL,NULL, "./DigiCertGlobalRootG2.crt.pem", NULL, NULL);
mysqli_real_connect($con, "vindascraft.mysql.database.azure.com", "muebleriavindas", "Proyecto2025*.", "muebleriavindas", 3306, MYSQLI_CLIENT_SSL);
?>
