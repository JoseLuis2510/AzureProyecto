<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio Sesión</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
<?php
session_start(); 

// Verifica si hay un mensaje en la sesión
if (isset($_SESSION['mensaje'])) {
    echo "<div class='alert alert-warning' style='text-align: center; margin: 0 auto; width: 50%;'>
            " . $_SESSION['mensaje'] . "
          </div>";

    // Borra el mensaje para que no se muestre en el siguiente acceso
    unset($_SESSION['mensaje']);
}
?>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./InicioSesion.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logo.jpg" width="200px"  alt="">
                </a>
                <p class="text-center">Vindas Mueblería</p>
                <form action="InicioSesion_Procesar.php" method="POST" id="formulario">
                <div class="mb-3">
                    <label for="identificacion" class="form-label">Identificación</label>
                    <input type="text" class="form-control" id="identificacion" name="identificacion" >
                    <div class="invalid-feedback">Por favor ingrese su identificación</div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" >
                    <div class="invalid-feedback">Por favor ingrese su contraseña</div>
                </div>
                <div class="mb-3">
                    <a class="text-primary fw-bold" href="./ContraseñaOlvidada.php">Olvidó su contraseña</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Ingresar</button>
                <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">¿Usuario nuevo?</p>
                    <a class="text-primary fw-bold ms-2" href="./CrearCuenta.php">Crear cuenta</a>
                </div>
              </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>