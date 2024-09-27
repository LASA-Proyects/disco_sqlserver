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
                <h1>MENÚ PRINCIPAL</h1>
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
                    <!---<div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Terminal/mboleteria" class="card bg-info text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                            <i class="fas fa-ticket-alt fa-3x"></i>
                                <h3>BOLETERIA</h3>
                            </div>
                        </a>
                    </div>-->

                    <div class="col-lg-3 col-md-6 mb-3 d-none" id="babidas_boton">
                        <a href="<?php echo base_url; ?>Terminal/mbebidas" class="card bg-success text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-glass-martini-alt fa-3x"></i>
                                <h3>BEBIDAS</h3>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3 d-none" id="cocteleria_boton">
                        <a href="<?php echo base_url; ?>Terminal/mcocteleria" class="card bg-warning text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-cocktail fa-3x"></i>
                                <h3>COCTELERIA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3 d-none" id="cocina_boton">
                        <a href="<?php echo base_url; ?>Terminal/mcocina" class="card bg-danger text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-utensils fa-3x"></i>
                                <h3>COCINA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Terminal/malmacen" class="card bg-secondary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-warehouse fa-3x"></i>
                                <h3>ALMACEN</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Terminal/mbancos" class="card bg-info text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-university fa-3x"></i>
                                <h3>CAJA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Pedidos/historial_pedidos_terminal" class="card bg-dark text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-history fa-3x"></i>
                                <h3>HISTORIAL VENTAS</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>const base_url = "<?php  echo base_url;?>";</script>
<script src="<?php echo base_url;?>Assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/adminlte.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/alertas.js"></script>
<script src="<?php echo base_url;?>Assets/js/consultaStockCaja.js"></script>
</body>
</html>