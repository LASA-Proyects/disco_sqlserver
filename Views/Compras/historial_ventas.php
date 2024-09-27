<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Historial de Salidas</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form id="buscarInfo" method="POST" target="_blank">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="min">Desde</label>
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
                                            <label for="min">Hasta</label>
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
                                        <div class="form-group">
                                            <label>Acción</label>
                                            <button type="button" class="btn btn-primary btn-block" id="searchButton" onclick="buscarVentasFecha()">
                                                <i class="fas fa-search"></i> Buscar por Fecha
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                            <table class="table table-sm" id="tblHistorialVentas" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>OPERACIÓN</th>
                                        <th>TOTAL</th>
                                        <th>FECHA</th>
                                        <th>FECHA OPERACIÓN</th>
                                        <th>ESTADO</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tblHistorialVentasJs"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>
                const base_url = "<?php echo base_url; ?>"
            </script>
            <script src="<?php echo base_url; ?>Assets/js/venta.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/historial_ventas.js"></script>