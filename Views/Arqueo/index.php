<?php include "Views/Templates/header.php" ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Arqueo de Caja</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">RESUMEN DE ARQUEO</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo base_url;?>Arqueo/pdfPorFechas" method="POST" target="_blank">
                                <div class="row text-center d-flex justify-content-center align-items-center">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <div class="form-group">
                                                    <label for="min">DESDE</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="date" class="form-control float-right" id="desde" name="desde" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d')?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <div class="form-group">
                                                    <label for="min">HASTA</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="far fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                        <input type="date" class="form-control float-right" id="hasta" name="hasta" value="<?php date_default_timezone_set('America/Lima'); echo date('Y-m-d')?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <div class="form-group">
                                                    <label for="min">TIPOS DE PEDIDOS</label>
                                                    <select name="usuario" id="usuario" class="form-control productos" style="width: 100%;">
                                                        <?php foreach ($data['usuarios'] as $row) { ?>
                                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                                                        <?php } ?>
                                                        <option value="-1">GENERAL</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-warning btn-block">GENERAR RESUMEN</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <!--<div class="card-header py-3">
                    <button class="btn btn-danger d-none" type="button" style="float: right;" onclick="UltimoCorte();" id="c_caja">Cerrar Caja</button>
                    <button class="btn btn-warning d-none" type="button" style="float: right;" onclick="PrimerCorte();" id="p_caja">Primer Corte</button>
                    <button class="btn btn-success" type="button" style="float: right;" onclick="ArqueoCaja();" id="a_caja">Abrir Caja</button>
                    <div class="row">
                        <div class="form-group">
                            <button class="btn btn-secondary" type="button" style="float: right;" onclick="getStockAlmacen();" id="v_stock">Verificar Salida</button>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="button" style="float: right;" onclick="VerificarStock();" id="v_stock">Verificar Stock</button>
                        </div>
                    </div>
                </div>-->
                <div class="card-body">
                    <table class="table table-sm" id="tblArqueoCaja" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Monto Inicial</th>
                                <th>Primer Corte</th>
                                <th>Ultimo Corte</th>
                                <th>Fecha de Apertura</th>
                                <th>Fecha de Cierre</th>
                                <th>Total de ventas</th>
                                <th>Monto Total</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div id="abrir_caja" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Arqueo Caja</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmAbrirCaja" onsubmit="abrirArqueoCaja(event);">
                            <div class="form-group">
                                <div class="row text-center" id="campos_billetes">
                                    <div class="col-md-3">
                                        <input type="hidden" name="id" id="id">
                                        <label id="200l">200</label>
                                        <input id="b_200" class="form-control" type="text" name="b_200">
                                    </div>
                                    <div class="col-md-3">
                                        <label id="100l">100</label>
                                        <input id="b_100" class="form-control" type="text" name="b_100">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="50l">50</label>
                                        <input id="b_50" class="form-control" type="text" name="b_50">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="20l">20</label>
                                        <input id="b_20" class="form-control" type="text" name="b_20">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="10l">10</label>
                                        <input id="b_10" class="form-control" type="text" name="b_10">
                                    </div>
                                </div>
                                <div class="row text-center" id="campos_monedas">
                                    <div class="col-md-2">
                                        <label id="5l">5</label>
                                        <input id="m_5" class="form-control" type="text" name="m_5">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="2l">2</label>
                                        <input id="m_2" class="form-control" type="text" name="m_2">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="1l">1</label>
                                        <input id="m_1" class="form-control" type="text" name="m_1">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="050l">0.50</label>
                                        <input id="m_050" class="form-control" type="text" name="m_050">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="020l">0.20</label>
                                        <input id="m_020" class="form-control" type="text" name="m_020">
                                    </div>
                                    <div class="col-md-2">
                                        <label id="010l">0.10</label>
                                        <input id="m_010" class="form-control" type="text" name="m_010">
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="form-group">
                                        <h3 id="total_dinero" style="color: blue; background-color: lightgray; padding: 10px; font-size: 24px;">TOTAL: S/. 0.00</h3>
                                    </div>
                                </div>
                                <div id="ocultar_campos">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="total_ventas">Total Ventas</label>
                                                <input type="text" name="total_ventas" id="total_ventas" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="monto_general">Monto Total</label>
                                                <input type="text" name="monto_general" id="monto_general" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" id="btnAccion">Abrir Caja</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="abrir_primer_corte" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Primer Corte</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmPrimerCorte" onsubmit="CrearPrimerCorte(event);">
                                <div class="form-group">
                                    <div class="row text-center" id="campos_billetes">
                                        <div class="col-md-3">
                                            <input type="hidden" name="id_pcorte" id="id_pcorte">
                                            <label id="200l">200</label>
                                            <input id="b_200c" class="form-control" type="text" name="b_200c">
                                        </div>
                                        <div class="col-md-3">
                                            <label id="100l">100</label>
                                            <input id="b_100c" class="form-control" type="text" name="b_100c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="50l">50</label>
                                            <input id="b_50c" class="form-control" type="text" name="b_50c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="20l">20</label>
                                            <input id="b_20c" class="form-control" type="text" name="b_20c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="10l">10</label>
                                            <input id="b_10c" class="form-control" type="text" name="b_10c">
                                        </div>
                                    </div>
                                    <div class="row text-center" id="campos_monedas">
                                        <div class="col-md-2">
                                            <label id="5l">5</label>
                                            <input id="m_5c" class="form-control" type="text" name="m_5c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="2l">2</label>
                                            <input id="m_2c" class="form-control" type="text" name="m_2c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="1l">1</label>
                                            <input id="m_1c" class="form-control" type="text" name="m_1c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="050l">0.50</label>
                                            <input id="m_050c" class="form-control" type="text" name="m_050c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="020l">0.20</label>
                                            <input id="m_020c" class="form-control" type="text" name="m_020c">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="010l">0.10</label>
                                            <input id="m_010c" class="form-control" type="text" name="m_010c">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <h3 id="total_dinero_corte" style="color: blue; background-color: lightgray; padding: 10px; font-size: 24px;">TOTAL: S/. 0.00</h3>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" id="btnAccion">Primer Corte</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="obtenerDetallePedidos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title text-white" id="title">Detalle Pedido</h5>
                                    <button class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm" width="100%" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                            <thead>
                                                <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblDetalleOP"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <div id="ultimoCorte" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Cierre Caja</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="frmUltimoCorte" onsubmit="CerraCaja(event);">
                                <div class="form-group">
                                    <div class="row text-center" id="campos_billetes">
                                        <div class="col-md-3">
                                            <input type="hidden" name="id_ucorte" id="id_ucorte">
                                            <label id="200l">200</label>
                                            <input id="b_200cc" class="form-control" type="text" name="b_200cc">
                                        </div>
                                        <div class="col-md-3">
                                            <label id="100l">100</label>
                                            <input id="b_100cc" class="form-control" type="text" name="b_100cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="50l">50</label>
                                            <input id="b_50cc" class="form-control" type="text" name="b_50cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="20l">20</label>
                                            <input id="b_20cc" class="form-control" type="text" name="b_20cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="10l">10</label>
                                            <input id="b_10cc" class="form-control" type="text" name="b_10cc">
                                        </div>
                                    </div>
                                    <div class="row text-center" id="campos_monedas">
                                        <div class="col-md-2">
                                            <label id="5l">5</label>
                                            <input id="m_5cc" class="form-control" type="text" name="m_5cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="2l">2</label>
                                            <input id="m_2cc" class="form-control" type="text" name="m_2cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="1l">1</label>
                                            <input id="m_1cc" class="form-control" type="text" name="m_1cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="050l">0.50</label>
                                            <input id="m_050cc" class="form-control" type="text" name="m_050cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="020l">0.20</label>
                                            <input id="m_020cc" class="form-control" type="text" name="m_020cc">
                                        </div>
                                        <div class="col-md-2">
                                            <label id="010l">0.10</label>
                                            <input id="m_010cc" class="form-control" type="text" name="m_010cc">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <h3 id="total_ultimo_corte" style="color: blue; background-color: lightgray; padding: 10px; font-size: 24px;">TOTAL: S/. 0.00</h3>
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit" id="btnAccion">Cerrar Caja</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="getStockAlmacen" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white" id="title">Detalle Pedido</h5>
                            <button class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="tblProductosStock" width="auto" cellspacing="0" style="text-align: center; vertical-align: middle;">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Foto</th>
                                            <th>Descripción</th>
                                            <th>Almacen</th>
                                            <th>Stock</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btnAccion" class="btn btn-primary" type="button" onclick="generarSalida(event);">Registrar</button>
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "Views/Templates/footer.php" ?>
            <script>
                const base_url = "<?php echo base_url; ?>"
            </script>
            <script src="<?php echo base_url; ?>Assets/js/arqueo_caja.js"></script>
            <script src="<?php echo base_url; ?>Assets/js/alertas.js"></script>