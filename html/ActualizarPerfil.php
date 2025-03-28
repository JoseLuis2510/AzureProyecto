<?php
session_start();
include('ConexionBD.php'); // Se incluye la conexion a la base de datos

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario']; 

// Consulta para obtener los datos del usuario
$sql = "SELECT identificacion, nombre, email FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_usuario); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $identificacion = $user['identificacion'];
    $nombre = $user['nombre'];
    $correo = $user['email'];
} else {
    echo "<script>
            alert('No se encontró información del usuario.');
            window.location.href = 'InicioSesion.php';
          </script>";
    exit();
}

// Mostrar mensaje si existe
if (isset($_SESSION['mensaje'])) {
    echo '<div class="alert alert-info" role="alert">' . $_SESSION['mensaje'] . '</div>';
    unset($_SESSION['mensaje']); 
}
?>

<div class="col-md-8 col-lg-6 col-xxl-3">
    <div class="card mb-0">
        <div class="card-body">
            <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="../assets/images/ActualizarPerfil.png" width="150px" alt="">
            </a>
            <p class="text-center">Actualizar información</p>
            <form action="ActualizarPerfil_Procesar.php" method="post">
                <div class="mb-3">
                    <label for="id" class="form-label">Identificación:</label>
                    <input type="text" class="form-control" id="id" name="id" value="<?php echo $identificacion; ?>" >
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>" >
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $correo; ?>" >
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Actualizar</button>
            </form>
        </div>
    </div>
</div>

<?php
$contenido = ob_get_clean();
// Incluye el layout
include('layout.php');
?>
