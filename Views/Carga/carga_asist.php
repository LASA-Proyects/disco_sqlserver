<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Buttons</title>
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url;?>Assets/css/adminlte.min.css">
</head>

<?php include "Views/Templates/header_pos.php" ?>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Marca de Asistencia para Invitados</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-3">
                <a href="<?php echo base_url; ?>Terminal/mboleteria" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>
        </div>
          <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Buscar Invitado</h3>
                </div>
                <div class="card-body">
                  <form method="post" id="frmbuscarInvitado">
                    <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="promotor">SELECCIONAR PROMOTOR</label>
                            <select name="promotor" id="promotor" class="form-control" style="width: 100%;">
                                <?php foreach ($data['promotores'] as $row) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                            <label for="promotor">BUSCAR</label>
                            <button class="btn btn-success btn-block" type="button" onclick="buscarInvitado(event)">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="table-responsive">
                      <table class="table table-sm" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                          <thead>
                              <tr>
                              <th>Apellido Paterno</th>
                              <th>Apellido Materno</th>
                              <th>Nombres</th>
                              <th></th>
                              </tr>
                          </thead>
                          <tbody id="tblDetalleAsist"></tbody>
                      </table>
                      <button id="btnAccion" class="btn btn-primary" type="button" onclick="capturarIdUsuario(event);">Registrar</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/carga.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>