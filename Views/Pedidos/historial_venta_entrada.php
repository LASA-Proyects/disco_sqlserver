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
                    <h1>VENTA DE ENTRADAS</h1>
                </div>
            </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-lg-3">
                    <a href="<?php echo base_url; ?>Terminal/mboleteria" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
            <form id="entradasExport" method="POST" target="_blank">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="desde">DESDE</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control float-right" id="desde" name="desde" value="<?php date_default_timezone_set('America/Lima'); echo  date('Y-m-d')?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hasta">HASTA</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control float-right" id="hasta" name="hasta" value="<?php  date_default_timezone_set('America/Lima'); echo  date('Y-m-d')?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>REPORTE</label>
                        <button type="button" class="btn btn-warning btn-block" id="pdfEntrada">GENERAR RESUMEN</button>
                    </div>
                </form>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm" id="tblHistorialEntradas" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Almacen</th>
                                        <th>Documento</th>
                                        <th>Serie</th>
                                        <th>Correlativo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/historial_entradas.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>

