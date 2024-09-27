<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>INICIO</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-weight: bold; font-size: 20px">VENTAS X ARTÍCULOS</span>
                        <!--<span class="info-box-number">
                            <small style="display: inline; font-weight: bold;">Cantidad:</small> 
                            <small style="display: inline;"> <?php echo $data['resumen_ventas_gen']['cantidad']?></small>
                            <small style="display: inline; font-weight: bold;">Total:</small> 
                            <small style="display: inline;">S/.<?php echo $data['resumen_ventas_gen']['total']?></small>
                        </span>-->
                    </div>
                    <button type="button" class="btn btn-info btn-sm" onclick="verResumenVentas()">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text" style="font-weight: bold; font-size: 20px">VENTAS X CLIENTE</span>
                    </div>
                    <button type="button" class="btn btn-info btn-sm" onclick="verResumenClientes()">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="verResumenVentasModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                  <div class="modal-header bg-primary">
                      <h5 class="modal-title text-white" id="ProductName"><i class="fas fa-file-alt"></i> RESUMEN DE VENTAS X ARTÍCULO</h5>
                      <button class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body" style="overflow-y: auto;">
                      <div class="table-responsive">
                          <table class="table table-bordered" id="tblResumenVentasTBL" width="100%" cellspacing="0" style="font-size: 12px;">
                              <thead style="background-color: #f0f0f0;">
                                  <tr>
                                      <th style="text-align: center; width: 10px;">Id</th>
                                      <th style="text-align: center;">Descripción</th>
                                      <th style="text-align: center; width: 10px;">Código</th>
                                      <th style="text-align: center; width: 10px;">Cant.</th>
                                      <th style="text-align: center; width: 10px;">Unidad</th>
                                      <th style="text-align: center; width: 10px;">Tipo</th>
                                      <th style="text-align: center; width: 10px;">Total</th>
                                  </tr>
                              </thead>
                              <tbody id="tblResumenVentasTBLJS" style="font-size: 12px;">
                              </tbody>
                              <tfoot id="tblResumenVentasTFoot" style="background-color: #455a64"></tfoot>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div id="verResumenClientesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                  <div class="modal-header bg-primary">
                      <h5 class="modal-title text-white" id="ProductName"><i class="fas fa-file-alt"></i> RESUMEN DE VENTAS X ARTÍCULO</h5>
                      <button class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body" style="overflow-y: auto;">
                      <div class="table-responsive">
                          <table class="table table-bordered" id="tblResumenClientesTBL" width="100%" cellspacing="0" style="font-size: 12px;">
                              <thead style="background-color: #f0f0f0;">
                                  <tr>
                                      <th style="text-align: center; width: 10px;">N°</th>
                                      <th style="text-align: center; width: 10px;">Dni</th>
                                      <th style="text-align: center;">Nombre</th>
                                      <th style="text-align: center; width: 10px;">Ruc</th>
                                      <th style="text-align: center; ">Razon Social</th>
                                      <th style="text-align: center; width: 10px;">Total</th>
                                  </tr>
                              </thead>
                              <tbody id="tblResumenClientesTBLJS" style="font-size: 12px;">
                              </tbody>
                              <tfoot id="tblResumenClientesTFoot" style="background-color: #455a64"></tfoot>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>const base_url = "<?php  echo base_url;?>";</script>
            <script src="<?php echo base_url; ?>Assets/js/configuracion.js"></script>