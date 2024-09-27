<?php include "Views/Templates/header.php" ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Contactos</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header py-3">
                            <form id="exportForm" method="post">
                                <input type="hidden" id="titulo" name="titulo" value="">
                                <input type="hidden" id="cabeceras" name="cabeceras" value="">
                                <input type="hidden" id="contenido" name="contenido" value="">
                                <button type="button" class="btn btn-success" style="float: left;"  id="excelDetalladoButton">
                                    <i class="far fa-file-excel"></i> Exportar a Excel
                                </button>
                            </form>
                            <button class="btn btn-primary" type="button" style="float: right;" onclick="frmProveedor();"><i class="fas fa-plus"></i> Nuevo Contacto</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm" id="tblProveedor" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Dni</th>
                                        <th>Ruc</th>
                                        <th>Apellidos</th>
                                        <th>Nombres</th>
                                        <th>Razón Social</th>
                                        <th>Tipo</th>
                                        <th>Fecha Alta</th>
                                        <th>Fecha Cese</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="nuevo_proveedor" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="title">Nuevo Proveedor</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="frmProveedor">
                                            <input type="hidden" name="id" id="id">
                                            <input type="hidden" id="dni_modificar" name="dni_modificar">
                                            <input type="hidden" id="ruc_modificar" name="ruc_modificar">
                                            <div class="form-group">
                                                <label for="tipo_documento">DOCUMENTO DE IDENTIDAD</label>
                                                <select name="tipo_documento" id="tipo_documento" class="form-control">
                                                    <option value="" disabled selected></option>
                                                    <option value="1">DNI</option>
                                                    <option value="2">RUC</option>
                                                </select>
                                            </div>
                                            <div class="row d-none" id="campos_dni">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="dni">DNI</label>
                                                        <div class="input-group">
                                                            <input id="dni" class="form-control" type="number" name="dni">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="button" onclick="consultaDni(event);">Buscar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nombres">NOMBRE COMPLETO</label>
                                                        <input id="nombres" class="form-control" type="text" name="nombres">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="apellidoPeterno">APELLIDO PATERNO</label>
                                                        <input id="apellidoPeterno" class="form-control" type="text" name="apellidoPeterno">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="apellidoMaterno">APELLIDO MATERNO</label>
                                                        <input id="apellidoMaterno" class="form-control" type="text" name="apellidoMaterno">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none" id="campos_ruc">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ruc">RUC</label>
                                                        <div class="input-group">
                                                            <input id="ruc" class="form-control" type="number" name="ruc">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary" type="button" onclick="consultaRuc(event);">Buscar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="razon_social">RAZÓN SOCIAL</label>
                                                        <input id="razon_social" class="form-control" type="text" name="razon_social">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="direccion">DIRECCIÓN</label>
                                                        <input id="direccion" class="form-control" type="text" name="direccion">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="correo">CORREO</label>
                                                        <input id="correo" class="form-control" type="text" name="correo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none" id="data_chofer">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="placa">PLACA</label>
                                                        <input id="placa" class="form-control" type="text" name="placa">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="licencia">LICENCIA</label>
                                                        <input id="licencia" class="form-control" type="text" name="licencia">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none" id="data_transporte">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="numero_mtc">N° MTC</label>
                                                        <input id="numero_mtc" class="form-control" type="number" name="numero_mtc">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="tipo_persona">TIPO CONTACTO</label>
                                                <select name="tipo_persona" id="tipo_persona" class="form-control" style="width: 100%;">
                                                    <?php foreach ($data['tipo_personas'] as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <button class="btn btn-primary" type="button" onclick="registrarProveedor(event);" id="btnAccion">Registrar</button>
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
                        <script src="<?php echo base_url; ?>Assets/js/proveedor.js"></script>
                        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>