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
        <a href="<?php echo base_url; ?>Terminal/malmacen" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Regresar
        </a>
        <div class="card-body">
            <div class="row justify-content-center align-items-center">
                <div class="col-auto">
                    <div class="form-group">
                        <label for="fecha_ingreso">INGRESAR FECHA RETORNO:</label>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-group">
                        <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d')?>">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <form id="exportForm" method="post">
                    <input type="hidden" id="titulo" name="titulo" value="">
                    <input type="hidden" id="cabeceras" name="cabeceras" value="">
                    <input type="hidden" id="contenido" name="contenido" value="">
                    <div class="col-md-2 px-0">
                        <button type="button" class="btn btn-success btn-block" id="excelDetalladoButton">
                            <i class="far fa-file-excel"></i> Exportar a Excel
                        </button>
                    </div>
                </form>
                <table class="table table-sm" id="tblProductosStock" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Foto</th>
                            <th>Descripción</th>
                            <th>Almacen</th>
                            <th>Stock</th>
                            <th>Unidad</th>
                            <th>Seleccionar</th>
                            <th>Cantidad</th>
                            <th>Observación</th>
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