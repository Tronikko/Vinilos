<nav class="navbar fixed-top" style="background-color: #0066cc;">
  <div class="container-fluid">
    <!-- Botón del menú hamburguesa al lado izquierdo -->
    <button class="navbar-toggler text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Título centrado -->
    <a class="navbar-brand mx-auto text-white" href="#">Vinyl Records</a>

    <!-- Offcanvas Menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" style="background-color: #e3f2fd;">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel" style="color: #0066cc;">Menú</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-start flex-grow-1 pe-3">
          <!-- Opciones del menú -->
          <li class="nav-item">
            <a class="nav-link" href="../pages/tienda.php" style="color: #0066cc;">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../pages/carrito.php" style="color: #0066cc;">Carrito de Compra</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" href="../pages/index.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
