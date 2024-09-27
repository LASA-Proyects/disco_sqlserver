<?php include "Views/Templates/header.php" ?>
<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/boton_permisos.css">
<div class="content-wrapper">
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Error de Página</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-danger">404</h2>

            <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Algo salió mal.</h3>
                <p>
                    Trabajaremos para solucionarlo de inmediato.
                    Mientras tanto, puedes volver al <a href="<?php echo base_url; ?>Configuracion/home">menú de inicio</a> o ponte en contacto con el administrador.
                </p>
            </div>
        </div>
        <?php include "Views/Templates/footer.php" ?>
        <script>
            const base_url = "<?php echo base_url; ?>"
        </script>