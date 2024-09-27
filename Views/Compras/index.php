<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nuevo Ingreso</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <div class="modal-header bg-primary d-flex justify-content-center py-1">
                            <h5 class="modal-title text-white text-center" id="title">DATOS DE OPERACIÓN</h5>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmInfoPedido">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TIPO DE OPERACIÓN</label>
                                            <input type="hidden" id="total_compra" name="total_compra">
                                            <input type="hidden" id="id_compra" name="id_compra" value= "0">
                                            <select name="t_operacion" id="t_operacion" class="form-control" style="width: 100%;">
                                                <option disabled selected style="display:none;">Tipo de Operación</option>
                                                <?php foreach ($data['tipos_operaciones'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>FECHA</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ALMACEN INICIAL</label>
                                            <select name="id_almacen_ini" id="id_almacen_ini" class="form-control" style="width: 100%;">
                                                <option disabled selected style="display:none;">Almacen Inicial</option>
                                                <?php foreach ($data['almacenes'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ALMACEN FINAL</label>
                                            <select name="id_almacen_fin" id="id_almacen_fin" class="form-control" style="width: 100%; display: none;">
                                                <option value=""></option>
                                                <?php foreach ($data['almacenes'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TIPO DOCUMENTO</label>
                                            <select name="t_documento" id="t_documento" class="form-control" style="width: 100%;">
                                                <?php foreach ($data['t_documentos'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>TIPO DE CAMBIO</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="tipo_cambio" name="tipo_cambio" placeholder="T. Cambio">
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" onclick="consultaTipoCambio(event);"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>SERIE</label>
                                            <input id="serie_doc" class="form-control" type="text" name="serie_doc" placeholder="Serie">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>CORRELATIVO</label>
                                            <input id="correl_doc" class="form-control" type="number" name="correl_doc" placeholder="Correlativo">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="proveedor">Proveedor</label>
                                            <select name="proveedor" id="proveedor" class="form-control" style="width: 100%;">
                                                <?php foreach ($data['proveedores'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>">
                                                        <?php echo (!empty($row['razon_social']) ? $row['razon_social'] : $row['nombres']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow mb-4">
                        <form id="frmCompra">
                            <div class="modal-header bg-primary d-flex justify-content-center py-1">
                                <h5 class="modal-title text-white text-center" id="titulo_productos"></h5>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group centered-input">
                                            <label>INFORMACIÓN DE PRODUCTO</label>
                                            <input id="nombre" class="form-control" type="text" name="nombre" disabled placeholder="Información de Producto">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>BUSCAR</label>
                                            <div class="input-group">
                                                <button class="btn btn-info btn-block" type="button" onclick="buscarProducto(event);">Buscar Producto</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CÓDIGO</label>
                                            <div class="input-group">
                                                <input type="hidden" name="id" id="id">
                                                <input id="codigo" class="form-control" type="text" name="codigo" placeholder="Código">
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" onclick="buscarCodigo(event);"><i class="fa-solid fa-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>LIMPIAR</label>
                                            <button class="btn btn-secondary btn-block" type="button" onclick="limpiarForm(event)"><i class="fas fa-broom"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>PRECIO</label>
                                            <input id="precio" class="form-control" type="number" name="precio" placeholder="Precio">
                                        </div>
                                        <input id="sub_total" type="hidden" name="sub_total" d-none>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>CANTIDAD</label>
                                            <div class="input-group">
                                                <input id="cantidad" class="form-control" type="number" name="cantidad" disabled placeholder="Cantidad">
                                                <div class="input-group-append">
                                                    <a class="btn btn-info btnAddCompras">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>UNIDAD</label>
                                            <input id="unidad" class="form-control" type="text" name="unidad" disabled placeholder="Unidad">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descripción</th>
                                                        <th>Unidad</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tblDetalle">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button id="registrar_compra" class="btn btn-primary mt-2 btn-block" type="button" onclick="procesarCompra(event);">PROCESAR</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="busqueda_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">PRODUCTO</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmReceta">
                            <div class="table-responsive">
                                <table class="table table-sm" id="tblBuscProduct" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
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
            <script src="<?php echo base_url; ?>Assets/js/eliminarDataLista.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/compras_products.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/compras.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>