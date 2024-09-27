<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>NUEVO INGRESO</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="modal-header bg-primary d-flex justify-content-center py-1">
                            <h5 class="modal-title text-white text-center" id="title">DATOS DE OPERACIÓN</h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmInfoBancoIgreso">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="hidden" id="banco_tipo" name="banco_tipo"  value="1">
                                        <div class="form-group">
                                            <label>BANCOS</label>
                                            <select name="banco" id="banco" class="form-control" style="width: 100%;">
                                                <option disabled selected style="display:none;"></option>
                                                <?php foreach ($data['bancos'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?> | <?php echo $row['cuenta']; ?> | <?php echo $row['cuenta_contable']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>FECHA</label>
                                            <?php
                                            date_default_timezone_set('America/Lima');
                                            ?>
                                            <input class="form-control" type="date" name="fecha" id="fecha" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>NÚMERO DE OPERACIÓN</label>
                                            <input type="text" name="numero_operacion" id="numero_operacion" class="form-control" placeholder="Ingrese el número de operación">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>MONTO INGRESO</label>
                                            <input type="number" name="monto_ingreso" id="monto_ingreso" class="form-control" placeholder="Ingrese el monto">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>DENOMINACIÓN DE BILLETAS</label>
                                            <button type="button" class="btn btn-primary form-control" onclick="agregarDenomBilletes()">Agregar Denominación</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>GLOSA</label>
                                            <select name="glosa" id="glosa" class="form-control" style="width: 100%;">
                                                <option disabled selected style="display:none;"></option>
                                                <?php foreach ($data['glosas'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" onclick="registrarIngresoBanco()">Enviar</button>
                                    </div>
                                </div>

                                <div id="agregar_denominacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title text-white" id="title">Denominaciones</h5>
                                                <button class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="row text-center" id="campos_billetes">
                                                        <div class="col-md-3">
                                                            <label id="200l">200</label>
                                                            <input id="b_200" class="form-control" type="text" name="b_200">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label id="100l">100</label>
                                                            <input id="b_100" class="form-control" type="text" name="b_100">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="50l">50</label>
                                                            <input id="b_50" class="form-control" type="text" name="b_50">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="20l">20</label>
                                                            <input id="b_20" class="form-control" type="text" name="b_20">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="10l">10</label>
                                                            <input id="b_10" class="form-control" type="text" name="b_10">
                                                        </div>
                                                    </div>
                                                    <div class="row text-center" id="campos_monedas">
                                                        <div class="col-md-2">
                                                            <label id="5l">5</label>
                                                            <input id="m_5" class="form-control" type="text" name="m_5">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="2l">2</label>
                                                            <input id="m_2" class="form-control" type="text" name="m_2">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="1l">1</label>
                                                            <input id="m_1" class="form-control" type="text" name="m_1">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="050l">0.50</label>
                                                            <input id="m_050" class="form-control" type="text" name="m_050">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="020l">0.20</label>
                                                            <input id="m_020" class="form-control" type="text" name="m_020">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label id="010l">0.10</label>
                                                            <input id="m_010" class="form-control" type="text" name="m_010">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <h3 id="total_dinero" style="color: blue; background-color: lightgray; padding: 10px; font-size: 24px;">TOTAL: S/. 0.00</h3>
                                                    </div>
                                                </div>
                                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
            <?php include "Views/Templates/footer.php" ?>
            <script>
                const base_url = "<?php echo base_url; ?>"
            </script>
            <script src="<?php echo base_url; ?>Assets/js/banco_ingreso.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>