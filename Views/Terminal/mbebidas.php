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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>BEBIDAS</h1>
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
        <div class="row mb-3">
            <div class="col-lg-3">
                <a href="<?php echo base_url; ?>Terminal/index" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a type="button" onclick="consultarValidacion(4, 'Pedidos/familias/');" class="card bg-primary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                                <h3>REALIZAR PEDIDO VENTA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a type="button" onclick="consultarValidacion(5, 'Pedidos/bebidas_combo/');" class="card bg-secondary text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-glass-martini-alt fa-3x"></i>
                                <h3>REALIZAR PEDIDO COMBOS</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a type="button" onclick="consultarValidacion(7, 'Pedidos/bebidas_cortesia/');" class="card bg-warning text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-gift fa-3x"></i>
                                <h3>REALIZAR PEDIDO CORTESIA</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a type="button" onclick="consultarValidacion(8, 'Pedidos/bebidas_descuento/');" class="card bg-success text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-user fa-3x"></i>
                                <h3>DESCUENTO A TRABAJADOR</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a type="button" onclick="consultarValidacion(6, 'Pedidos/bebidas_representante/');" class="card bg-danger text-decoration-none" style="display: block; text-align: center; padding: 20px; border-radius: 10px;">
                            <div class="card-body">
                                <i class="fas fa-money-bill fa-3x"></i>
                                <h3>GASTOS REPRESENTACIÓN</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div id="solicitarClave" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">VERIFICAR CONTRASEÑA</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmVerificarContrasena">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="usuario">Linea</label>
                                        <select name="usuario" id="usuario" class="form-control" style="width: 100%;">
                                            <?php foreach ($data['usuarios'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="clave">INGRESE CONTRASEÑA</label>
                                        <input id="clave" class="form-control" type="password" name="clave" placeholder="Contraseña">
                                    </div>
                                    <button class="btn btn-primary" type="button" onclick="verificarContrasena(event);" id="btnAccion">Verificar</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
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