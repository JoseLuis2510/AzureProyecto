<?php
if (isset($_SESSION['nombre'])) {
    echo $_SESSION['nombre'];
} else {
    echo "La variable de sesión 'nombre' no está definida.";
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mueblería Vindas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">



  <style>
     main {
      min-height: calc(100vh - 150px); /* Ajusta según el tamaño del header y footer */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
  </style>
</head>

<body>
  <!-- Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar toggle button -->
      <div class="sidebar-toggle-btn d-xl-none d-block">
          <button id="sidebarToggle" class="btn btn-primary">
              <i class="ti ti-menu-2"></i> 
          </button>
      </div>

      <!-- Sidebar scroll-->
      <div>
          <div class="brand-logo d-flex align-items-center justify-content-between">
              <a href="./index.php" class="text-nowrap logo-img">
                  <img src="../assets/images/logo.jpg" width="200" height="200" />
              </a>
              <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                  <i class="ti ti-x fs-8"></i>
              </div>
          </div>

          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar">
              <ul id="sidebarnav">
                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                      <span class="hide-menu">Inicio</span>
                  </li>
                  <li class="sidebar-item">
                      <a class="sidebar-link" href="./index.php" aria-expanded="false">
                          <span>
                              <iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon>
                          </span>
                          <span class="hide-menu">Mueblería Vindas</span>
                      </a>
                  </li>
                 
                  <?php
                    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2):
                    ?>
                      <li class="nav-small-cap">
                          <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                          <span class="hide-menu">Inventario</span>
                      </li>
                      <li class="sidebar-item">
                          <a class="sidebar-link" href="./Inventario.php" aria-expanded="false">
                              <span>
                                  <iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon>
                              </span>
                              <span class="hide-menu">Gestión de Inventario</span>
                          </a>

                          <a class="sidebar-link" href="./InformeRotacion.php" aria-expanded="false">
                              <span>
                                <iconify-icon icon="bi:file-earmark-bar-graph" class="fs-6"></iconify-icon>
                              </span>
                              <span class="hide-menu">Informe de rotación</span>
                          </a>
                      </li>
                      <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">Planificación</span>
                      </li>
                      <li class="sidebar-item">
                        <a class="sidebar-link" href="./Calendario.php" aria-expanded="false">
                            <span>
                                <iconify-icon icon="carbon:calendar" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Calendario</span>
                        </a>
                        <a class="sidebar-link" href="./Pedido.php" aria-expanded="false">
                            <span>
                                <iconify-icon icon="bx:bx-package" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Gestión de pedidos</span>
                        </a>
                        <!-- Enlace a la página de alertas -->
                        <a class="sidebar-link" href="./Alertas.php" aria-expanded="false">
                            <span>
                                <iconify-icon icon="carbon:warning" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Alertas</span>
                        </a>
                      </li>
                    <?php
                    endif; // Fin de la condición
                    ?>

                  <li class="nav-small-cap">
                      <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                      <span class="hide-menu">Presupuestos</span>
                  </li>
                  
                  <li class="sidebar-item">
                      <a class="sidebar-link" href="./Presupuestos.php" aria-expanded="false">
                          <span>
                              <iconify-icon icon="bi:currency-dollar" class="fs-6" style="color: black;"></iconify-icon>
                          </span>
                          <span class="hide-menu">Gestión de presupuestos</span>
                      </a>
                  </li>
                  
                      <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
                        <span class="hide-menu">Proyectos</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="./Catalogo.php" aria-expanded="false">
                            <span>
                              <iconify-icon icon="carbon:list" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Catálogo</span>
                        </a>
                  </li>
                  <?php
                    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2):
                    ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="./GestionarCatalogo.php" aria-expanded="false">
                            <span>
                              <iconify-icon icon="carbon:list" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Gestionar Catálogo</span>
                        </a>
                  </li>
                  <li class="sidebar-item">
                        <a class="sidebar-link" href="./Categorias.php" aria-expanded="false">
                            <span>
                              <iconify-icon icon="carbon:list" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Gestionar Categorías</span>
                        </a>
                  </li>

                  <li class="sidebar-item">
                        <a class="sidebar-link" href="./Estilos.php" aria-expanded="false">
                            <span>
                              <iconify-icon icon="carbon:list" class="fs-6"></iconify-icon>
                            </span>
                            <span class="hide-menu">Gestionar Estilos</span>
                        </a>
                  </li>
                    <?php
                    endif; // Fin de la condición
                    ?>
                  
                    
                  <li class="sidebar-item" style="visibility: hidden;">
                    <a class="sidebar-link" href="./invisibleOption1.php" aria-expanded="false">
                        <span>
                            <iconify-icon icon="bi:circle" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Opción Invisible 1</span>
                    </a>
                  </li>
                  <li class="sidebar-item" style="visibility: hidden;">
                    <a class="sidebar-link" href="./invisibleOption2.php" aria-expanded="false">
                        <span>
                            <iconify-icon icon="bi:circle" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Opción Invisible 2</span>
                    </a>
                  </li>
                  <li class="sidebar-item" style="visibility: hidden;">
                    <a class="sidebar-link" href="./invisibleOption3.php" aria-expanded="false">
                        <span>
                            <iconify-icon icon="bi:circle" class="fs-6"></iconify-icon>
                        </span>
                        <span class="hide-menu">Opción Invisible 3</span>
                    </a>
                  </li>
              </ul>
          </nav>
      </div>
  </aside>
    
    <!-- Sidebar End -->

    <!-- Main Wrapper -->
    <div class="body-wrapper">
      <!-- Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
            <p style="margin-top: 20px;"><?php echo htmlspecialchars($_SESSION['nombre']); ?></p>
              <li class="nav-item dropdown">
              

                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user rounded-circle" style="font-size: 30px;"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="./VerPerfil.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">Ver perfil</p>
                    </a>
                    <a href="./ActualizarPerfil.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-pencil fs-6"></i>
                      <p class="mb-0 fs-3">Actualizar perfil</p>
                    </a>
                    <a href="./CambiarContraseña.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-key fs-6"></i>
                      <p class="mb-0 fs-3">Cambiar contraseña</p>
                    </a>
                    <a href="./CerrarSesion.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Cerrar Sesión</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!-- Header End -->

      <!-- Main Content Start -->
      <main>
      <?php
            // Aquí se renderiza el contenido capturado
            if (isset($contenido)) {
                echo $contenido;
            } else {
                echo "<p>No se encontró contenido.</p>";
            }
            ?>
      </main>
      <!-- Main Content End -->
    </div>
 
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="../assets/js/sidebarmenu.js"></script>
  <script src="../assets/js/app.min.js"></script>
  <script src="../assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js"></script>
</body>

</html>