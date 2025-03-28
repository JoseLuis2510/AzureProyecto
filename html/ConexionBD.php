<?php
$host = 'localhost';
$user = 'root';
$pass = ''; //Cada uno debe cambiar esto para que la conexion funcione ya que todos tenemos contraseñas distintas
$db = 'muebleria';


$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
