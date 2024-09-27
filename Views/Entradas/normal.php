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
                <h1>VENTA DE ENTRADAS</h1>
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
            <div class="modal-body">
                <form id="registroEntradaForm">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" id="cambio" name="cambio">
                                <label>SELECCIONE DOCUMENTO</label>
                                <select name="tipo_doc" id="tipo_doc" class="form-control" style="width: 100%;">
                                    <option value="" disabled selected></option>
                                    <?php foreach ($data['tipo_docs'] as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <input type="hidden" id="parametro" name="parametro">
                                <div class="col-md-12">
                                    <label>FECHA</label>
                                    <?php
                                    date_default_timezone_set('America/Lima');
                                    ?>
                                    <input class="form-control" type="date" name="fecha_oper" id="fecha_oper" value="<?= date('Y-m-d'); ?>">
                                </div>
                                <div class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label for="serie">SERIE</label>
                                        <input type="text" class="form-control" id="serie_doc" name="serie_doc" placeholder="Serie" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label for="correlativo">CORRELATIVO</label>
                                        <input type="number" class="form-control" id="correl_doc" name="correl_doc" placeholder="Correlativo" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="correlativo">TIPO DE CAMBIO</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="tipo_cambio" name="tipo_cambio" placeholder="T. Cambio">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="consultaTipoCambio(event);"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 d-none" id="campos_dni">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="dni" name="dni" placeholder="DNI">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="consultaDNI(event);" id="btnDNI">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none" id="campos_ruc">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="number" class="form-control" id="ruc" name="ruc" placeholder="RUC">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="consultaRUC(event);" id="btnRUC">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="campo_nombre">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" id="nombre_cli" name="nombre_cli" placeholder="Nombre">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label>Género</label>
                                <select class="form-control" id="genero" name="genero">
                                    <option value="MASCULINO">Masculino</option>
                                    <option value="FEMENINO">Femenino</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>SELECCIONE TIPO ENTRADA</label>
                                    <select name="tipo_entrada" id="tipo_entrada" class="form-control" style="width: 100%;" onchange="buscarEnBD(this.value)">
                                        <option value="" disabled selected></option>
                                        <?php foreach ($data['tipo_entradas'] as $row) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>CANTIDAD</label>
                                    <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center text-center">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarEfectivo" onclick="toggleCampoEntrada('mostrarEfectivo', 'efectivo', 'op_efect')">
                                    <label for="mostrarEfectivo" class="custom-control-label"><i class="fas fa-money-bill fa-2x"></i></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarVisa" onclick="toggleCampoEntrada('mostrarVisa', 'visa', 'op_visa')">
                                    <label for="mostrarVisa" class="custom-control-label"><i class="fa fa-cc-visa fa-2x"></i></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarMaster" onclick="toggleCampoEntrada('mostrarMaster', 'master_c', 'op_mast')">
                                    <label for="mostrarMaster" class="custom-control-label"><i class="fa fa-cc-mastercard fa-2x"></i></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarDiners" onclick="toggleCampoEntrada('mostrarDiners', 'diners', 'op_diners')">
                                    <label for="mostrarDiners" class="custom-control-label"><i class="fa fa-cc-diners-club fa-2x"></i></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarExpress" onclick="toggleCampoEntrada('mostrarExpress', 'a_express', 'op_express')">
                                    <label for="mostrarExpress" class="custom-control-label"><i class="fa fa-cc-amex fa-2x"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarYape" onclick="toggleCampoEntrada('mostrarYape', 'yape', 'op_yape')">
                                    <label for="mostrarYape" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/yape.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarPlin" onclick="toggleCampoEntrada('mostrarPlin', 'plin', 'op_plin')">
                                    <label for="mostrarPlin" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/plin.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarizipay" onclick="toggleCampoEntrada('mostrarizipay', 'izipay', 'op_izipay')">
                                    <label for="mostrarizipay" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/izipay.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="mostrarniubiz" onclick="toggleCampoEntrada('mostrarniubiz', 'niubiz', 'op_niubiz')">
                                    <label for="mostrarniubiz" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/niubiz.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row justify-content-center text-center">
                            <div class="col-md-6">
                                <label>Pagos</label>
                                <div class="form-group">
                                    <input id="efectivo" class="form-control d-none" type="number" name="efectivo" placeholder="Efectivo">
                                </div>
                                <div class="form-group">
                                    <input id="visa" class="form-control d-none" type="number" name="visa" placeholder="Visa">
                                </div>
                                <div class="form-group">
                                    <input id="master_c" class="form-control d-none" type="number" name="master_c" placeholder="Master">
                                </div>
                                <div class="form-group">
                                    <input id="diners" class="form-control d-none" type="number" name="diners" placeholder="Diners">
                                </div>
                                <div class="form-group">
                                    <input id="a_express" class="form-control d-none" type="number" name="a_express" placeholder="A. Express">
                                </div>
                                <div class="form-group">
                                    <input id="cortesia" class="form-control d-none" type="number" name="cortesia" placeholder="Cortesia">
                                </div>
                                <div class="form-group">
                                    <input id="yape" class="form-control d-none" type="number" name="yape" placeholder="Yape">
                                </div>
                                <div class="form-group">
                                    <input id="plin" class="form-control d-none" type="number" name="plin" placeholder="Plin">
                                </div>
                                <div class="form-group">
                                    <input id="izipay" class="form-control d-none" type="number" name="izipay" placeholder="Izipay">
                                </div>
                                <div class="form-group">
                                    <input id="niubiz" class="form-control d-none" type="number" name="niubiz" placeholder="Niubiz">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Operación</label>
                                <div class="form-group">
                                    <input id="op_efect" class="form-control d-none" type="text" name="op_efect" disabled>
                                </div>
                                <div class="form-group">
                                    <input id="op_visa" class="form-control d-none" type="text" name="op_visa">
                                </div>
                                <div class="form-group">
                                    <input id="op_mast" class="form-control d-none" type="text" name="op_mast">
                                </div>
                                <div class="form-group">
                                    <input id="op_diners" class="form-control d-none" type="text" name="op_diners">
                                </div>
                                <div class="form-group">
                                    <input id="op_express" class="form-control d-none" type="text" name="op_express">
                                </div>
                                <div class="form-group">
                                    <input id="op_yape" class="form-control d-none" type="text" name="op_yape">
                                </div>
                                <div class="form-group">
                                    <input id="op_plin" class="form-control d-none" type="text" name="op_plin">
                                </div>
                                <div class="form-group">
                                    <input id="op_izipay" class="form-control d-none" type="text" name="op_izipay">
                                </div>
                                <div class="form-group">
                                    <input id="op_niubiz" class="form-control d-none" type="text" name="op_niubiz">
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-between">
                                <div class="text-left">
                                    <p><strong>MONTO ACTUAL:</strong></p>
                                    <p id="dineroActual" style="font-size: 40px; color: #004080;"><strong>S/. 0.00</strong></p>
                                </div>
                                <div class="text-left">
                                    <p><strong>FALTA:</strong></p>
                                    <p id="falta" style="font-size: 40px; color: #FF5733;"><strong>S/. 0.00</strong></p>
                                </div>
                                <div class="text-left">
                                    <p><strong>VUELTO:</strong></p>
                                    <p id="vuelto" style="font-size: 40px; color: #800000;"><strong>S/. 0.00</strong></p>
                                </div>
                                <div class="text-right">
                                    <input type="hidden" name="tipo_pedido" id="tipo_pedido" value="1">
                                    <input type="hidden" id="total_pedido" name="total_pedido">
                                    <input type="hidden" id="total_igv" name="total_igv">
                                    <input type="hidden" id="total_base" name="total_base">
                                    <p><strong>Sub Total:</strong> <span id="subtotal"></span></p>
                                    <p><strong>IGV:</strong> <span id="igv"></span></p>
                                    <p><strong>Total:</strong> <span id="total_venta"></span></p>
                                </div>
                            </div>
                        </div>
                </form>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" onclick="regEntrNorml(event);">Registrar</button>
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                </div>
                <div id="loader" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%;">
                    <div id="loader-background" style="position: absolute; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
                    <div id="loader-icon" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <i class="fa fa-spinner fa-spin fa-3x" style="color: white;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "Views/Templates/footer.php" ?>
<script>const base_url = "<?php  echo base_url;?>";</script>
<script src="<?php echo base_url; ?>Assets/js/entradas.js"></script>
<script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>