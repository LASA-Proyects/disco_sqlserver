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
                <h1>RETORNO DE PRODUCTOS</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <a href="<?php echo base_url; ?>Terminal/mboleteria" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="tblProductosStock" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Foto</th>
                            <th>Descripción</th>
                            <th>Almacen</th>
                            <th>Stock</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <button id="btnAccion" class="btn btn-primary" type="button" onclick="generarSalida(event);">Registrar</button>
            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
        </div>
    </div>
</section>

<?php include "Views/Templates/footer.php" ?>

<script>
    const base_url = "<?php echo base_url; ?>"
</script>

<script src="<?php echo base_url; ?>Assets/js/arqueo_caja.js"></script>
<script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
</body>
</html>