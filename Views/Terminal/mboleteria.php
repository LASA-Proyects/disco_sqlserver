<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Buttons</title>
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/adminlte.min.css">
</head>

<?php include "Views/Templates/header_pos.php" ?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>BOLETERÍA</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Buttons</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Entradas/normal" class="card bg-primary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                                <h3>VENTA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Carga/carga_asist" class="card bg-info text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-users fa-3x"></i>
                                <h3>PROMOTORES</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Pedidos/historial_venta_entrada" class="card bg-success text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-history fa-3x"></i>
                                <h3>HISTORIAL DE VENTAS</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Bancos/terminal_transferencias_boleteria" class="card bg-warning text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-cash-register fa-3x"></i>
                                <h3>CAJA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Bancos/terminal_histo_transf_boleteria" class="card bg-danger text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-file-invoice-dollar fa-3x"></i>
                                <h3>HISTORIAL CAJA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Arqueo/retorno_almacen_boleteria" class="card bg-secondary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-warehouse fa-3x"></i>
                                <h3>ALMACEN</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo base_url;?>Assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/adminlte.min.js"></script>
</body>
</html>