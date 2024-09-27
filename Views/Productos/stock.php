<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Stock de Productos</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
        <form method="post" id="frmbuscarStockPorAlmacen">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="card-header">
                            <h3 class="card-title">REPORTE DE PEDIDOS</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="almacen">A LA FECHA</label>
                                        <input class="form-control" type="date" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="producto">ARTICULO</label>
                                        <select name="producto" id="producto" class="form-control buscador" style="width: 100%;" onchange="buscarProvincia(event)">
                                            <?php foreach ($data['productos'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['descripcion']; ?></option>
                                            <?php } ?>
                                            <option value="-1">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="almacen">ALMACEN</label>
                                        <select name="almacen" id="almacen" class="form-control buscador" style="width: 100%;">
                                            <?php foreach ($data['almacenes'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                            <option value="-1">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="familia">FAMILIA</label>
                                        <select name="familia" id="familia" class="form-control buscador" style="width: 100%;">
                                            <?php foreach ($data['familias'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                            <option value="-1">TODOS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                <label for="almacen">BUSCAR STOCK</label>
                                    <button class="btn btn-info btn-block" type="button" onclick="buscarStockPorAlmacen(event)">
                                        <i class="fas fa-search"></i> BUSCAR
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="filtro_stock">Filtro Stock</label>
                                            <select name="filtro_stock" id="filtro_stock" class="form-control buscador" style="width: 100%;" data-index="6">
                                                <option disabled selected></option>
                                                <option value="1">Mayores a 0</option>
                                                <option value="2">Menores a 0</option>
                                                <option value="3">Iguales a 0</option>
                                                <option value="">Todos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="filtro_stock">Exportar</label>
                                            <form id="exportForm" method="post">
                                                <input type="hidden" id="titulo" name="titulo" value="">
                                                <input type="hidden" id="cabeceras" name="cabeceras" value="">
                                                <input type="hidden" id="contenido" name="contenido" value="">
                                                <button type="button" class="btn btn-success btn-block" id="excelDetalladoButton">
                                                    <i class="far fa-file-excel"></i> Excel
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm" id="tblStock" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Código</th>
                                                <th>Foto</th>
                                                <th>Descripción</th>
                                                <th>Familia</th>
                                                <th>Almacen</th>
                                                <th>Stock</th>
                                                <th>Ver Kardex</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblProductosStock"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<input type="hidden" id="tipo_enlace" value = 1>
<div id="verKardexProducto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin: auto; max-width: 90%;">
        <div class="modal-content" style="border-radius: 20px; width: 100%; height: 80vh;">
            <div class="modal-header bg-primary" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title text-white" id="ProductName" style="margin-left: 15px;"><i class="fas fa-file-alt"></i> KADEX DE PRODUCTO</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="ingresos">INGRESOS</label>
                                <input id="ingresos" class="form-control" type="text" name="ingresos" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="salidas">SALIDAS</label>
                                <input id="salidas" class="form-control" type="text" name="salidas" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="saldo">SALDO</label>
                                <input id="saldo" class="form-control" type="text" name="saldo" readonly>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered" id="tblKardexProductos" width="100%" cellspacing="0">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th style="text-align: center;">FECHA</th>
                                <th style="text-align: center;">OPERACIÓN</th>
                                <th style="text-align: center;">SERIE</th>
                                <th style="text-align: center;">CORRELATIVO</th>
                                <th style="text-align: center;">ALM. INICIAL</th>
                                <th style="text-align: center;">INGRESO</th>
                                <th style="text-align: center;">SALIDA</th>
                                <th style="text-align: center;">STOCK</th>
                                <th style="text-align: center;">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody id="tblKardexProductosJS" style="text-align: center;">
                        </tbody>
                    </table>
                </div>
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