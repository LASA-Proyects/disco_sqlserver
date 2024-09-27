<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Cocina</title>
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/adminlte.min.css">
</head>

<?php include "Views/Templates/header_pos.php" ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 style="color: #2c3e50;">ALMACEN</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" style="color: #ff5733;">Almacen</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-3">
                <a href="<?php echo base_url; ?>Terminal/index" class="btn btn-secondary" style="background-color: #6c757d; color: #ffffff;">
                    <i class="fas fa-undo"></i> Regresar
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Arqueo/retorno_almacen" class="card bg-info text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-exchange-alt fa-3x" style="color: #ffffff;"></i>
                                <h3 style="color: #ffffff;">RETORNO</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Kardex/kardex_terminal" class="card bg-secondary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-list-alt fa-3x" style="color: #ffffff;"></i>
                                <h3 style="color: #ffffff;">KARDEX</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="<?php echo base_url; ?>Productos/stock_terminal" class="card bg-primary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-boxes fa-3x" style="color: #ffffff;"></i>
                                <h3 style="color: #ffffff;">STOCK</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>const base_url = "<?php  echo base_url;?>";</script>
<script src="<?php echo base_url;?>Assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/alertas.js"></script>
<script src="<?php echo base_url;?>Assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/adminlte.min.js"></script>
<script src="<?php echo base_url;?>Assets/js/consulta_validacion.js"></script>
</body>
</html>