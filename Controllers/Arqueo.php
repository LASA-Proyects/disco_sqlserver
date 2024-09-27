<?php
class Arqueo extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'caja');
        if(!empty($verificar) || $id_usuario == 1){
            $data['usuarios'] = $this->model->getUsuarios();
            $this->views->getView($this, "index",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function retorno_almacen()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL ALMACEN');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "retorno_almacen");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function retorno_almacen_boleteria()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL ALMACEN');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "retorno_almacen_boleteria");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }
    
    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }

    public function listar()
    {
        $data = $this->model->getArqueo();
        
        if (!empty($data)) {
            foreach ($data as &$item) {
                if ($item['estado'] == 1) {
                    $item['estado'] = '<span class="badge badge-success">Abierto</span>';
                    $item['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                            <button class="btn btn-warning btn-sm" type="button" onclick="apertura('.$item['id'].');"><i class="fas fa-pencil-alt"></i> Apertura</button>
                                        </div>';
                } elseif ($item['estado'] == 2) {
                    $item['estado'] = '<span class="badge badge-Warning">1° Corte</span>';
                    $item['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="editarPrimerCorte('.$item['id'].');"><i class="fas fa-pencil-alt"></i> 1° Corte</button>
                                        </div>';
                } else {
                    $item['estado'] = '<span class="badge badge-danger">Cerrado</span>';
                    $item['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                            <button class="btn btn-warning btn-sm" type="button" onclick="apertura('.$item['id'].');"><i class="fas fa-pencil-alt"></i> Apertura</button>'.
                                            '<button class="btn btn-primary btn-sm" type="button" onclick="editarPrimerCorte('.$item['id'].');"><i class="fas fa-pencil-alt"></i> 1° Corte</button>'.
                                            '<button class="btn btn-info btn-sm" type="button" onclick="editarUltimoCorte('.$item['id'].');"><i class="fas fa-pencil-alt"></i> 2° Corte</button>
                                        </div>';
                }
            }
        }
    
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarStock()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $usuario = $this->model->getUsuarioId($id_usuario);
        $id_almacen = $usuario['id_almacen'];
        $data = $this->model->getProductosStock($id_almacen);
        for ($i=0; $i < count($data); $i++){
            $data[$i]['foto'] = '<img class="img-thumbnail" src="'.base_url."Assets/img/".$data[$i]['foto'].'" width="56">';
            $data[$i]['seleccion'] = '<div>
                <input class="form-check-input" type="checkbox" name="product_select[]" id="product_select_'.$data[$i]['id_producto'].'" value="'.$data[$i]['id_producto'].'">
            <div/>';
            $data[$i]['cantidad'] = '<div>
                <input id="cant_stock_'.$data[$i]['id_producto'].'" class="form-control" type="number" name="cant_stock[]" data-product-id="'.$data[$i]['id_producto'].'">
            <div/>';
            $data[$i]['observacion'] = '<div>
                <textarea class="form-control" name="product_observacion_'.$data[$i]['id_producto'].'" id="product_observacion_'.$data[$i]['id_producto'].'" rows="1" placeholder="Observación"></textarea>
            <div/>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function abrirArqueo()
    {
        $monto_inicial = 0;
        $b_200 = $_POST['b_200'];
        $b_100 = $_POST['b_100'];
        $b_50 = $_POST['b_50'];
        $b_20 = $_POST['b_20'];
        $b_10 = $_POST['b_10'];
        $m_5 = $_POST['m_5'];
        $m_2 = $_POST['m_2'];
        $m_1 = $_POST['m_1'];
        $m_050 = $_POST['m_050'];
        $m_020 = $_POST['m_020'];
        $m_010 = $_POST['m_010'];
        $id = $_POST['id'];
        $camposValores = array(
            'b_200' => 200,
            'b_100' => 100,
            'b_50' => 50,
            'b_20' => 20,
            'b_10' => 10,
            'm_5' => 5,
            'm_2' => 2,
            'm_1' => 1,
            'm_050' => 0.50,
            'm_020' => 0.20,
            'm_010' => 0.10
        );

        foreach ($camposValores as $campo => $valor) {
            if (!empty($_POST[$campo])) {
                $monto_inicial += $_POST[$campo] * $valor;
            }
        }
        $fecha_apertura = date('Y-m-d');
        $id_usuario = $_SESSION['id_usuario'];
        $id_almacen = $_SESSION['almacen'];
        $id = trim($_POST['id']);
        $ver_stock = $this->model->VerificarStock();
        $verificar = $this->model->verificarCajaAbierta();
        if($_POST['id'] == ""){
            if($ver_stock[0]['estado'] == 1){
                if(empty($verificar)){
                    $data = $this->model->registrarArqueo($id_usuario, $id_almacen ,$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_inicial, $fecha_apertura);
                    if($data == "ok"){
                        $msg = array('msg' => 'Caja abierta con éxito', 'icono' => 'success');
                    }else{
                        $msg = array('msg' => 'Error al abrir la caja', 'icono' => 'error');
                    }
                }else{
                    $msg = array('msg' => 'La Caja ya esta abierta', 'icono' => 'warning');
                }
            }else{
                $msg = array('msg' => 'Stock no Verificado', 'icono' => 'warning');
            }
        }else{
            $traerCaja = $this->model->traerCaja($id);
            if($traerCaja["estado"] == 0){
                $data = $this->model->modificarArqueo($id,$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_inicial);
                if($data == "ok"){
                    $msg = array('msg' => 'Apertura modificada con éxito', 'icono' => 'success');
                }else{
                    $msg = array('msg' => 'Error al modificar la apertura', 'icono' => 'error');
                }
            }else{
                $msg = array('msg' => 'La caja aun no fue cerrada', 'icono' => 'warning');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function primerCorte()
    {
        $id = $_POST['id_pcorte'];
        $monto_corte = 0;
        $b_200 = $_POST['b_200c'];
        $b_100 = $_POST['b_100c'];
        $b_50 = $_POST['b_50c'];
        $b_20 = $_POST['b_20c'];
        $b_10 = $_POST['b_10c'];
        $m_5 = $_POST['m_5c'];
        $m_2 = $_POST['m_2c'];
        $m_1 = $_POST['m_1c'];
        $m_050 = $_POST['m_050c'];
        $m_020 = $_POST['m_020c'];
        $m_010 = $_POST['m_010c'];
        $camposValores = array(
            'b_200c' => 200,
            'b_100c' => 100,
            'b_50c' => 50,
            'b_20c' => 20,
            'b_10c' => 10,
            'm_5c' => 5,
            'm_2c' => 2,
            'm_1c' => 1,
            'm_050c' => 0.50,
            'm_020c' => 0.20,
            'm_010c' => 0.10
        );

        foreach ($camposValores as $campo => $valor) {
            if (!empty($_POST[$campo])) {
                $monto_corte += $_POST[$campo] * $valor;
            }
        }
        $fecha_corte = date('Y-m-d');
        $id_usuario = $_SESSION['id_usuario'];
        if($_POST['id_pcorte'] == ""){
            $inicial = $this->model->getMontoInicial($id_usuario);
            $data = $this->model->primerCorte($inicial['id'],$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha_corte, $monto_corte, 1);
            $this->model->actualizarCorteArqueo($monto_corte, $inicial['id']);
            if($data == "ok"){
                $msg = array('msg' => 'Primer Corte Realizado', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Error al Realizar Primer Corte', 'icono' => 'error');
            }
        }else{
            $cArqPCorte = $this->model->consultarArqueoPrimCorte($id);
            $traerCaja = $this->model->traerCaja($cArqPCorte['id_arqueo']);
            if($traerCaja['estado'] == 0){
                $data = $this->model->editarPrimerCorte($id,$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_corte);
                if($data == "ok"){
                    $this->model->modificarMontoArqueo($monto_corte, $cArqPCorte['id_arqueo']);
                    $msg = array('msg' => 'Primer Corte Modificado', 'icono' => 'success');
                }else{
                    $msg = array('msg' => 'Error al Modificar Primer Corte', 'icono' => 'error');
                }
            }else{
                $msg = array('msg' => 'La caja aun no fue cerrada', 'icono' => 'warning');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function cerrarCaja()
    {
        $id = $_POST["id_ucorte"];
        $monto_cierre = 0;
        $b_200 = $_POST['b_200cc'];
        $b_100 = $_POST['b_100cc'];
        $b_50 = $_POST['b_50cc'];
        $b_20 = $_POST['b_20cc'];
        $b_10 = $_POST['b_10cc'];
        $m_5 = $_POST['m_5cc'];
        $m_2 = $_POST['m_2cc'];
        $m_1 = $_POST['m_1cc'];
        $m_050 = $_POST['m_050cc'];
        $m_020 = $_POST['m_020cc'];
        $m_010 = $_POST['m_010cc'];
        $camposValores = array(
            'b_200cc' => 200,
            'b_100cc' => 100,
            'b_50cc' => 50,
            'b_20cc' => 20,
            'b_10cc' => 10,
            'm_5cc' => 5,
            'm_2cc' => 2,
            'm_1cc' => 1,
            'm_050cc' => 0.50,
            'm_020cc' => 0.20,
            'm_010cc' => 0.10
        );

        foreach ($camposValores as $campo => $valor) {
            if (!empty($_POST[$campo])) {
                $monto_cierre += $_POST[$campo] * $valor;
            }
        }
        if($_POST['id_ucorte'] == ""){
            $fecha_ult_corte = date('Y-m-d');
            $id_usuario = $_SESSION['id_usuario'];
            $monto_final = $this->model->getMontoTotalVentas($id_usuario);
            $total_ventas= $this->model->getTotalVentas($id_usuario);
            $inicial = $this->model->getMontoInicial($id_usuario);
            $primer_corte = $this->model->getMontoPrimerCorte($inicial['id']);
            $general = $primer_corte['monto_total'] + $monto_cierre;
            $data = $this->model->actualizarArqueo($monto_cierre, $fecha_ult_corte, $total_ventas['total'], $general, $inicial['id']);
            $this->model->primerCorte($inicial['id'],$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha_ult_corte, $monto_cierre, 2);
            if($data == "ok"){
                $this->model->actualizarApertura($id_usuario);
                $this->model->actualizarEstadoStock(0);
                $msg = array('msg' => 'Caja Cerrada con éxito', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Error al cerrar la caja', 'icono' => 'error');
            }
        }else{
            $data = $this->model->editarUltimoCorte($id,$b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_cierre);
            $this->model->modificarMontoCierre($monto_cierre, $id);
            if($data == "ok"){
                $msg = array('msg' => 'Último Corte Modificado', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Error al Modificar Último Corte', 'icono' => 'error');
            }
        }

        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getArqueoVentas(){
        $id_usuario = $_SESSION['id_usuario'];
        $data['monto_total'] = $this->model->getMontoTotalVentas($id_usuario);
        $data['total_ventas'] = $this->model->getTotalVentas($id_usuario);
        $data['inicial'] = $this->model->getMontoInicial($id_usuario);
        $data['monto_general'] = $data['monto_total']['total'];
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verEstadoArqueo()
    {
        $cajaAbiertaData = $this->model->verificarCajaAbierta();
        $stockData = $this->model->VerificarStock();
    
        $data = [
            'cajaAbierta' => $cajaAbiertaData,
            'stock' => $stockData,
        ];
    
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pdfPorFechas()
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarioId($id_usu);
        if ($id_usu == "-1") {
            $data = $this->model->getRangoFechasGen($desde, $hasta);
            $pagos_fecha = $this->model->getPagosFechaGen($desde, $hasta);
            $primer_corte = $this->model->getPrimerCorteFechas($desde, $hasta);
            $ultimo_corte = $this->model->getUltimoCorteFechas($desde, $hasta);
        } else {
            $data = $this->model->getRangoFechas($desde, $hasta, $getUsu['id']);
            $pagos_fecha = $this->model->getPagosFecha($desde, $hasta, $getUsu['id']);
            $primer_corte = $this->model->getPrimerCorteFechas($desde, $hasta);
            $ultimo_corte = $this->model->getUltimoCorteFechas($desde, $hasta);
        }
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_Reporte('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $maxHeight = 230;
        $currentHeight = 0;
        $total = 0.00;
        $maxRowsPerPage = 20;
    
        foreach ($data as $row) {
            $cellHeight = 10;
    
            if ($currentHeight + $cellHeight > $maxHeight) {
                $pdf->AddPage();
                $currentHeight = 0;
            }
            $pdf->Cell(20, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(110, 10, $row['descripcion'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['cantidad'], 1, 0, 'C');
            $pdf->Cell(20, 10, 'S/. ' . $row['precio'], 1, 0, 'C');
            $sub_total = $row['cantidad'] * $row['precio'];
            $pdf->Cell(20, 10, 'S/. ' . number_format($sub_total, 2), 1, 1, 'C');
            $currentHeight += $cellHeight;
            $total += $sub_total;
        }
    
        if ($currentHeight + 300 > $maxHeight) {
            $pdf->AddPage();
            $currentHeight = 0;
        }
    
        $pdf->Cell(0, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'R');
        $pdf->Cell(0, -10, 'Fecha: ' . $desde . ' / ' . $hasta, 0, 1, 'L');
        
        if ($id_usu == "-1") {
            $pdf->Cell(0, 20, 'RESUMEN GENERAL', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 20, 'Vendedor: ' . $getUsu['nombre'], 0, 1, 'L');
        }
    
        $anchoPagina = $pdf->GetPageWidth();
        $tipo_pago_e = $anchoPagina * 0.138;
        $datos_tipop_e = $anchoPagina * 0.1;
        $pdf->SetFont('Arial', 'B', 9);
        $tipo_pago = $this->model->getTipoPagos();
        $pdf->SetFillColor(200, 200, 200);
        $anchoTablaPagos = $tipo_pago_e + $datos_tipop_e * 2;
        $centrarTablaX = ($pdf->GetPageWidth() - $anchoTablaPagos) / 2;
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(71, 8, utf8_decode('RESUMEN DE PAGOS'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->Cell(29, 5, utf8_decode('TIPO PAGO'), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode('CANTIDAD'), 1, 0, 'C');
        $pdf->Cell(21, 5, utf8_decode('SUB TOTAL'), 1, 1, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        foreach ($tipo_pago as $row_pago) {
            $pdf->SetX($centrarTablaX);
            $pdf->Cell($tipo_pago_e, 6, utf8_decode($row_pago['nombre']), 1, 0, 'C', true);
            $nombre_tipo_pago = $row_pago['nombre'];
            $valor_tipo_pago = $pagos_fecha[0][$nombre_tipo_pago];
            $pdf->Cell($datos_tipop_e, 6, $valor_tipo_pago, 1, 0, 'C', true);
            $nombre_suma = 'cant_' . $row_pago['nombre'];
            $valor_suma = $pagos_fecha[0][$nombre_suma];
            $pdf->Cell($datos_tipop_e, 6, $valor_suma, 1, 1, 'C', true);
        }
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(110, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'C');

        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(71, 4, utf8_decode('REMESAS'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->Cell(71, 4, utf8_decode('TURNO 01'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->Cell(35.5, 4, utf8_decode('DENOMINACIÓN'), 1, 0, 'C');
        $pdf->Cell(35.5, 4, utf8_decode('CANTIDAD'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 9);
        $denominaciones = [
            "BILLETES DE S/. 200", "BILLETES DE S/. 100", "BILLETES DE S/. 50", "BILLETES DE S/. 20", "BILLETES DE S/. 10",
            "MONEDAS DE S/. 5", "MONEDAS DE S/. 2", "MONEDAS DE S/. 1", "MONEDAS DE S/. 0.50", "MONEDAS DE S/. 0.20", "MONEDAS DE S/. 0.10"
        ];
        
        $cantidades_pc = isset($primer_corte[0])
        ? [
            $primer_corte[0]["b_200"], $primer_corte[0]["b_100"], $primer_corte[0]["b_50"],
            $primer_corte[0]["b_20"], $primer_corte[0]["b_10"], $primer_corte[0]["m_5"],
            $primer_corte[0]["m_2"], $primer_corte[0]["m_1"], $primer_corte[0]["m_050"],
            $primer_corte[0]["m_020"], $primer_corte[0]["m_010"]
        ]
        : array_fill(0, 11, 0);
        
        foreach ($denominaciones as $index => $denominacion) {
            $pdf->SetX($centrarTablaX);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(35.5, 3.5, $denominacion, 1, 0, 'C', true);
            $pdf->Cell(35.5, 3.5, $cantidades_pc[$index], 1, 1, 'C', true);
        }

        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 10);
        $total_pc = isset($primer_corte[0]["monto_total"]) ? $primer_corte[0]["monto_total"] : 0;
        $pdf->Cell(110, 10, 'Total: S/. ' . number_format($total_pc, 2), 0, 1, 'C');

        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(71, 4, utf8_decode('REMESAS'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->Cell(71, 4, utf8_decode('TURNO 02'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->Cell(35.5, 4, utf8_decode('DENOMINACIÓN'), 1, 0, 'C');
        $pdf->Cell(35.5, 4, utf8_decode('CANTIDAD'), 1, 1, 'C');
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 9);
        $denominaciones = [
            "BILLETES DE S/. 200", "BILLETES DE S/. 100", "BILLETES DE S/. 50", "BILLETES DE S/. 20", "BILLETES DE S/. 10",
            "MONEDAS DE S/. 5", "MONEDAS DE S/. 2", "MONEDAS DE S/. 1", "MONEDAS DE S/. 0.50", "MONEDAS DE S/. 0.20", "MONEDAS DE S/. 0.10"
        ];
        
        $cantidades_uc = isset($ultimo_corte[0])
        ? [
            $ultimo_corte[0]["b_200"], $ultimo_corte[0]["b_100"], $ultimo_corte[0]["b_50"],
            $ultimo_corte[0]["b_20"], $ultimo_corte[0]["b_10"], $ultimo_corte[0]["m_5"],
            $ultimo_corte[0]["m_2"], $ultimo_corte[0]["m_1"], $ultimo_corte[0]["m_050"],
            $ultimo_corte[0]["m_020"], $ultimo_corte[0]["m_010"]
        ]
        : array_fill(0, 11, 0);
        
        foreach ($denominaciones as $index => $denominacion) {
            $pdf->SetX($centrarTablaX);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(35.5, 3.5, $denominacion, 1, 0, 'C', true);
            $pdf->Cell(35.5, 3.5, $cantidades_uc[$index], 1, 1, 'C', true);
        }

        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 10);
        $total_uc = isset($ultimo_corte[0]["monto_total"]) ? $ultimo_corte[0]["monto_total"] : 0;
        $pdf->Cell(110, 10, 'Total: S/. ' . number_format($total_uc, 2), 0, 1, 'C');



                    
        $pdf->Output("I", "Liquidacion_Ventas.pdf", true);
    }

    public function VerificarStock()
    {
        $data['stock'] = $this->model->VerificarStock();
        $estado = isset($data['stock']['estado']) ? (int)$data['stock']['estado'] : -1;
        if ($estado === 0 || $estado === -1) {
            $this->model->actualizarEstadoStock(1);
            $msg = array('msg' => 'Stock Verificado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al Verificar el Stock', 'icono' => 'error');
        }
        
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarSalida()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $almacen = $_SESSION['almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $data_series = $this->model->getSeries();
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d h:i:s");
        try {
            $venta = $this->model->registrarVenta($id_usuario, 99998, 6, $data[0]['fecha_operacion'], 0, 3, $almacen, 1, $data_series['serie'], $data_series['correlativo'], 0 ,$fecha_actual);
            $this->model->registrarLog($_SESSION['id_usuario'],$venta['sql'], $fecha_actual, $_SESSION['id_almacen'], 'detalle_compras');
        } catch (PDOException $e) {
            $msg = array('msg' => 'Error al registrar la Salida por Retorno: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $fecha_actual, $_SESSION['id_almacen'], 'detalle_compras');
            echo json_encode($msg);
            die();
        }
        try {
            $compra = $this->model->registrarCompra($id_usuario, $venta['id'], 99998, 12,$data[0]['fecha_operacion'], 1, 3, 1, NULL, $data_series['serie'], $data_series['correlativo'], 0,$fecha_actual);
            $this->model->registrarLog($_SESSION['id_usuario'],$venta['sql'], $fecha_actual, $_SESSION['id_almacen'], 'detalle_compras');
        } catch (PDOException $e) {
            $msg = array('msg' => 'Error al registrar el Ingreso por Retorno: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $fecha_actual, $_SESSION['id_almacen'], 'detalle_compras');
            echo json_encode($msg);
            die();
        }
        foreach ($data as $row){
            try{
            $this->model->registrarDetalleVenta($venta['id'],$row["id_producto"], $almacen, $row["cantidad"], 0, 0, $row["observacion"]);
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar el Detalle Venta: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                echo json_encode($msg);
                die();
            }
            try{
            $this->model->registrarDetalleCompra($compra['id'], $venta['id'], $row["id_producto"], 1, $row["cantidad"], 0, 0, $row["observacion"]);
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar el Detalle Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                echo json_encode($msg);
                die();
            }
            $correlativoAum = str_pad(intval($data_series['correlativo']) + 1, strlen($data_series['correlativo']), '0', STR_PAD_LEFT);
            $this->model->actualizarSerieDev($correlativoAum);
        }
        $msg = array('msg' => 'pedido registrado', 'icon' => 'success');
        $msg['serie'] = $data_series['serie'];
        $msg['correlativo'] = $data_series['correlativo'];
        echo json_encode($msg);
        die();
    }

    public function generarPdfSalida($id_salida)
    {
        $salida = $this->model->getPedidosId($id_salida);
        $detalle_salida = $this->model->getDetalleSalida($salida['id']);
        $empresa = $this->model->getEmpresa();
        require('Libraries/Ticket/ticket.php');
        $pdf = new PDF_Code128('P','mm',array(80,258));
        $pdf->SetMargins(4,10,4);
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',9);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper($empresa['nombre'])),0,'C',false);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0,5,utf8_decode("RUC:".$empresa['ruc']),0,'C',false);
        $pdf->MultiCell(0,5,utf8_decode($empresa['direccion']),0,'C',false);
        $pdf->MultiCell(0,5,utf8_decode("Teléfono: ".'+51 '.$empresa['telefono']),0,'C',false);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("SALIDA POR DEVOLUCIÓN")),0,'C',false);
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("(".$salida['nombre_ini']. " - " . $salida['nombre_fin']. ")")),0,'C',false);
        $pdf->SetFont('Arial','B',10.5);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper($salida['serie']." - ".$id_salida)),0,'C',false);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
        $pdf->Ln(5);

        $pdf->Cell(38.5, 5, utf8_decode("DESCRIPCIÓN"), 0, 0, 'C');
        $pdf->Cell(37, 5, utf8_decode("CANTIDAD"), 0, 1, 'C');
        
        foreach ($detalle_salida as $row) {
            $pdf->Cell(55.5, 4, utf8_decode($row['descripcion']), 0, 0, 'L');
            $pdf->Cell(17, 4, utf8_decode($row['cantidad']), 0, 1, 'A');
            if($row['observacion'] !== NULL && $row['observacion'] !== ''){
                $pdf->SetFont('Arial','B',9);
                $pdf->MultiCell(0, 4, utf8_decode("OBSERVACIÓN: ".$row['observacion']), 0, 'A', false);
                $pdf->SetFont('Arial','',9);
            }
            $pdf->Ln(2);
            $pdf->Cell(0, 1, utf8_decode("................................................................................"), 0, 0, 'A');
            $pdf->Ln(3);
        }
    
        $pdf->Ln(8);
        $pdf->Cell(0, 5, utf8_decode("Realizado por: ". $salida['nombre_usuario']), 0, 1, 'L');
        $pdf->Cell(0, 5, utf8_decode("Fecha: " . $salida['fecha']), 0, 1, 'L');
        $pdf->Ln(8);
        $pdf->Cell(0, 5, utf8_decode("Autorizado por:"), 0, 1, 'L');
        $pdf->Ln(8);
        $pdf->Cell(0, 2, utf8_decode("Firma:         ________________________"), 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->Cell(0, 5, utf8_decode("Nombre:     ________________________"), 0, 1, 'L');
        $pdf->Ln(3);
        $pdf->Cell(0, 5, utf8_decode("Fecha:        ____________"), 0, 1, 'L');
        
        $pdf->Output("I", "VENTA.pdf", true);
    
        $pdf->Output("I","VENTA.pdf",true);
    }
    
    public function editarApertura($id)
    {
        $data = $this->model->editarApertura($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editarPrimerCorte($id)
    {
        $data = $this->model->primerCorteEdit($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editarUltimoCorte($id)
    {
        $data = $this->model->ultimoCorteEdit($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consutlarEstados()
    {
        $data = $this->model->consutlarEstados($_SESSION['id_usuario'], $_SESSION['almacen']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>