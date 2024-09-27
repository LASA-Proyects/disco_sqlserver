<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Familia</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
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
                    <button class="btn btn-primary" type="button" style="float: right;" onclick="frmFamilia();"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body">
                    <table class="table table-sm" id="tblFamilia" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                        <thead>
                            <tr></tr>
                                <th>#</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="nueva_familia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Nueva Familia</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmFamilia">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" name="id" id="id">
                                        <label for="nombre">Nombre</label>
                                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre del usuario">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="linea">Linea</label>
                                        <select name="linea" id="linea" class="form-control" style="width: 100%;">
                                            <?php foreach ($data['lineas'] as $row) { ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                            <?php } ?>
                                        </select>
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
                                <button class="btn btn-primary" type="button" onclick="registrarFamilia(event);" id="btnAccion">Registrar</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/familia.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>