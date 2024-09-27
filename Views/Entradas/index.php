<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Entradas</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content" style="display: flex; justify-content: center; align-items: center; height: 60vh; margin: 0;">
        <div class="container-fluid">
            <div class="row text-center">
                <div class="col-md-12 mb-4">
                <a href="<?php echo base_url; ?>Entradas/normal" class="btn btn-warning btn-lg" style="padding: 40px; width: 100%; border-radius: 40px; text-align: center; display: block;">ENTRADAS NORMALES</a>
                </div>
            </div>
        </div>
        <?php include "Views/Templates/footer.php" ?>
        <script>const base_url = "<?php  echo base_url;?>";</script>
        <script src="<?php echo base_url; ?>Assets/js/familia.js"></script>
        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>