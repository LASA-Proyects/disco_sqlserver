<?php include "Views/Templates/header.php" ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>
    <div class="col-md-12">

    <div class="row">
            <div class="col-lg-12">
                <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 style="float: left; margin: 0; font-size: 1.5rem; color: #343a40;">Pedidos</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                    <form id="exportForm" method="POST" target="_blank">
                            <div class="row">
                                <div class="col-md-2">
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
                                <div class="col-md-2">
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
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="min">Usuario</label>
                                        <select name="usuario" id="usuario" class="form-control productos" style="width: 100%;">
                                            <?php foreach ($data['usuarios'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                            <option value="-1">GENERAL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1" style="text-align: center;">
                                    <label for="min">Buscar</label>
                                    <button type="button" class="btn btn-primary btn-block" id="searchButton" onclick="buscarPedidosFecha()">
                                        <i class="fas fa-search"></i>   
                                    </button>
                                </div>
                                <div class="col-md-1" style="text-align: center;">
                                    <label for="min">Reportes</label>
                                    <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#reportModal">
                                        <i class="fas fa-file-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="reportModalLabel">Seleccionar Reporte</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-warning btn-block" id="pdfButton" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> RESUMEN VENTA
                                                    </button>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-secondary btn-block" id="pdfVentaDetallado" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-alt" style="margin-right: 8px;"></i> REPORTE VENTA DETALLADO
                                                    </button>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-success btn-block" id="excelButton" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-excel" style="margin-right: 8px;"></i> REPORTE EXCEL
                                                    </button>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-info btn-block" id="excelDetalladoButton" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-file-excel" style="margin-right: 8px;"></i> REPORTE EXCEL DETALLADO
                                                    </button>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button" class="btn btn-secondary btn-block" id="resumenGeneral" style="border-radius: 0; font-size: 1rem; font-weight: 600; text-transform: uppercase; padding: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.2)'" onmouseout="this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)'">
                                                        <i class="fas fa-chart-pie" style="margin-right: 8px;"></i> RESUMEN GENERAL
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
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            <div class="card-header p-2">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab" onclick="setValue(1)">PROCESADOS</a></li>
                <li class="nav-item"><a class="nav-link" href="#anulados" data-toggle="tab" onclick="setValue(2)">ANULADOS</a></li>
            </ul>
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="timeline">
                    <div class="post">
                        <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="fac_nofacf_proc">DIFERENCIA</label>
                                    <select name="fac_nofacf_proc" id="fac_nofacf_proc" class="form-control buscador" style="width: 100%;" data-index="3">
                                        <option disabled selected></option>
                                        <option value="1">FACTURADOS</option>
                                        <option value="2">NO FACTURADOS</option>
                                        <option value="">TODOS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="bol_fac_proc">MOVIMIENTO</label>
                                    <select name="bol_fac_proc" id="bol_fac_proc" class="form-control buscador" style="width: 100%;" data-index="3">
                                        <option disabled selected></option>
                                        <option value="1">BOLETA</option>
                                        <option value="2">FACTURAS</option>
                                        <option value="">TODOS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm" id="tblPedidosPagados" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>N° Ped.</th>
                                        <th>Almacen</th>
                                        <th>Fecha</th>
                                        <th>Dni/Ruc</th>
                                        <th>Nombre/Razon</th>
                                        <th>Asiento Cont.</th>
                                        <th>Tipo Doc.</th>
                                        <th>Serie</th>
                                        <th>Numer</th>
                                        <th>Sub Total</th>
                                        <th>Estado</th>
                                        <th>Estado Sunat</th>
                                        <th></th>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody id="tblPagosJs"></tbody>
                        </table>
                        </div>
                    </div>
                  </div>
                  <div class="tab-pane" id="anulados">
                    <div class="post">
                        <div class="table-responsive">
                        <table class="table table-sm" id="tblPedidosAnulados" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>N° Ped.</th>
                                        <th>Almacen</th>
                                        <th>Fecha</th>
                                        <th>Dni/Ruc</th>
                                        <th>Nombre/Razon</th>
                                        <th>Asiento Cont.</th>
                                        <th>Tipo Doc.</th>
                                        <th>Serie</th>
                                        <th>Numer</th>
                                        <th>Sub Total</th>
                                        <th>Estado</th>
                                        <th>Estado Sunat</th>
                                        <th></th>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody id="tblPagosAnuladosJS"></tbody>
                        </table>
                        </div>
                    </div>
                  </div>
                </div>
                <div id="obtenerDetallePedidos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="title">Detalle Pedido</h5>
                                    <button class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal_consulta_sunat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Estado de Documento</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmFechaPermi">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Código</label>
                                            <input id="codigo_doc" class="form-control" type="text" name="codigo_doc" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea class="form-control" rows="3" id="descripcion_doc" name="descripcion_doc" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Hash</label>
                                            <input id="hash_doc" class="form-control" type="text" name="hash_doc" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div id="qr_container"></div> <!-- Contenedor para el QR -->
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cerrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <div id="modalProcesarPedido" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <form method="post" id="frmPedidoFacturado">
                                        <div class="modal-body text-center">
                                            <input type="hidden" id="cambio" name="cambio">
                                            <input type="hidden" id="id_pedido" name = "id_pedido">
                                            <div style="font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 10px; color: black;">EDITAR PEDIDO</div>
                                            <p style="font-size: 18px; font-weight: bold; color: #333; text-align: center;">
                                                PEDIDO N° <span id="pedido_numero" style="font-size: 20px; color: #0066cc;"></span>
                                            </p>
                                            <input type="hidden" id="parametro" name="parametro">
                                        </div>
                                        <div class="row justify-content-center text-center">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" type="checkbox" id="mostrarEfectivo" onclick="toggleCampoEntrada('mostrarEfectivo', 'efectivo', 'op_efect')">
                                                    <label for="mostrarEfectivo" class="custom-control-label">
                                                        <i class="fas fa-money-bill fa-2x"></i><br>Pago Efectivo
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" type="checkbox" id="mostrarPos" onclick="toggleCampoEntrada('mostrarPos', 'pos', 'op_pos')">
                                                    <label for="mostrarPos" class="custom-control-label">
                                                        <i class="fas fa-credit-card fa-2x"></i><br>Pago por Pos
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-3">
                                                    <input class="custom-control-input" type="checkbox" id="mostrarTransferencia" onclick="toggleCampoEntrada('mostrarTransferencia', 'transferencia', 'op_transf')">
                                                    <label for="mostrarTransferencia" class="custom-control-label">
                                                        <i class="fas fa-exchange-alt fa-2x"></i><br>Transferencia
                                                    </label>
                                                </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarVisa" onclick="toggleCampoEntrada('mostrarVisa', 'visa', 'op_visa')">
                                                        <label for="mostrarVisa" class="custom-control-label"><i class="fa fa-cc-visa fa-2x"></i></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarMaster" onclick="toggleCampoEntrada('mostrarMaster', 'master_c', 'op_mast')">
                                                        <label for="mostrarMaster" class="custom-control-label"><i class="fa fa-cc-mastercard fa-2x"></i></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarDiners" onclick="toggleCampoEntrada('mostrarDiners', 'diners', 'op_diners')">
                                                        <label for="mostrarDiners" class="custom-control-label"><i class="fa fa-cc-diners-club fa-2x"></i></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarExpress" onclick="toggleCampoEntrada('mostrarExpress', 'a_express', 'op_express')">
                                                        <label for="mostrarExpress" class="custom-control-label"><i class="fa fa-cc-amex fa-2x"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarYape" onclick="toggleCampoEntrada('mostrarYape', 'yape', 'op_yape')">
                                                        <label for="mostrarYape" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/yape.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarPlin" onclick="toggleCampoEntrada('mostrarPlin', 'plin', 'op_plin')">
                                                        <label for="mostrarPlin" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/plin.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
                                                        <input class="custom-control-input" type="checkbox" id="mostrarizipay" onclick="toggleCampoEntrada('mostrarizipay', 'izipay', 'op_izipay')">
                                                        <label for="mostrarizipay" class="custom-control-label"><img src="<?php echo base_url; ?>Assets/img/izipay.jpg" class="yape-icon" style="width: 32px; height: 32px;"></label>
                                                    </div>
                                                    <div class="custom-control custom-checkbox d-none">
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
                                                    <div class="form-group">
                                                        <input id="pos" class="form-control d-none" type="number" name="pos" placeholder="Pos">
                                                    </div>
                                                    <div class="form-group">
                                                        <input id="transferencia" class="form-control d-none" type="number" name="transferencia" placeholder="Transferencia">
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
                                                    <div class="form-group">
                                                        <input id="op_pos" class="form-control d-none" type="text" name="op_pos">
                                                    </div>
                                                    <div class="form-group">
                                                        <input id="op_transf" class="form-control d-none" type="text" name="op_transf">
                                                    </div>
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
                                        <div class="modal-footer">
                                            <button id="btnAccion" class="btn btn-primary" type="button" onclick="registrarPedidoFacturado(event);">Registrar</button>
                                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php include "Views/Templates/footer.php"?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/pedidos_index.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/moment.min.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/daterangepicker.js"></script>