<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Cortesia</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <button class="btn btn-primary" type="button" style="float: right;" onclick="frmEntradaCortesia();"><i class="fas fa-plus"></i> Generar Entrada</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tblEntradaCort" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>RUC</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>RUC</th>
                                    <th>Nombre</th>
                                    <th>Género</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div id="pedido_cortesia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="registroEntradaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="registroEntradaModalLabel">Registrar Entrada</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="registroEntradaCortForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="token" name="token" placeholder="Token">
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" onclick="buscarToken(event);">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="solic" name="solic" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="cant" name="cant" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="nombre_cli" name="nombre_cli" placeholder="Nombre">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" id="genero" name="genero">
                                                <option value="MASCULINO">Masculino</option>
                                                <option value="FEMENINO">Femenino</option>
                                                <option value="OTRO">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="modal-footer">
                                    <button class="btn btn-primary" type="button" onclick="regEntrCort();">Registrar</button>
                                    <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/entradas.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>