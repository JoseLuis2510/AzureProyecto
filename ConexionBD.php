<?php
// Iniciar conexión
$conn = mysqli_init();

// Verificar si la inicialización de la conexión MySQL fue exitosa
if (!$conn) {
    die("❌ Error al inicializar la conexión MySQL.");
} else {
    echo "✅ Conexión MySQL inicializada.<br>";
}

// Verificar si el archivo de certificado SSL existe
$certificado = "./DigiCertGlobalRootG2.crt.pem";
if (!file_exists($certificado)) {
    die("❌ Error: No se encontró el archivo de certificado SSL.");
} else {
    echo "✅ Certificado SSL encontrado.<br>";
}

// Configurar SSL
mysqli_ssl_set($conn, NULL, NULL, $certificado, NULL, NULL);

// Intentar la conexión a la base de datos
$servidor = "vindascraft.mysql.database.azure.com";
$usuario = "muebleriavindas";
$contraseña = "Proyecto2025*.";
$base_datos = "muebleria";

// Intentar la conexión a MySQL
echo "Intentando conectar a MySQL...<br>";
if (!mysqli_real_connect($conn, $servidor, $usuario, $contraseña, $base_datos, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die('❌ Error de conexión a MySQL: ' . mysqli_connect_error());
} else {
    echo "✅ Conexión exitosa a MySQL en Azure<br>";
}

// Retornar la conexión si la necesitas más tarde
return $conn;

// Cerrar la conexión
mysqli_close($conn);
?>
