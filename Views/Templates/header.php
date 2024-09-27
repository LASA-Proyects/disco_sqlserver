<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>
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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
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

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?php echo base_url; ?>Configuracion/home" class="brand-link">
      <span class="brand-text font-weight-light">SISTEMA ADMINISTRATIVO</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?php echo base_url; ?>Configuracion/home" class="nav-link">
              <i class="nav-icon fas fa-laptop-house"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>
          <?php
            $sistemaMostrado = false;
            $almacenMostrado = false;
            $bancoMostrado = false;
            $productoMostrado = false;
            $ingresoMostrado = false;
            $salidaMostrado = false;
            $facturacionMostrado = false;

            foreach ($_SESSION['permisos'] as $permiso) {
                if ($permiso['id_permiso_padre'] == 1 && !$sistemaMostrado) {
                    $sistemaMostrado = true;
                    ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-copy"></i>
                            <p>
                                Sistemas
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 1) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 1 || $subpermiso['id_permiso_hijo'] == 3) : ?>
                                        <li class="nav-item">
                                            <a href="<?= base_url . ($subpermiso['id_permiso_hijo'] == 1 ? 'Usuarios' : 'Permisos') ?>" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?= $subpermiso['id_permiso_hijo'] == 1 ? 'Usuarios' : 'Permisos' ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php } elseif ($permiso['id_permiso_padre'] == 2 && $permiso['id_permiso_hijo'] == '') { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url; ?>Pedidos" class="nav-link">
                            <i class="nav-icon fas fa-paste"></i>
                            <p>Pedidos</p>
                        </a>
                    </li>
                <?php } elseif ($permiso['id_permiso_padre'] == 3 && !$almacenMostrado) { ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Almacén
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 3) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 4) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Productos/stock" class="nav-link">
                                                <i class="fas fa-box nav-icon"></i>
                                                <p>Stock</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 5) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Kardex" class="nav-link">
                                                <i class="fas fa-clipboard nav-icon"></i>
                                                <p>Kardex</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 18) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Productos/TomaInv" class="nav-link">
                                                <i class="fas fa-clipboard nav-icon"></i>
                                                <p>Toma Inv.</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php $almacenMostrado = true;
                } elseif ($permiso['id_permiso_padre'] == 4 && !$bancoMostrado) { ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-university"></i>
                            <p>
                                Movimientos Caja
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 4) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 6) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Bancos/ingresos" class="nav-link">
                                                <i class="fas fa-arrow-alt-circle-up nav-icon"></i>
                                                <p>Ingresos</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 7) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Bancos/salidas" class="nav-link">
                                                <i class="fas fa-arrow-alt-circle-down nav-icon"></i>
                                                <p>Salidas</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 8) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Bancos/transferencias" class="nav-link">
                                                <i class="fas fa-exchange-alt nav-icon"></i>
                                                <p>Transferencias</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 13) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Bancos/movimientos" class="nav-link">
                                                <i class="fas fa-chart-line nav-icon"></i>
                                                <p>Movimientos Banco</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php $bancoMostrado = true;
                } elseif ($permiso['id_permiso_padre'] == 5 && $permiso['id_permiso_hijo'] == '') { ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url; ?>Carga" class="nav-link">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Invitados</p>
                        </a>
                    </li>
                <?php } elseif ($permiso['id_permiso_padre'] == 6 && !$productoMostrado) { ?>
                  <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                            Productos
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 6) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 14) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Productos" class="nav-link">
                                                <i class="fas fa-box nav-icon"></i>
                                                <p>Producto Nuevo</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 15) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Familia" class="nav-link">
                                                <i class="nav-icon fas fa-list-alt"></i>
                                                <p>Producto Familia</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php $productoMostrado = true;
                  } elseif ($permiso['id_permiso_padre'] == 7 && !$ingresoMostrado) { ?>
                  <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-dolly"></i>
                            <p>
                            Ingresos
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 7) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 9) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Compras" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Nueva Ingreso</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 10) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Compras/historial" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Historial de Ingreso</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php $ingresoMostrado = true;
                    } elseif ($permiso['id_permiso_padre'] == 8 && !$salidaMostrado) { ?>
                                      <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-truck-loading"></i>
                            <p>
                            Salidas
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 8) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 11) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Compras/ventas" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Nueva Salida</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if ($subpermiso['id_permiso_hijo'] == 12) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Compras/historial_ventas" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Historial de Salidas</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php 
                    $salidaMostrado = true;
                } elseif ($permiso['id_permiso_padre'] == 11 && !$facturacionMostrado) { ?>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                Fact. Electrónica
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php foreach ($_SESSION['permisos'] as $subpermiso) : ?>
                                <?php if ($subpermiso['id_permiso_padre'] == 11) : ?>
                                    <?php if ($subpermiso['id_permiso_hijo'] == 19) : ?>
                                        <li class="nav-item">
                                            <a href="<?php echo base_url; ?>Facturacion/guias_electronicas" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Guía Electrónica</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php 
                    $facturacionMostrado = true;
                    } elseif ($permiso['id_permiso_padre'] == 10 && $permiso['id_permiso_hijo'] == '') {?>
                      <li class="nav-item">
                          <a href="<?php echo base_url; ?>Contactos" class="nav-link">
                              <i class="nav-icon fas fa-address-book"></i>
                              <p>Contactos</p>
                          </a>
                      </li>
                    <?php } ?>
            <?php } ?>
        </ul>
      </nav>
    </div>
  </aside>
