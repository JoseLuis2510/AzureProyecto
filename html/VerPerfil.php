<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['id_usuario']; 

// Conexión a la base de datos
include('ConexionBD.php');

// Inicializar las variables
$identificacion = $nombre = $email = '';


$sql = "SELECT identificacion, nombre, email FROM usuarios WHERE id_usuario = ?"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id); 
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encuentra el usuario
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Asignar los valores a las variables
    $identificacion = $user['identificacion']; 
    $nombre = $user['nombre']; 
    $email = $user['email']; 
} else {
    echo "<script>
            alert('No se pudo cargar la información del perfil.');
            window.location.href = 'InicioSesion.php';
          </script>";
    exit();
}
?>
<?php
// Inicia la captura del contenido
ob_start();
?>
<div class="d-flex align-items-center justify-content-center w-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
                <div class="card-body text-center">
                    <a href="./index.php" class="d-flex justify-content-center">
                        <img src="../assets/images/perfil.jpg" width="200px" alt="">
                    </a>
                    <p class="text-center">Mi perfil</p>
                    <form>
                    <div class="mb-3" style="display: flex; flex-direction: column; align-items: center;">
                        <label for="identificacion" class="form-label">Identificación:</label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" value="<?php echo htmlspecialchars($identificacion); ?>" readonly style="width: 100%; margin-top: 5px; text-align: center;">
                    </div>
                    <div class="mb-3" style="display: flex; flex-direction: column; align-items: center;">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" readonly style="width: 100%; margin-top: 5px; text-align: center;">
                    </div>
                    <div class="mb-3" style="display: flex; flex-direction: column; align-items: center;">
                        <label for="correo" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($email); ?>" readonly style="width: 100%; margin-top: 5px; text-align: center;">
                    </div>
                </form>



                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Captura el contenido en una variable
$contenido = ob_get_clean();
 
// Incluye el layout
include('layout.php');

