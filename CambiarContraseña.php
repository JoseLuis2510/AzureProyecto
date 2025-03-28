<?php
session_start();


if (!isset($_SESSION['id_usuario'])) {
    header("Location: InicioSesion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php

ob_start();
?>
    <div class="container mt-5">
        <h2 class="text-center">Cambiar Contraseña</h2>

        <?php
        // Mostrar mensaje si existe
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='alert alert-warning text-center' style='margin: 20px auto; width: 50%;'>
                    {$_SESSION['mensaje']}
                  </div>";
            unset($_SESSION['mensaje']); 
        }
        ?>

        <form action="CambiarContraseña_Procesar.php" method="post">
            <div class="mb-4">
                <label for="contrasennaAnterior" class="form-label">Contraseña Anterior</label>
                <input type="password" id="contrasennaAnterior" name="contrasennaAnterior" class="form-control form-control-lg rounded-pill" >
            </div>

            <div class="mb-4">
                <label for="nuevaContrasenna" class="form-label">Nueva Contraseña</label>
                <input type="password" id="nuevaContrasenna" name="nuevaContrasenna" class="form-control form-control-lg rounded-pill" >
            </div>

            <div class="mb-4">
                <label for="confirmarContrasenna" class="form-label">Confirmar Contraseña</label>
                <input type="password" id="confirmarContrasenna" name="confirmarContrasenna" class="form-control form-control-lg rounded-pill">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill fw-bold btn-lg" style="transition: all 0.3s ease;">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
$contenido = ob_get_clean();
// Incluye el layout
include('layout.php');
?>
