<?php include "Views/Templates/header.php" ?>
<link rel="stylesheet" href="<?php echo base_url;?>Assets/css/boton_permisos.css">
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
            <div class="row">
                <div class="col-12">
                    <div class="card" style="background-color: #191e2f;">
                        <div class="col-md-8 mx-auto">
                            <div class="card-header text-center">
                                <p style="color: white;">Asignar Permisos</p>
                            </div>
                            <div class="card-body">
                                <form id="formulario" onsubmit="registrarPermisos(event)">
                                    <div class="row">
                                        <?php foreach ($data['datos'] as $row){?>
                                            <div class="col-md-4 text-center text-capitalize p-2">
                                                <label for="" style="color: white;"><?php echo $row['permiso'];?></label><br>
                                                <input type="checkbox" name ="permisos[]" value="<?php echo $row['id']; ?>" <?php echo isset($data['asignados'][$row['id']]) ? 'checked' : '' ;?>>
                                            </div>
                                        <?php }?>
                                        <input type="hidden" value="<?php echo $data['id_usuario'];?>" name="id_usuario">
                                    </div>
                                    <div class="d-flex flex-column align-items-center mt-3">
                                        <div class="w-50">
                                            <button class="btn btn-outline-primary btn-block mb-2" type="submit">Guardar Cambios</button>
                                        </div>
                                        <div class="w-50">
                                            <a class="btn btn-outline-danger btn-block" href="<?php echo base_url; ?>Usuarios">Cancelar</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php include "Views/Templates/footer.php" ?>
                        <script src="<?php echo base_url;?>Assets/js/usuarios.js"></script>
                        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
                        <script>
                            const base_url = "<?php echo base_url; ?>"
                        </script>