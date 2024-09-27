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
                    <a href="<?php echo base_url; ?>Terminal/mbebidas" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                        <?php foreach ($data['familia'] as $familia) { ?>
                        <a href="<?php echo base_url.'Pedidos/bebidas_representante/'.$familia['id'];?>" class="btn">
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
                                        <img src="<?php echo base_url . "Assets/img/" . $producto['foto']; ?>" class="card-img-top" alt="<?php echo $producto['descripcion']; ?>">
                                    </div>
                                    <div class="card-body d-flex flex-column" style="height: 130px;">
                                        <h5 class="card-title text-center my-auto"><?php echo $producto['descripcion']; ?></h5>
                                        <p class="card-text text-center my-auto">S/. <?php echo $producto['precio_venta']; ?></p>
                                    </div>
                                    <form id="frmPedidoIngr_<?php echo $producto['id_producto']; ?>">
                                    <input type="hidden" name="t_pedido" id="t_pedido" value="3">
                                        <div class="card-footer">
                                            <input type="hidden" name="id_product" value="<?php echo $producto['id_producto']; ?>">
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
                                        $url = base_url.'Pedidos/bebidas_representante/'.$data['id_familia'];
                                        
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
                        <input type="hidden" name="tipo_pedido" id="tipo_pedido" value="3">
                        <div class="form-group">
                            <label>FECHA DE REGISTRO</label>
                            <input id="fecha_registro" class="form-control" type="date" name="fecha_registro" placeholder="INVITACION A DJ ..." value="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <input id="glosa" class="form-control" type="text" name="glosa" placeholder="INVITACION A DJ ...">
                        </div>
                        <div class="text-center mt-3">
                            <label>AUTORIZADO POR</label>
                            <select name="autorizado" id="autorizado" class="form-control" style="width: 100%; padding: 8px; font-size: 16px; text-align: center;">
                                <?php foreach ($data['trab_descs'] as $row) { ?>
                                    <option value="<?php echo $row['id']; ?>" style="background-color: #f5f5f5; color: #333; border: 1px solid #ccc;">
                                        <?php echo $row['nombre']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="text-left mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="consumo_artista" id="consumo_artista" value="1">
                                <label class="form-check-label">CONSUMO DE ARTISTA</label>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button class="btn btn-primary btn-block" type="button" onclick="registrarPedido(event);" id="btnProcesarPedido">PROCESAR</button>
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
                                    <button class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="loader" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%;">
                    <div id="loader-background" style="position: absolute; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"></div>
                    <div id="loader-icon" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <i class="fa fa-spinner fa-spin fa-3x" style="color: white;"></i>
                    </div>
                </div>
                <input type="hidden" id="parametroBusq" value="3">
                <input type="hidden" id="lineaparamBusq" value="1">
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
            <script src="<?php echo base_url; ?>Assets/js/pedidos_no_venta.js"></script>