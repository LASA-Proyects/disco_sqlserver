<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 style="float: left; margin: 0; font-size: 1.5rem; color: #343a40;">Movimientos Bancarios</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <form method="post" id="frmBuscarKardexBanco">
                    <div class="row">
                        <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_desde">Desde</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="date" class="form-control float-right" id="fecha_desde" name="fecha_desde" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fecha_hasta">Hasta</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="date" class="form-control float-right" id="fecha_hasta" name="fecha_hasta" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="banco">Cuenta Bancos</label>
                                <select class="form-control" id="banco" name="banco">
                                    <option disabled selected style="display:none;"></option>
                                    <?php foreach ($data['bancos'] as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?> | <?php echo $row['cuenta']; ?> | <?php echo $row['cuenta_contable']; ?></option>
                                    <?php } ?>
                                    <option value="-1">TODOS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="promotor">Buscar</label>
                                <button class="btn btn-primary btn-block" type="button" onclick="buscarKardexBancos(event)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-1" style="text-align: center;">
                            <label for="min">Reportes</label>
                            <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#reportModalMovimientos">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                        <div class="modal fade" id="reportModalMovimientos" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reportModalMovimientosLabel">Seleccionar Reporte</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-warning btn-block" onclick="generarPDF()" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> Generar Pdf
                                                    </button>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-success btn-block" onclick="generarExcel()" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-excel" style="margin-right: 8px;"></i> Generar Excel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="font-size: 1rem;">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="tblMovimientoBancosGEN" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                            <thead>
                                <tr></tr>
                                    <th>Fecha</th>
                                    <th>Id</th>
                                    <th>Usuario</th>
                                    <th>Glosa</th>
                                    <th>Operación</th>
                                    <th>Cuenta Banco</th>
                                    <th>Número De Operación</th>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody id="tblMovimientoBancos"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/banco_ingreso.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>