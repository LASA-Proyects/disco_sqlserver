<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/bootstrap-4.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/fontawesome.min.css">
<nav class="navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
        <p class="nav-link" style="margin: 0;">
            <span style="font-weight: bold;">Almacén:</span> <?php echo $_SESSION['nom_al']; ?>
        </p>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
        <p class="nav-link" style="margin: 0;">
            <span style="font-weight: bold;">Usuario:</span> <?php echo $_SESSION['nombre']; ?>
        </p>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-sign-out-alt"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">Sesión</span>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url; ?>Usuarios/salir" class="dropdown-item">
            <i class="fas fa-power-off mr-2"></i> Cerrar Sesión
            </a>
        </div>
        </li>
    </ul>
</nav>