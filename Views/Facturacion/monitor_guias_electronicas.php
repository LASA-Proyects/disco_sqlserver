<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>MONITOR DE GUÍAS ELECTRÓNICAS</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                            <h3 class="card-title">VISUALIZADOR</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                            <div class="card-body">
                                <table class="table table-sm" id="tblMonitorGuiasElect" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Destinatario</th>
                                            <th>Serie</th>
                                            <th>Correlativo</th>
                                            <th>Ticket</th>
                                            <th>XML</th>
                                            <th>CDR</th>
                                            <th>Estado</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        
                            <div id="ver_ticket_guia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="title">Ticket de Guia</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input id="numero_ticket" class="form-control" type="text" name="numero_ticket" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <?php include "Views/Templates/footer.php" ?>
                        <script>
                            const base_url = "<?php echo base_url; ?>"
                        </script>
                        <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>
                        <script src="<?php echo base_url; ?>Assets/js/monitor_guia_electronica.js"></script>