<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Toma de Inventario</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <form id="tomaInvForm" method="POST" target="_blank">
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="min">Almacen</label>
                                        <select name="almacen" id="almacen" class="form-control almacenes" style="width: 100%;">
                                            <?php foreach ($data['almacenes'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Acción</label>
                                    <button type="button" class="btn btn-primary btn-block" id="tomaInv">
                                        <i class="fas fa-download"></i> Generar Toma
                                    </button>
                                </div>
                            </div>
                        </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-sm" id="tblTomaInv" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Usuario</th>
                                        <th>Fecha Descarga</th>
                                        <th>Fecha Subida</th>
                                        <th>Fecha Proceso</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="subida" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="title">Nuevo Usuario</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="frmImportar" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="archivoExcel">Seleccionar archivo Excel:</label>
                            <input type="file" class="form-control-file" id="archivoExcel" name="archivoExcel">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary btn-block mt-md-0 mt-3" onclick="importar()"><i class="fas fa-upload"></i> IMPORTAR</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block mt-md-0 mt-3" data-dismiss="modal">CANCELAR</button>
                            </div>
                        </div>
                    </form>
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
    <div id="verEstadoTomaInv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="margin: auto; max-width: 90%;">
        <div class="modal-content" style="border-radius: 20px; width: 100%; height: 80vh;">
            <div class="modal-header bg-primary" style="border-radius: 20px 20px 0 0;">
            <h5 class="modal-title text-white" style="margin-left: 15px;"><i class="fas fa-file-alt"></i> ESTADO TOMA INVENTARIO</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="diferencia_flt">DIFERENCIA</label>
                        <select name="diferencia_flt" id="diferencia_flt" class="form-control buscador" style="width: 100%;" data-index="3">
                            <option disabled selected></option>
                            <option value="1">POSITIVOS</option>
                            <option value="2">NEGATIVOS</option>
                            <option value="3">SIN DIFERENCIA [0]</option>
                            <option value="">TODOS</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="movimientos_flt">MOVIMIENTO</label>
                        <select name="movimientos_flt" id="movimientos_flt" class="form-control buscador" style="width: 100%;" data-index="3">
                            <option disabled selected></option>
                            <option value="1">INGRESOS</option>
                            <option value="2">SALIDAS</option>
                            <option value="">TODOS</option>
                        </select>
                    </div>
                </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tblTomaInvId" width="100%" cellspacing="0">
                        <thead style="background-color: #f0f0f0;">
                            <tr>
                                <th style="text-align: center;">N°</th>
                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                <th style="text-align: center;">UNIDAD</th>
                                <th style="text-align: center;">ALMACEN</th>
                                <th style="text-align: center;">STOCK ACTUAL SYS</th>
                                <th style="text-align: center;">STOCK FÍSICO</th>
                                <th style="text-align: center;">DIFERENCIA</th>
                                <th style="text-align: center;">SERIE - CORRELATIO</th>
                                <th style="text-align: center;">MOVIMIENTO</th>
                            </tr>
                        </thead>
                        <tbody id="tblTomaInvIdJs" style="text-align: center;">
                        </tbody>
                    </table>
                    <form id="exportForm" method="post">
                        <input type="hidden" id="titulo" name="titulo" value="">
                        <input type="hidden" id="cabeceras" name="cabeceras" value="">
                        <input type="hidden" id="contenido" name="contenido" value="">
                        <div class="col-md-1">
                            <div class="form-group">
                            <button type="button" class="btn btn-success btn-block" id="excelDetalladoButton">
                                <i class="far fa-file-excel"></i>
                            </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php include "Views/Templates/footer.php" ?>
<script>
    const base_url = "<?php echo base_url; ?>"
</script>
<script src="<?php echo base_url; ?>Assets/js/toma_inv.js"></script>
<script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>