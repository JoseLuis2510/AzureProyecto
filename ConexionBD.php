<?php
$host = 'vindascraft.mysql.database.azure.com';
echo $username = 'muebleriavindas';
$password = 'Proyecto2025*.';
$db_name = 'muebleria';

$conn = mysqli_init();
mysqli_ssl_set($con,NULL,NULL, "./DigiCertGlobalRootG2.crt.pem", NULL, NULL);
mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno($conn)){
die ('Failed to connect to MySQL: '.mysqli_connect_error());
}

mysqli_close($conn);
?>
