<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Carga</h1>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">REPORTE DE VENTAS</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="exportForm" method="POST" target="_blank">
                            <div class="row text-center d-flex justify-content-center align-items-center">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <div class="form-group">
                                                <label for="min">DESDE</label>
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
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <div class="form-group">
                                                <label for="min">HASTA</label>
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
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <div class="form-group">
                                                <label for="min">TIPOS DE PEDIDOS</label>
                                                <select name="usuario" id="usuario" class="form-control productos" style="width: 100%;">
                                                    <?php foreach ($data['usuarios'] as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                    <?php } ?>
                                                    <option value="-1">GENERAL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-warning btn-block" id="pdfButton">RESUMEN VENTA</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button class="btn btn-info" type="button" style="float: right;" onclick="frmCarga();">IMPORTAR</button>
                    <button class="btn btn-secondary" type="button" style="float: right; margin-right: 10px;" onclick="frmInvitado();">INGRESAR</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="tblCargaInvitados" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Promotor</th>
                                    <th>Dni</th>
                                    <th>Apellido Paterno</th>
                                    <th>Apellido Materno</th>
                                    <th>Nombre</th>
                                    <th>Mesa</th>
                                    <th>Fecha Asistencia</th>
                                    <th>Hora Asistencia</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div id="nueva_carga" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">IMPORTAR</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmCarga" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input id="excel_file" class="form-control" type="file" name="excel_file">
                                </div>
                                <button class="btn btn-primary" type="button" onclick="importarDesdeExcel(event);" id="btnAccion">IMPORTAR</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            
            <div id="nuevo_invitado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Nuevo Invitado</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmInvitado">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input id="dni" class="form-control" type="number" name="dni" placeholder="DNI">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input id="a_materno" class="form-control" type="text" name="a_materno" placeholder="Apellido Paterno">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input id="a_paterno" class="form-control" type="text" name="a_paterno" placeholder="Apellido Materno">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input id="nombre_inv" class="form-control" type="text" name="nombre_inv" placeholder="Nombres Completos">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select name="mesa" id="mesa" class="form-control" style="width: 100%;">
                                                <?php foreach ($data['mesas'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="button" onclick="registrarInvitado(event);">Registrar</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/carga.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>