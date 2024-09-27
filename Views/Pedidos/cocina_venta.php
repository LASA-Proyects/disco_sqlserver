<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/all.min.css">
<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/adminlte.min.css">
<?php include "Views/Templates/header_pos.php" ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <section class="content">
        <div class="container-fluid">
        <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-block" type="button" onclick="modalBusqueda();">Buscar Producto</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card-body">
                    <a href="<?php echo base_url; ?>Terminal/mcocina" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                        <?php foreach ($data['familia'] as $familia) { ?>
                        <a href="<?php echo base_url.'Pedidos/cocina_venta/'.$familia['id'];?>" class="btn">
                        <h6 class="text-center"><?php echo $familia['nombre'];?></h6>
                            <img class="img-thumbnail" src="<?php echo base_url."Assets/img/".$familia['foto']?>" width="90">
                        </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                    <?php foreach ($data['productos'] as $producto) { ?>
                        <div class="col-md-4">
                            <div class="position-relative">
                                <?php if ($producto['stock'] == 0) { ?>
                                    <div class="position-absolute ribbon-wrapper">
                                        <div class="ribbon bg-danger">
                                            AGOTADO
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="card">
                                    <span class="stock-quantity badge badge-info"><?php echo $producto['stock']; ?></span>
                                    <button class="btn btn-primary rounded-circle btn-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-top: 3px; margin-left: 2px;" onclick="btnVerStockProducto(<?php echo $producto['id_producto']; ?>)">
                                        <i class="fas fa-exclamation" style="font-size: 16px;"></i>
                                    </button>
                                    <div class="card-header">
                                        <img src="<?php echo base_url . "Assets/img/" . $producto['foto']; ?>" class="card-img-top" alt="<?php echo $producto['descripcion']; ?>" style="max-height: 300px; width: 100%; object-fit: cover;">
                                    </div>
                                    <div class="card-body d-flex flex-column" style="height: 130px;">
                                        <h5 class="card-title text-center my-auto"><?php echo $producto['descripcion']; ?></h5>
                                        <h5 class="card-text text-center my-auto">S/. <?php echo $producto['precio_venta']; ?></h5>
                                    </div>
                                    <form id="frmPedidoIngr_<?php echo $producto['id_producto']; ?>">
                                        <div class="card-footer">
                                            <input type="hidden" name="id_product" id="id_product" value="<?php echo $producto['id_producto']; ?>">
                                            <input type="hidden" name="t_pedido" id="t_pedido" value="1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <button class="btn btn-danger btn-sm btnDisminuirCantidad"><i class="fas fa-minus"></i></button>
                                                    <input type="text" class="quantity" value="1" style="width: 50px; padding: 5px; text-align: center; border: 1px solid #ccc;">
                                                    <button class="btn btn-success btn-sm btnAumentarCantidad"><i class="fas fa-plus"></i></button>
                                                </div>
                                            <div class="d-flex justify-content-end mt-2">
                                            <a class="btn btn-info flex-fill btnAddCarrito <?php echo ($producto['stock'] == 0) ? 'd-none' : ''; ?>" prod="<?php echo $producto['id_producto']; ?>">
                                                <i class="fa fa-cart-plus"></i>
                                            </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                        <div class="mailbox-controls">
                            <div class="float-right">
                                <div class="btn-group">
                                    <?php 
                                        $anterior = $data['pagina'] - 1;
                                        $siguiente = $data['pagina'] + 1;
                                        $url = base_url.'Pedidos/cocina_venta/'.$data['id_familia'];
                                        
                                        if ($data['pagina'] > 1) {
                                            echo '<a class="btn btn-sm btn-primary" href="'.$url.'/'.$anterior.'">
                                                <i class="fas fa-chevron-left"></i> Anterior
                                            </a>';
                                        }
                                        
                                        if ($data['total'] >= $siguiente) {
                                            echo '<a class="btn btn-sm btn-primary" href="'.$url.'/'.$siguiente.'">
                                                Siguiente <i class="fas fa-chevron-right"></i>
                                            </a>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-striped table-striped table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tblListaCarrito">
                            </tbody>
                        </table>
                    </div>
                    <form id="frmInfoPed">
                        <div class="text-center mt-3">
                            <button class="btn btn-primary btn-block" type="button" onclick="verificarPedido();" id="btnProcesarPedido">PROCESAR</button>
                        </div>
                        <div class="text-center mt-3">
                            <input type="hidden" id="total" name="total">
                            <h3 id="totalGeneral" style="color: blue; background-color: lightgray; padding: 10px; font-size: 24px;">S/0.00</h3>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-danger btn-block" type="button" onclick="cancelarOrden();" id="btnCancelarOrden">CANCELAR ORDEN</button>
                        </div>
                    </form>
                </div>
                <div id="StockPorProducto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="border-radius: 20px;">
                            <div class="modal-header bg-primary" style="border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title text-white" id="ProductName" style="margin-left: 15px;"><i class="fas fa-shopping-cart"></i></h5>
                                <button class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead style="background-color: #f0f0f0;">
                                            <tr>
                                                <th style="text-align: center;">ALMACEN</th>
                                                <th style="text-align: center;">STOCK</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblStockPorProductos" style="text-align: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="obtenerDetallePedidos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="title">Detalle Pedido</h5>
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
                                            <p id="pedido_numero" style="font-size: 18px;"></p>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <label>SELECCIONE DOCUMENTO</label>
                                                    <select name="t_documento" id="t_documento" class="form-control" style="width: 100%;">
                                                        <option value="" disabled selected></option>
                                                        <?php foreach ($data['tipo_documentos'] as $row) { ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>FECHA</label>
                                                    <?php
                                                    date_default_timezone_set('America/Lima');
                                                    ?>
                                                    <input class="form-control" type="date" name="fecha" id="fecha" value="<?= date('Y-m-d'); ?>">
                                                </div>
                                            </div>
                                            <input type="hidden" id="parametro" name="parametro">
                                            <div class="form-row d-none" id="doc_boleta">
                                                <div class="form-group col-md-6">
                                                    <label for="serie">SERIE</label>
                                                    <input type="text" class="form-control" id="serie" name="serie" readonly>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="correlativo">CORRELATIVO</label>
                                                    <input type="number" class="form-control" id="correlativo" name="correlativo" readonly>
                                                </div>
                                            </div>
                                            <div class="form-row d-none" id="campo_datos">
                                                <div class="form-group col-md-2">
                                                    <label for="dni">DNI</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="dni" name="dni">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-info" type="button" onclick="consultaDni(event);">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="nombres">NOMBRES</label>
                                                    <input type="text" class="form-control" id="nombres" name="nombres">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="apellido_paterno">A. PATERNO</label>
                                                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="apellido_materno">A. MATERNO</label>
                                                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno">
                                                </div>
                                            </div>
                                            <div class="form-row d-none" id="campo_datos_fact">
                                                <div class="form-group col-md-2">
                                                    <label for="ruc">RUC</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="ruc" name="ruc">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-info" type="button" onclick="consultaRuc(event);">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="razon_social">RAZON SOCIAL</label>
                                                    <input type="text" class="form-control" id="razon_social" name="razon_social">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="direccion">DIRECCIÓN</label>
                                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                                </div>
                                            </div>
                                            <div class="row d-none" id="campo_correo">
                                                <div class="form-group col-md-6">
                                                    <label for="correo">CORREO</label>
                                                    <input id="correo" class="form-control" type="text" name="correo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                                <thead>
                                                    <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Cantidad</th>
                                                    <th>IGV</th>
                                                    <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblDetalleOP"></tbody>
                                            </table>
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
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input id="codigo_vendedor" class="form-control" type="number" name="codigo_vendedor" placeholder="Código de Vendedor">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input id="propina" class="form-control" type="number" name="propina" placeholder="Propina">
                                                        </div>
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
                                    <div id="loader" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%;">
                                        <div id="loader-background" style="position: absolute; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
                                        <div id="loader-icon" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                            <i class="fa fa-spinner fa-spin fa-3x" style="color: white;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="parametroBusq" value="3">
            <input type="hidden" id="lineaparamBusq" value="3">
            <div id="busquedaProductoPantalla" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="border-radius: 20px;">
                            <div class="modal-header bg-primary" style="border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title text-white" id="ProductName" style="margin-left: 15px;"><i class="fas fa-search"></i> Búsqueda de Productos</h5>
                                <button class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Buscar producto..." id="buscarProdPant">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="resultBusqueda">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            
            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/carrito.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/pedidos.js"></script>