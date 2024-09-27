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
                    <a href="<?php echo base_url; ?>Terminal/mbancos" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header">
                <form method="post" id="frmBuscarKardexBanco">
                    <div class="row">
                    <div class="col-md-2">
                            <div class="form-group">
                                <label for="hasta_fecha">FECHA DESDE</label>
                                <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?php date_default_timezone_set('America/Lima'); echo  date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hasta_fecha">FECHA HASTA</label>
                                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?php date_default_timezone_set('America/Lima'); echo  date('Y-m-d')?>">
                            </div>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="promotor">BUSCAR</label>
                                <button class="btn btn-success btn-block" type="button" onclick="buscarKardexBancos(event)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group" id="boton_kardex_sin_validar">
                                <label for="filtro_kardex">KARDEX</label>
                                <button type="button" class="btn btn-success btn-block" onclick="validarKardex()">
                                    <i class="fas fa-check-circle"></i> Validar
                                </button>
                            </div>
                            <div class="form-group d-none" id="boton_kardex_validado">
                                <label for="filtro_kardex_validado">KARDEX</label>
                                <button type="button" class="btn btn-secondary btn-block" disabled>
                                    <i class="fas fa-check-circle"></i> Validado
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tblMovimientoBancosGEN" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                            <thead>
                                <tr></tr>
                                    <th>FECHA</th>
                                    <th>ID</th>
                                    <th>USUARIO</th>
                                    <th>GLOSA</th>
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
            <script src="<?php echo base_url; ?>Assets/js/validar_kardex.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>