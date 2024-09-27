<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Recetas</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2 class="font-weight-bold text-center"><?php echo $data['producto']['descripcion']?></h2>
                        </div>
                        <div class="card-body">
                            <img class="img-fluid w-100" style="height: auto;" src="<?php echo (base_url.'/Assets/img/' . $data['producto']['foto']); ?>" width="100">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <button class="btn btn-primary" type="button" style="float: right;" onclick="frmReceta();"><i class="fas fa-plus"></i> Agregar Productos</button>
                        </div>
                        <div class="card-body">
							<div class="table-responsive">
								<table class="table table-sm" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
									<thead>
										<tr>
                                            <th>#</th>
											<th>Producto</th>
                                            <th>Unidad de Medida</th>
											<th>Cantidad</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tblListaReceta">
									</tbody>
								</table>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="nueva_receta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="title">Nueva Receta</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="frmReceta">
                <input type="hidden" value="<?php echo $data['id_producto'];?>" name="id_producto">
					<div class="table-responsive">
                    <table class="table table-sm" id="tblBuscProduct" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Precio</th>
                                <th>Familia</th>
                                <th>Unidad</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php" ?>
<script>const base_url = "<?php  echo base_url;?>";</script>
<script src="<?php echo base_url; ?>Assets/js/receta.js"></script>
<script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>