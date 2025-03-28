<?php
// Iniciar conexión
$con = mysqli_init();

// Verificar si el archivo de certificado existe
$certificado = "./DigiCertGlobalRootG2.crt.pem";
if (!file_exists($certificado)) {
    die("❌ Error: No se encontró el archivo de certificado SSL.");
} else {
    echo "✅ Certificado SSL encontrado.<br>";
}

// Configurar SSL
mysqli_ssl_set($con, NULL, NULL, $certificado, NULL, NULL);

// Datos de conexión
$servidor = "vindascraft.mysql.database.azure.com";
$usuario = "muebleriavindas";
$contraseña = "Proyecto2025*.";
$base_datos = "muebleria";

// Intentar conexión con SSL
if (!mysqli_real_connect($con, $servidor, $usuario, $contraseña, $base_datos, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}

echo "✅ Conexión exitosa a MySQL en Azure";

// Cerrar conexión
mysqli_close($con);
?>
