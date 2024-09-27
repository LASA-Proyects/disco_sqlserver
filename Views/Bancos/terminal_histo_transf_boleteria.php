<?php include "Views/Templates/header_pos.php" ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>KARDEX</h1>
                </div>
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
            <div class="card shadow mb-4">
                <div class="card-header">
                <form method="post" id="frmBuscarKardexBanco">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hasta_fecha">FECHA HASTA</label>
                                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="banco">CUENTA BANCO</label>
                                <select class="form-control" id="banco" name="banco">
                                    <option disabled selected style="display:none;"></option>
                                    <?php foreach ($data['bancos'] as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?> | <?php echo $row['cuenta']; ?> | <?php echo $row['cuenta_contable']; ?></option>
                                    <?php } ?>
                                    <?php if ($_SESSION['id_usuario'] == 1) { ?>
                                        <option value="-1">TODOS</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="promotor">BUSCAR</label>
                                <button class="btn btn-success btn-block" type="button" onclick="buscarKardexBancos(event)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>GENERAR PDF</label>
                                <button type="button" class="btn btn-warning btn-block" onclick="generarPDF()">GENERAR PDF</button>
                                <button type="button" class="btn btn-success btn-block" onclick="generarExcel()">GENERAR EXCEL</button>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                            <thead>
                                <tr></tr>
                                    <th>FECHA</th>
                                    <th>OPERACIÓN</th>
                                    <th>CUENTA BANCO</th>
                                    <th>NÚMERO DE OPERACIÓN</th>
                                    <th>INGRESO</th>
                                    <th>SALIDA</th>
                                    <th>SALDO</th>
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