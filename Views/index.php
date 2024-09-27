<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Disco Proyect 2023</title>
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/all.min.css">
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url; ?>Assets/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a class="h1">SISTEMA ADMIN.</a>
    </div>
    <div class="card-body">
      <form id="frmLogin">
        <div class="input-group mb-3 d-none" id="inpu_usuario">
          <input type="text" class="form-control" placeholder="Usuario" id="usuario" name="usuario">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3 d-none" id="inpu_clave">
          <input type="password" class="form-control" placeholder="Contraseña" id="clave" name="clave">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="form-group">
              <select class="form-control" id="usuario_select" name ="usuario_select">
                  <option value="" style="display: none;"></option>
                  <option value="1">ADMINISTRADOR</option>
                <?php foreach ($data['usuarios'] as $row) { ?>
                  <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
                <select class="form-control" id="almacen" name ="almacen">
                <option value="" style="display: none;"></option>
                </select>
              </div>
            </div>
          </div>
          <button class="btn btn-primary btn-block" type="submit" onclick="frmLogin(event);">Login</button>
          <div class="alert alert-danger text-center d-none" role="alert" id="alerta"></div>
      </form>
    </div>
  </div>
</div>
<script>const base_url = "<?php  echo base_url;?>";</script>
<script src="<?php echo base_url; ?>Assets/js/jquery-3.7.1.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/login.js"></script>
<script src="<?php echo base_url; ?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/adminlte.min.js"></script>
</body>
</html>
