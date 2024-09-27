<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>GUIAS ELECTRÓNICAS</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <form method="post" id="frmGuiaElectronica">
                    <div class="card card-info">
                        <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                            <h3 class="card-title">DATOS GUÍA</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">Destinatario</label>
                                            <select name="destinatario" id="destinatario" class="form-control destinatarios" style="width: 100%;">
                                                <?php foreach ($data['destinatarios'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>">
                                                        <?php echo (!empty($row['razon_social']) ? $row['razon_social'] : $row['nombres']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-1 col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">F. Emisión</label>
                                            <input id="f_emision" class="form-control" type="date" name="f_emision" value="<?php date_default_timezone_set('America/Lima'); echo  date('Y-m-d')?>">
                                        </div>
                                    </div>
                                    <div class="col-xl-1 col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">F. Traslado</label>
                                            <input id="f_traslado" class="form-control" type="date" name="f_traslado" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d')?>">
                                            <div class="form-check form-check-inline d-none" id="vehiculo_menor_check">
                                                <input id="vehiculo_menor" class="form-check-input" type="checkbox" name="vehiculo_menor">
                                                <label class="form-check-label" for="vehiculo_menor" style="font-size: 14px;">Vehículo Menor</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">Motivo</label>
                                            <select name="motivo" id="motivo" class="form-control depart_partida" style="width: 100%;">
                                                <?php foreach ($data['motivos'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['guia_motivo_traslado']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="form-group">
                                                <input id="orden_compra" class="form-control" type="text" name="orden_compra" placeholder="Orden de Compra">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-3">
                                        <div class="form-group">
                                            <label for="modalidad" style="margin-bottom: 0rem;">Modalidad</label>
                                            <select name="modalidad" id="modalidad" class="form-control depart_partida" style="width: 100%;">
                                                <?php foreach ($data['modalidades'] as $row) { ?>
                                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['guia_modalidad_traslado']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="form-group">
                                                <input id="nombre_modalidad" class="form-control d-none" type="text" name="nombre_modalidad" placeholder="Nombres">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3w col-sm-3" id="transporte_razon_social_inputs">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">Transporte Razón Social</label>
                                            <div class="form-group">
                                                <input id="transporte_razon_social" class="form-control" type="text" name="transporte_razon_social" placeholder="Transporte Razón Social">
                                                <input id="num_registro_mtc" class="form-control" type="text" name="num_registro_mtc " placeholder="Número Registro MTC">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-3 d-none" id="vehiculo_inputs">
                                        <div class="form-group">
                                            <label style="margin-bottom: 0rem;">Datos Vehículo</label>
                                            <div class="form-group">
                                                <input id="carro_placa" class="form-control" type="text" name="carro_placa" placeholder="Placa">
                                                <input id="carro_apellidos" class="form-control d-none" type="text" name="carro_apellidos" placeholder="Apellidos">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-1 col-sm-3 d-none" id="chofer_inputs">
                                        <div class="form-group">
                                            <label for="modalidad" style="margin-bottom: 0rem;">Chofer</label>
                                            <div class="form-group">
                                                <input id="chofer_licencia" class="form-control" type="text" name="chofer_licencia" placeholder="Licencia">
                                                <input id="chofer_dni" class="form-control" type="number" name="chofer_dni" placeholder="DNI">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                                <h3 class="card-title">DATOS ENVÍO</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <label style="margin-bottom: 0rem;">PUNTO DE PARTIDA</label>
                                <div class="row">
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="departamento_partida" style="margin-bottom: 0rem;">Departamento</label>
                                            <select name="departamento_partida" id="departamento_partida" class="form-control depart_partida" style="width: 100%;" onchange="buscarProvinciaPartida(this.value)">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['departamentos'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['departamento']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="provincia_partida" style="margin-bottom: 0rem;">Provincia</label>
                                            <select name="provincia_partida" id="provincia_partida" class="form-control depart_partida" style="width: 100%;" onchange="buscarDistritoPartida(this.value)">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['provincias'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['provincia']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="distrito_partida" style="margin-bottom: 0rem;">Distrito</label>
                                            <select name="distrito_partida" id="distrito_partida" class="form-control depart_partida" style="width: 100%;">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['distritos'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['distrito']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="direccion_partida" style="margin-bottom: 0rem;">Dirección</label>
                                            <input id="direccion_partida" class="form-control" type="text" name="direccion_partida" placeholder="Dirección">
                                        </div>
                                    </div>
                                </div>

                                <label style="margin-bottom: 0rem;">PUNTO DE LLEGADA</label>
                                <div class="row">
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="departamento_llegada" style="margin-bottom: 0rem;">Departamento</label>
                                            <select name="departamento_llegada" id="departamento_llegada" class="form-control depart_partida" style="width: 100%;" onchange="buscarProvinciaLlegada(this.value)">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['departamentos'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['departamento']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="provincia_llegada" style="margin-bottom: 0rem;">Provincia</label>
                                            <select name="provincia_llegada" id="provincia_llegada" class="form-control depart_partida" style="width: 100%;" onchange="buscarDistritoLlegada(this.value)">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['provincias'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['provincia']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-sm-2">
                                        <div class="form-group">
                                            <label for="distrito_llegada" style="margin-bottom: 0rem;">Distrito</label>
                                            <select name="distrito_llegada" id="distrito_llegada" class="form-control depart_partida" style="width: 100%;">
                                            <option value="" disabled disabled></option>
                                                <?php foreach ($data['distritos'] as $row) { ?>
                                                    <option value="<?php echo $row['cod']; ?>"><?php echo $row['distrito']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="direccion_llegada" style="margin-bottom: 0rem;">Dirección</label>
                                            <input id="direccion_llegada" class="form-control" type="text" name="direccion_llegada" placeholder="Dirección">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                                <h3 class="card-title">ADJUNTAR DOCUMENTOS</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                            </div>

                            <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                                <h3 class="card-title">CONCEPTO DE COMPROBANTE</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                            <button class="btn btn-primary" type="button" onclick="modalProductosGuia();"><i class="fas fa-plus"></i></button>
                                <table class="table table-sm" id="tblProductosGuia" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Descripción</th>
                                            <th>Unidad</th>
                                            <th>Precio</th>
                                            <th>Cant.</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblProductosGuiaDetalle">
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-header" style="padding: 3px; border-bottom: 1px solid #ddd;">
                                <h3 class="card-title">DATOS GENERALES</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-2 col-sm-6">
                                        <div class="form-group">
                                            <label for="peso_bruto_total" style="margin-bottom: 0rem;">Peso Bruto Total</label>
                                            <input id="peso_bruto_total" class="form-control" type="number" name="peso_bruto_total" placeholder="Peso bruto total">
                                        </div>
                                    </div>
                                    <div class="col-xl-1 col-sm-6 d-none" id="numero_bultos_input">
                                        <div class="form-group">
                                            <label for="numero_bultos" style="margin-bottom: 0rem;">N° Bultos</label>
                                            <input id="numero_bultos" class="form-control" type="number" name="numero_bultos" placeholder="Peso bruto total">
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-sm-6">
                                        <div class="form-group">
                                            <label for="notas" style="margin-bottom: 0rem;">Notas</label>
                                            <textarea id="notas" class="form-control" name="notas" placeholder="Escriba algunas notas..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary" id="registrar_guia_electronica" type="button" onclick="generarGuiaElectronica(event);">Registrar Guia</button>
                        </form>

                        <div id="verProductos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h5 class="modal-title text-white" id="ProductName"><i class="nav-icon fas fa-cubes"></i> LISTA DE PRODUCTOS</h5>
                                        <button class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="overflow-y: auto;">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="ProductosTBL" width="100%" cellspacing="0" style="font-size: 12px;">
                                                <thead style="background-color: #f0f0f0;">
                                                    <tr>
                                                        <th style="text-align: center; width: 10px;">Id</th>
                                                        <th style="text-align: center;">Descripción</th>
                                                        <th style="text-align: center; width: 10px;">Código</th>
                                                        <th style="text-align: center; width: 10px;">Unidad</th>
                                                        <th style="text-align: center; width: 10px;">P. Compra</th>
                                                        <th style="text-align: center; width: 10px;">P. Venta</th>
                                                        <th style="text-align: center; width: 10px;">Cantidad</th>
                                                        <th style="text-align: center; width: 10px;">Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="ProductosTBLJS" style="font-size: 12px;">
                                                </tbody>
                                            </table>
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
                        <script src="<?php echo base_url; ?>Assets/js/guia_electronica.js"></script>