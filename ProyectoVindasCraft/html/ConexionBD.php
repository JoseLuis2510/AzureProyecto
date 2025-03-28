<?php
$host = 'vindascraft.mysql.database.azure.com';
$user = 'muebleriavindas';
$pass = 'Proyecto2025*.'; 
$db = 'muebleria';


$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
