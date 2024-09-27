<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Productos</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="card-header py-3">
                            <form id="exportForm" method="post">
                                <input type="hidden" id="titulo" name="titulo" value="">
                                <input type="hidden" id="cabeceras" name="cabeceras" value="">
                                <input type="hidden" id="contenido" name="contenido" value="">
                                    <button type="button" class="btn btn-success" style="float: left;" id="excelDetalladoButton">
                                        <i class="far fa-file-excel"></i> 
                                    </button>
                            </form>
                                <button class="btn btn-primary" type="button" style="float: right;" onclick="frmProducto();"><i class="fas fa-plus"></i> </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm" id="tblProductos" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Familia</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="title">Nuevo Artículo</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="frmProducto">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="codigo">Código</label>
                                                        <input type="hidden" name="id" id="id">
                                                        <input id="codigo" class="form-control" type="text" name="codigo">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="t_articulo">Tipo Artículo</label>
                                                        <select name="t_articulo" id="t_articulo" class="form-control" style="width: 100%;">
                                                            <?php foreach ($data['t_articulos'] as $row) { ?>
                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="familia">Familia</label>
                                                        <select name="familia" id="familia" class="form-control" style="width: 100%;">
                                                            <?php foreach ($data['familias'] as $row) { ?>
                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="afecta_compra" id="afecta_compra" value="1">
                                                            <label class="form-check-label">Incluir en Compras</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="afecta_venta" id="afecta_venta" value="1">
                                                            <label class="form-check-label">Incluir en Ventas</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="afecta_igv" id="afecta_igv" value="1">
                                                            <label class="form-check-label">Afecto a I.G.V</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="afecta_iss" id="afecta_iss" value="1">
                                                            <label class="form-check-label">Incluye ISS</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="descripcion">Descripción</label>
                                                        <input id="descripcion" class="form-control" type="text" name="descripcion">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="linea">Línea</label>
                                                        <select name="linea" id="linea" class="form-control" style="width: 100%;">
                                                            <?php foreach ($data['lineas'] as $row) { ?>
                                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ubicacion">Ubicación</label>
                                                        <input id="ubicacion" class="form-control" type="text" name="ubicacion">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="origen">Origen</label>
                                                        <input id="origen" class="form-control" type="text" name="origen">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="precio_compra">Precio Compra</label>
                                                        <input id="precio_compra" class="form-control" type="number" name="precio_compra" placeholder="S/.">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="precio_venta">Precio Venta</label>
                                                        <input id="precio_venta" class="form-control" type="numbre" name="precio_venta" placeholder="S/.">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="stock_minimo">Stk. Mínimo</label>
                                                        <input id="stock_minimo" class="form-control" type="numbre" name="stock_minimo">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="unidad_medida">UNIDAD</label>
                                                        <select id="unidad_medida" class="form-control" name="unidad_medida">
                                                            <option value=""></option>
                                                            <option value="LITROS">LITROS</option>
                                                            <option value="MILILITROS">MILILITROS</option>
                                                            <option value="ONZAS">ONZAS</option>
                                                            <option value="GRAMOS">GRAMOS</option>
                                                            <option value="KILOGRAMOS">KILOGRAMOS</option>
                                                            <option value="UNIDAD">UNIDAD</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cantidad">Cantidad</label>
                                                        <input id="cantidad" class="form-control" type="number" name="cantidad">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Foto</label>
                                                    <div class="card border-primary">
                                                        <div class="card-body">
                                                            <label for="imagen" class="btn btn-primary" id="icon-image"><i class="fas fa-image"></i></label>
                                                            <span id="icon-cerrar"></span>
                                                            <input id="imagen" type="file" class="d-none" type="file" name="imagen" onchange="preview(event)">
                                                            <input type="hidden" id="foto_actual" name="foto_actual">
                                                            <img class="img-thumbnail" id="img-preview">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="button" onclick="registrarProduct(event);" id="btnAccion">Registrar</button>
                                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php include "Views/Templates/footer.php" ?>
                        <script>
                            const base_url = "<?php echo base_url; ?>"
                        </script>
                        <script src="<?php echo base_url; ?>Assets/js/producto.js"></script>
                        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>