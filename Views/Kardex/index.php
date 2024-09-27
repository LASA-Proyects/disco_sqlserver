<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
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
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <div class="card-header">
                <form method="post" id="frmBuscarKardexProd">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                            <label for="producto">PRODUCTO</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cod_prod" name="cod_prod" placeholder="CÓDIGO PRODUCTO">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="button" onclick="buscarProducto(1)">
                                            <i class="fas fa-search text-white"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="hasta_fecha">FECHA HASTA</label>
                                <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="almacen">ALMACEN</label>
                                <select class="form-control" id="almacen" name="almacen">

                                    <?php foreach ($data['almacenes'] as $row) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                    <?php } ?>
                                    <option value="-1">TODOS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="promotor">BUSCAR</label>
                                <button class="btn btn-success btn-block" type="button" onclick="buscarKardex(event)">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="promotor">EXPORT. X RANGO</label>
                                <button class="btn btn-secondary btn-block" type="button" onclick="modalExportarxCodigo()">
                                    <i class="fas fa-code"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="promotor">EXPORT. GENERAL</label>
                                <button class="btn btn-info btn-block" type="button" onclick="modalExportarGeneral()">
                                    <i class="fas fa-code"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
            </div>
            <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="form-group">
                                <form id="exportForm" method="post">
                                    <input type="hidden" id="titulo" name="titulo" value="">
                                    <input type="hidden" id="cabeceras" name="cabeceras" value="">
                                    <input type="hidden" id="contenido" name="contenido" value="">
                                    <button type="button" class="btn btn-success btn-block" id="excelDetalladoButton">
                                        <i class="far fa-file-excel"></i> Exportar a Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm" id="tblKardexT" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Id</th>
                                    <th>Operación</th>
                                    <th>Movimiento</th>
                                    <th>Serie</th>
                                    <th>Correlativo</th>
                                    <th>Alm. Inicial</th>
                                    <th>Ingreso</th>
                                    <th>Salida</th>
                                    <th>Stock</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="tblKardex"></tbody>
                        </table>
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
                                <input type="hidden" id="tipo_busqueda" name ="tipo_busqueda">
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

            <div id="modalExportarxCodigo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">EXPORTAR EN EXCEL X RANGO</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmExportarExcel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="desde">DESDE:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="codigo_inicial" name="codigo_inicial">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="buscarProducto(2)">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hasta">HASTA:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="codigo_final" name="codigo_final">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="buscarProducto(3)">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicial">Fecha Inicial:</label>
                                            <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_final">Fecha Final:</label>
                                            <input type="date" class="form-control" id="fecha_final" name="fecha_final">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success" id="exportarExcel">
                                    <i class="fas fa-file-excel"></i> Exportar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modalExportarGeneral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">EXPORTAR EN EXCEL GENERAL</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmExportarExcelGen">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicial_gen">Fecha Inicial:</label>
                                            <input type="date" class="form-control" id="fecha_inicial_gen" name="fecha_inicial_gen">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_final_gen">Fecha Final:</label>
                                            <input type="date" class="form-control" id="fecha_final_gen" name="fecha_final_gen">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success" id="exportGeneral">
                                    <i class="fas fa-file-excel"></i> Exportar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/kardex.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>