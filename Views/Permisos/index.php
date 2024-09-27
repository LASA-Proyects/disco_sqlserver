<?php include "Views/Templates/header.php" ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css" />
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Permisos</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
    <div class="container-fluid">
        <form id="frmUsuarioPermiso">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9" style="display: flex; align-items: center;">
                                    <h5 style="margin-right: 15px;">Usuario</h5>
                                    <select class="form-control" id="id_usuario" name="id_usuario" style="border-radius: 5px; border: 1px solid #ced4da;">
                                        <?php foreach ($data['usuarios'] as $row) { ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3" style="display: flex; align-items: center;">
                                    <button id="btnBuscar" class="btn btn-primary btn-block" onclick="buscarPermisosUsu(event);" style="border-radius: 5px; padding: 10px 20px;">
                                        <i class="fas fa-search"></i> Ver Permisos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="card" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="card-body">
                        <h5 style="margin-bottom: 15px; color: #343a40;">Árbol de Permisos</h5>
                        <div id="permisosTree" class="d-none" style="border: 1px solid #ced4da; border-radius: 5px; padding: 15px; background-color: #ffffff;"></div>
                    </div>
                    <button id="btnGuardar" class="btn btn-primary d-none" onclick="registrarPermiso(event);" style="border-radius: 5px; padding: 10px 20px; margin: 20px; display: block; margin-left: auto; margin-right: auto;">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<?php include "Views/Templates/footer.php" ?>
<script>
    const base_url = "<?php echo base_url; ?>";
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>
<script src="<?php echo base_url; ?>Assets/js/permisos.js"></script>
<script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>