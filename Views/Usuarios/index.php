<?php include "Views/Templates/header.php" ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="card-header py-3">
                            <h3 style="float: left; margin: 0; font-size: 1.5rem; color: #343a40;">Usuarios</h3>
                            <button class="btn btn-primary" type="button" style="float: right;" onclick="frmUsuario();"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm" id="tblUsuarios" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Dni</th>
                                        <th>Usuario</th>
                                        <th>Nombre</th>
                                        <th>Tipo Usuario</th>
                                        <th>Código Vendedor</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="nuevo_usuario" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white"><i class="fas fa-user"></i> Nuevo Usuario</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="frmUsuario">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="num_doc">Dni</label>
                                                        <input id="num_doc" class="form-control" type="text" name="num_doc" placeholder="Dni">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="codigo_vendedor">Código de Vendedor</label>
                                                        <input type="number" class="form-control" id = "codigo_vendedor" name = "codigo_vendedor" placeholder = "Código de Vendedor">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="usuario">Usuario</label>
                                                        <input type="hidden" name="id" id="id">
                                                        <input id="usuario" class="form-control" type="text" name="usuario" placeholder="Usuario">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nombre">Nombre</label>
                                                        <input id="nombre" class="form-control" type="text" name="nombre" placeholder="Nombre">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="nombre">Tipo de Usuario</label>
                                                <select class="form-control" id="tipo_usuario" name ="tipo_usuario">
                                                    <?php foreach ($data['tipo_usuarios'] as $row) { ?>
                                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="row" id="claves">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="clave">Contraseña</label>
                                                        <input id="clave" class="form-control" type="password" name="clave" placeholder="Contraseña">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="confirmar">Confirmar Contraseña</label>
                                                        <input id="confirmar" class="form-control" type="password" name="confirmar" placeholder="Confirmar Contraseña">
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="button" onclick="registrarUser(event);" id="btnAccion">Registrar</button>
                                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="asignar_ingreso" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="title">Registro de Horario</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" id="frmFechaPermi">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="hidden" name="id_usu" id="id_usu">
                                                        <label>Fecha Inicial</label>
                                                        <input id="fecha_ini" class="form-control" type="date" name="fecha_ini">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Hora Inicial</label>
                                                        <input id="hora_ini" class="form-control" type="time" name="hora_ini">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Fecha Final</label>
                                                        <input id="fecha_fin" class="form-control" type="date" name="fecha_fin">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Hora Final</label>
                                                        <input id="hora_fin" class="form-control" type="time" name="hora_fin">
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="button" onclick="registrarFechas(event);">Registrar</button>
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
                        <script src="<?php echo base_url; ?>Assets/js/usuarios.js"></script>
                        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>