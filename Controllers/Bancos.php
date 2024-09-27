<?php
require'C:/xampp/htdocs/disco2023/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
class Bancos extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function ingresos()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 4, 6);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $this->views->getView($this, "ingresos",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function salidas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 4, 7);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $data['tipo_documentos'] = $this->model->getTipoDoc();
            $this->views->getView($this, "salidas",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }

    }

    public function terminal_ingresos()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERM. BANCO INGRESOS');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $data['tipo_documentos'] = $this->model->getTipoDoc();
            $this->views->getView($this, "terminal_ingresos",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function terminal_salidas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERM. BANCO SALIDAS');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $data['tipo_documentos'] = $this->model->getTipoDoc();
            $this->views->getView($this, "terminal_salidas",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function transferencias()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 4, 8);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $data["bancos_todos"] = $this->model->getBancosTodos();
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $this->views->getView($this, "transferencias",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
        
    }

    public function terminal_transferencias()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERM. BANCO TRANSF.');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $data["bancos_todos"] = $this->model->getBancosTodos();
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $this->views->getView($this, "terminal_transferencias",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
        
    }

    public function terminal_transferencias_boleteria()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERMINAL TRANSFERENCIAS');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $data["glosas"] = $this->model->getGlosas();
            $this->views->getView($this, "terminal_transferencias_boleteria",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
        
    }

    public function movimientos()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 4, 13);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $this->views->getView($this, "movimientos",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function terminal_histo_transf()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERMINAL HISTORIAL TRANSF');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $this->views->getView($this, "terminal_histo_transf",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function terminal_histo_transf_boleteria()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_almacen = $_SESSION['id_almacen'];
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERMINAL HISTORIAL TRANSF');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data["bancos"] = $this->model->getBancos($id_almacen);
            $id_usuario = $_SESSION['id_usuario'];
            $this->views->getView($this, "terminal_histo_transf_boleteria",$data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function buscarKardexBanco()
    {
        $id_banco = $_POST['banco'];
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        if($id_banco < 0){
            $data = $this->model->buscarKardexBancoGen($fecha_desde, $fecha_hasta);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }else{
            $data = $this->model->buscarKardexBanco($id_banco, $fecha_desde, $fecha_hasta);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function RegistrarMovimiento()
    {
        $banco_tipo = $_POST['banco_tipo'];
        $id_banco_ini = !empty($_POST['banco']) ? (int)$_POST['banco'] : null;
        $numero_operacion = !empty($_POST['numero_operacion']) ? $_POST['numero_operacion'] : null;
        $monto_ingreso = $_POST['monto_ingreso'];
        $glosa = isset($_POST['glosa']) ? $_POST['glosa'] : null;
        $id_usuario = $_SESSION['id_usuario'];
        $id_almacen = $_SESSION['id_almacen'];
        $fecha = $_POST['fecha'];
        $ingresoFecha = date("Y-m-d h:i:s");
        $monto_total = 0;
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
                $monto_total += $_POST[$campo] * $valor;
            }
        }

        if(!empty($numero_operacion) && !empty($glosa)){
            if($banco_tipo == 1){
                if($id_banco_ini != NULL){
                    if($monto_ingreso != NULL){
                        if($monto_total != 0){
                            if($monto_ingreso == $monto_total){
                                try {
                                    $datos = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 1);
                                    $this->model->registrarLog($_SESSION['id_usuario'],$datos['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al Registrar Ingreso Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                    die();
                                }
                                $msg = array('msg' => 'Ingreso registrado con éxito', 'icono' => 'success', 'id_banco' => $datos['id']);
                            }else{
                                $msg = array('msg' => 'Los montos no coinciden', 'icono' => 'warning', 'dato' => 'no');
                            }
                        }else{
                            try {
                                $datos = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 1);
                                $this->model->registrarLog($_SESSION['id_usuario'],$datos['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al Registrar Ingreso Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                            $msg = array('msg' => 'Ingreso registrado con éxito', 'icono' => 'success', 'id_banco'=>$datos['id']);
                        }
                    }else{
                        $msg = array('msg' => 'Ingresar Monto', 'icono' => 'warning', 'dato' => 'no');
                    }
                }else{
                    $msg = array('msg' => 'Seleccione un banco', 'icono' => 'warning', 'dato' => 'no');
                }
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                die();
            }else if($banco_tipo == 2){
                $documento = !empty($_POST['documento']) ? $_POST['documento'] : null;
                $nombre = !empty($_POST['nombre']) ? $_POST['nombre'] : null;
                $tipo_doc = !empty($_POST['tipo_documento']) ? $_POST['tipo_documento'] : null;
                $serie = !empty($_POST['serie']) ? $_POST['serie'] : null;
                $numero = !empty($_POST['correlativo']) ? $_POST['correlativo'] : null;
                if($id_banco_ini != NULL){
                    if($monto_ingreso != NULL){
                        if($monto_total != 0){
                            if($monto_ingreso == $monto_total){
                                try {
                                    $datos = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini, NULL, $numero_operacion, $documento, $nombre, $tipo_doc, $serie, $numero, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 2);
                                    $this->model->registrarLog($_SESSION['id_usuario'],$datos['datos'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al Registrar Salida Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                    die();
                                }
                                $msg = array('msg' => 'Salida registrada con éxito', 'icono' => 'success', 'id_banco'=>$datos['id']);
                            }else{
                                $msg = array('msg' => 'Los montos no coinciden', 'icono' => 'warning', 'dato' => 'no');
                            }
                        }else{
                            try {
                                $datos = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini, NULL, $numero_operacion, $documento, $nombre, $tipo_doc, $serie, $numero, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 2);
                                $this->model->registrarLog($_SESSION['id_usuario'],$datos['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al Registrar Salida Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                            $msg = array('msg' => 'Salida registrada con éxito', 'icono' => 'success', 'id_banco'=>$datos['id']);
                        }
                    }else{
                        $msg = array('msg' => 'Ingresar Monto', 'icono' => 'warning', 'dato' => 'no');
                    }
                }else{
                    $msg = array('msg' => 'Seleccione un banco', 'icono' => 'warning', 'dato' => 'no');
                }
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                die();
            }else{
                $id_banco_ini_trans = !empty($_POST['banco_inicial']) ? (int)$_POST['banco_inicial'] : null;
                $id_banco_fin_trans = !empty($_POST['banco_final']) ? (int)$_POST['banco_final'] : null;
                if($id_banco_ini_trans != NULL || $id_banco_fin_trans != NULL){
                    if($monto_ingreso != NULL){
                        if($monto_total != 0){
                            if($monto_ingreso == $monto_total){
                                try {
                                    $datosTransf = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini_trans, $id_banco_fin_trans, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 3);
                                    $this->model->registrarLog($_SESSION['id_usuario'],$datosTransf['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al Registrar Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                    die();
                                }
                                try {
                                    $datosTransfSal = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini_trans, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 2);
                                    $this->model->registrarLog($_SESSION['id_usuario'],$datosTransfSal['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al Registrar Salida Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                    die();
                                }
                                try {
                                    $datosTransfIngre = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_fin_trans, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 1);
                                    $this->model->registrarLog($_SESSION['id_usuario'],$datosTransfIngre['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al Registrar Ingreso Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                    echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                    die();
                                }
                                $msg = array('msg' => 'Transferencia registrada con éxito', 'icono' => 'success', 'id_banco'=>$datosTransf['id']);
                            }else{
                                $msg = array('msg' => 'Los montos no coinciden', 'icono' => 'warning', 'dato' => 'no');
                            }
                        }else{
                            try {
                                $datosTransf = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini_trans, $id_banco_fin_trans, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 3);
                                $this->model->registrarLog($_SESSION['id_usuario'],$datosTransf['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al Registrar Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                            try {
                                $datosTransfSal = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_ini_trans, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 2);
                                $this->model->registrarLog($_SESSION['id_usuario'],$datosTransfSal['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al Registrar Salida Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                            try {
                                $datosTransfIngre = $this->model->registrarIngresoBancos($id_usuario, $id_almacen, $id_banco_fin_trans, NULL, $numero_operacion, NULL, NULL, NULL, NULL, NULL, $monto_ingreso, $monto_total, $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha, 1);
                                $this->model->registrarLog($_SESSION['id_usuario'],$datosTransfIngre['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al Registrar Ingreso Transferencia Bancos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'banco_operaciones');
                                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                                die();
                            }
                            $msg = array('msg' => 'Transferencia registrada con éxito', 'icono' => 'success', 'id_banco'=>$datosTransf['id']);
                        }
                    }else{
                        $msg = array('msg' => 'Ingresar Monto', 'icono' => 'warning', 'dato' => 'no');
                    }
                }else{
                    $msg = array('msg' => 'Seleccione un banco', 'icono' => 'warning', 'dato' => 'no');
                }
            }
        }else{
            $msg = array('msg' => 'Completar todos los campos', 'icono' => 'warning', 'dato' => 'no');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pdfDetalladoBanco()
    {
        $id_banco = $_GET['banco'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
        if ($id_banco < 0) {
            $data = $this->model->buscarKardexBancoGen($fecha_desde, $fecha_hasta);
        } else {
            $data = $this->model->buscarKardexBanco($id_banco, $fecha_desde, $fecha_hasta);
        }
    
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_movimiento_bancos('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 7);
        
        foreach ($data as $row) {
            $pdf->Cell(15, 5, $row['fecha'], 1, 0, 'C');
            $pdf->Cell(35, 5, $row['operacion'], 1, 0, 'C');
            $pdf->Cell(30, 5, $row['numero_operacion'], 1, 0, 'C');
            $pdf->Cell(35, 5, $row['nombre'], 1, 0, 'C');
            $pdf->Cell(30, 5, $row['cuenta'], 1, 0, 'C');
            $pdf->Cell(30, 5, $row['cuenta_contable'], 1, 0, 'C');
            if($row['ingresos'] > 0 && $row['salidas'] == 0){
                $pdf->Cell(14, 5, $row['ingresos'], 1, 0, 'C');
            }else if($row['ingresos'] == 0 && $row['salidas'] > 0){
                $pdf->Cell(14, 5, $row['salidas'], 1, 0, 'C');
            }
            $pdf->Cell(8, 5, ($row['b_200'] !== null) ? $row['b_200'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['b_100'] !== null) ? $row['b_100'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['b_50'] !== null) ? $row['b_50'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['b_20'] !== null) ? $row['b_20'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['b_10'] !== null) ? $row['b_10'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_5'] !== null) ? $row['m_5'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_2'] !== null) ? $row['m_2'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_1'] !== null) ? $row['m_1'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_050'] !== null) ? $row['m_050'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_020'] !== null) ? $row['m_020'] : '0', 1, 0, 'C');
            $pdf->Cell(8, 5, ($row['m_010'] !== null) ? $row['m_010'] : '0', 1, 0, 'C');
            $pdf->Ln();
        }
        $pdf->Output("I", "Liquidacion_Ventas.pdf", true);
        exit();
    }

    public function excelDetalladoBanco()
    {
        $id_banco = $_GET['banco'];
        $fecha_desde = $_GET['fecha_desde'];
        $fecha_hasta = $_GET['fecha_hasta'];
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'MOVIMIENTO DE BANCOS');
        $sheet->mergeCells('A1:S1');
    
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
    
        $sheet->getStyle('A1:S1')->applyFromArray($styleHeader);
        $sheet->setCellValue('A2', 'Fecha');
        $sheet->setCellValue('B2', 'Operación');
        $sheet->setCellValue('C2', 'Número de Operación');
        $sheet->setCellValue('D2', 'Nombre');
        $sheet->setCellValue('E2', 'Cuenta');
        $sheet->setCellValue('F2', 'Cuenta Contable');
        $sheet->setCellValue('G2', 'Ingresos');
        $sheet->setCellValue('H2', 'Salidas');
        $sheet->setCellValue('I2', 'B-200');
        $sheet->setCellValue('J2', 'B-100');
        $sheet->setCellValue('K2', 'B-50');
        $sheet->setCellValue('L2', 'B-20');
        $sheet->setCellValue('M2', 'B-10');
        $sheet->setCellValue('N2', 'M-5');
        $sheet->setCellValue('O2', 'M-2');
        $sheet->setCellValue('P2', 'M-1');
        $sheet->setCellValue('Q2', 'M-0.50');
        $sheet->setCellValue('R2', 'M-0.20');
        $sheet->setCellValue('S2', 'M-0.10');
    
        if ($id_banco < 0) {
            $data = $this->model->buscarKardexBancoGen($fecha_desde, $fecha_hasta);
        } else {
            $data = $this->model->buscarKardexBanco($id_banco, $fecha_desde, $fecha_hasta);
        }
    
        $row = 3;
        foreach ($data as $row_data) {
            $sheet->setCellValue('A' . $row, $row_data['fecha']);
            $sheet->setCellValue('B' . $row, $row_data['operacion']);
            $sheet->setCellValue('C' . $row, $row_data['numero_operacion']);
            $sheet->setCellValue('D' . $row, $row_data['nombre']);
            $sheet->setCellValue('E' . $row, $row_data['cuenta']);
            $sheet->setCellValue('F' . $row, $row_data['cuenta_contable']);
            $sheet->setCellValue('G' . $row, $row_data['ingresos']);
            $sheet->setCellValue('H' . $row, $row_data['salidas']);
            $sheet->setCellValue('I' . $row, $row_data['b_200'] !== null ? $row_data['b_200'] : 0);
            $sheet->setCellValue('J' . $row, $row_data['b_100'] !== null ? $row_data['b_100'] : 0);
            $sheet->setCellValue('K' . $row, $row_data['b_50'] !== null ? $row_data['b_50'] : 0);
            $sheet->setCellValue('L' . $row, $row_data['b_20'] !== null ? $row_data['b_20'] : 0);
            $sheet->setCellValue('M' . $row, $row_data['b_10'] !== null ? $row_data['b_10'] : 0);
            $sheet->setCellValue('N' . $row, $row_data['m_5'] !== null ? $row_data['m_5'] : 0);
            $sheet->setCellValue('O' . $row, $row_data['m_2'] !== null ? $row_data['m_2'] : 0);
            $sheet->setCellValue('P' . $row, $row_data['m_1'] !== null ? $row_data['m_1'] : 0);
            $sheet->setCellValue('Q' . $row, $row_data['m_050'] !== null ? $row_data['m_050'] : 0);
            $sheet->setCellValue('R' . $row, $row_data['m_020'] !== null ? $row_data['m_020'] : 0);
            $sheet->setCellValue('S' . $row, $row_data['m_010'] !== null ? $row_data['m_010'] : 0);
    
            $row++;
        }
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Movimiento_Banco.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        exit();
    }

    public function generarPdfBancos($id_banco)
    {
        $empresa = $this->model->getEmpresa();
        $operacion_banco = $this->model->buscarBancoOp($id_banco);
        $almacen = $this->model->getAlmacen($operacion_banco['id_almacen']);
        $banco_inicial = $this->model->getBancoInicial($operacion_banco['id_banco_ini']);
        require('Libraries/Ticket/ticket.php');
        $pdf = new PDF_Code128('P','mm',array(80,258));
        $pdf->SetMargins(4,10,4);
        $x = 10;
        $y = 10;
        $pdf->AddPage();

        $pdf->SetFont('Arial','B',9);
        $pdf->MultiCell(0,4,utf8_decode(strtoupper($empresa['nombre'])),0,'C',false);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0,3,utf8_decode("RUC:".$empresa['ruc']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode($empresa['direccion']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode("TERMINAL:".$almacen['nombre']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode("Telefono: ".'+51 '.$empresa['telefono']),0,'C',false);
        $pdf->Ln(1);
        $fecha_actual = date('d/m/Y', strtotime($operacion_banco['fecha']));
        $pdf->SetFont('Arial','B',12);
        if($operacion_banco['tipo_operacion_banco'] == 1){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("COMPROBANTE DE INGRESO")),0,'C',false);
            $pdf->SetFont('Arial','B',10.5);
        }else if($operacion_banco['tipo_operacion_banco'] == 2){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("COMPROBANTE DE SALIDA")),0,'C',false);
        }else if($operacion_banco['tipo_operacion_banco'] == 3){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("COMPROBANTE DE TRANSFERENCIA")),0,'C',false);
        }
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("N° ".$id_banco)),0,'C',false);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(3);

        $pdf->Ln(2);
        if($operacion_banco['tipo_operacion_banco'] == 1){
            $pdf->MultiCell(0,3,utf8_decode("OPERACIÓN: INGRESO"),0,'C',false);
            $pdf->MultiCell(0,3,utf8_decode("BANCO: ".$banco_inicial['nombre']),0,'C',false);
        }else if($operacion_banco['tipo_operacion_banco'] == 2){
            $pdf->MultiCell(0,3,utf8_decode("OPERACIÓN: SALIDA"),0,'C',false);
            $pdf->MultiCell(0,3,utf8_decode("BANCO: ".$banco_inicial['nombre']),0,'C',false);
        }else if($operacion_banco['tipo_operacion_banco'] == 3){
            $banco_final = $this->model->getBancoFinal($operacion_banco['id_banco_fin']);
            $pdf->MultiCell(0,3,utf8_decode("OPERACIÓN: TRANSFERENCIA"),0,'C',false);
            $pdf->MultiCell(0,3,utf8_decode("BANCO INICIAL: ".$banco_inicial['nombre']),0,'C',false);
            $pdf->MultiCell(0,3,utf8_decode("BANCO FINAL: ".$banco_final['nombre']),0,'C',false);
        }
        if($operacion_banco['tipo_operacion_banco'] == 2){
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 6, utf8_decode("TIPO DOCUMENTO"), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(70, 6, utf8_decode($operacion_banco['documento_nombre']), 0, 'L');
            $pdf->Ln(2);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 6, utf8_decode("DNI / RUC"), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(70, 6, utf8_decode($operacion_banco['documento']), 0, 'L');
            $pdf->Ln(2);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 6, utf8_decode("NOMBRE / RAZÓN SOCIAL"), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(70, 6, utf8_decode($operacion_banco['nombre']), 0, 'L');
            $pdf->Ln(2);
            
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 6, utf8_decode("SERIE / CORRELATIVO"), 0, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(70, 6, utf8_decode($operacion_banco['serie'] . " - " . $operacion_banco['numero']), 0, 'L');
        }
        $pdf->Ln(2);
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 6, utf8_decode("NÚMERO DE OPERACIÓN"), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(70, 6, utf8_decode($operacion_banco['numero_operacion']), 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');

        $pdf->Ln(4);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("TOTAL"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode("S/. ".number_format($operacion_banco['monto_ingreso'], 2, '.', ',')), 0, 0, 'R');
    
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');

        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(54,5,utf8_decode("FEC. DE EMISION"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($fecha_actual),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,utf8_decode("VENDEDOR"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0, 5, utf8_decode($operacion_banco['nombre_usuario']), 0, 'R');
        $numero = $operacion_banco['monto_ingreso'];

        function convertirNumeroALetras($numero) {
            $numeros = array(
                'CERO', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
            );
        
            $decenas = array('', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA');
        
            $centenas = array('', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS');
        
            $centavos = floor(($numero * 100) % 100);
            $enteros = floor($numero);
        
            $cadena = '';
        
            if ($enteros == 0) {
                $cadena = $numeros[0];
            } elseif ($enteros == 1) {
                $cadena = $numeros[1];
            } elseif ($enteros >= 10 && $enteros <= 99) {
                $unidad = $enteros % 10;
                $decena = floor($enteros / 10);
        
                if ($unidad == 0) {
                    $cadena = $decenas[$decena];
                } else {
                    $cadena = $decenas[$decena] . ' Y ' . $numeros[$unidad];
                }
            } elseif ($enteros == 100) {
                $cadena = 'CIEN';
            } elseif ($enteros > 100 && $enteros <= 999) {
                $unidad = $enteros % 10;
                $decena = floor(($enteros % 100) / 10);
                $centena = floor($enteros / 100);
        
                if ($decena == 0 && $unidad == 0) {
                    $cadena = $centenas[$centena];
                } elseif ($decena == 0) {
                    $cadena = $centenas[$centena] . ' ' . $numeros[$unidad];
                } else {
                    $cadena = $centenas[$centena] . ' ' . $decenas[$decena] . ' Y ' . $numeros[$unidad];
                }
            }
        
            $cadena .= ' Y ' . $centavos . '/100 SOLES';
        
            return $cadena;
        }

        $totalEnLetras = convertirNumeroALetras($operacion_banco['monto_ingreso']);

        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode("SON " . $totalEnLetras), 0, 'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Output("I","VENTA ".$id_banco."_".$fecha_actual.".pdf",true);
    }
}
?>