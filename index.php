<?php
//
//session_start();
//if (!isset($_SESSION['id_usuario'])) {
   // header("Location: InicioSesion.php");
  //  exit();
//}

// Inicia la captura del contenido
ob_start();
?>
<h1>Bienvenido a Mueblería Vindas</h1>
<p>Gestiona tu inventario, revisa tu perfil y personaliza tus opciones desde el menú lateral.</p>
<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
// Incluye el layout
include('layout.php');
 

 
