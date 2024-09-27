<?php
class Carga extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 5);
        if(!empty($verificar) || $id_usuario == 1){
            $data['mesas'] = $this->model->getMesas();
            $data['usuarios'] = $this->model->getUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function carga_asist()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BOLETERIA PROMOTOR');
        if(!empty($verificar) || $id_usuario == 1){
            $data['promotores'] = $this->model->getPromotor();
            $this->views->getView($this, "carga_asist", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function listar()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->getCargaInvitados($id_usuario);
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 0) {
                $data[$i]['estado'] = '<span class="badge badge-danger">NO ASISTIÓ</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="justify-content: center;">
                <button class="btn btn-success btn-sm" type="button" onclick="btnAsistencia('.$data[$i]['id'].');"><i class="fas fa-check"></i></button>
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-primary">ASISTIÓ</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="justify-content: center;">
                <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function registrar()
    {
        $logMessage = '';
        if ($_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $tempFile = $_FILES['excel_file']['tmp_name'];
        
            if (($handle = fopen($tempFile, 'r')) !== false) {
                $datos = array();
                $headers = fgetcsv($handle, 0, ';');
        
                while (($row = fgetcsv($handle, 0, ';')) !== false) {
                    $row = array_map(function ($item) {
                        return mb_convert_encoding($item, 'UTF-8', 'ISO-8859-1');
                    }, $row);
                
                    $record = array_map('trim', $row);
                    $record = array_combine($headers, $record);
                    $datos[] = $record;
                }
        
                fclose($handle);
                foreach ($datos as $row) {
                    $id_usuario = $_SESSION['id_usuario'];
                    $dni = !empty($row['dni']) ? (int)$row['dni'] : null;
                    $apellidoPaterno = $row["apellidoPaterno"];
                    $apellidoMaterno = $row["apellidoMaterno"];
                    $nombres = $row["nombres"];
                    $mesa = $row["mesa"];
                    date_default_timezone_set('America/Lima');
                    $fecha_actual = date("Y-m-d");
                    $ingresoFecha = date("Y-m-d h:i:s");
                    $warnings = array();
                    if (empty($apellidoPaterno)) {
                        $warnings[] = 'Apellido Paterno';
                    }
                    if (empty($apellidoMaterno)) {
                        $warnings[] = 'Apellido Materno';
                    }
                    if (empty($nombres)) {
                        $warnings[] = 'Nombres';
                    }
                    if (empty($mesa)) {
                        $warnings[] = 'Mesa';
                    }

                    if (!empty($warnings)) {
                        $msg = array(
                            'msg' => 'Faltan los siguientes campos: ' . implode(', ', $warnings),
                            'icono' => 'warning'
                        );
                    } else {
                        try{
                            $data = $this->model->registrarCarga($id_usuario, $dni, $apellidoPaterno, $apellidoMaterno, $nombres, $mesa, $fecha_actual);
                            $logMessage .= $data['sql']."|";
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al Subir Invitados: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'asistencia_entrada');
                            echo json_encode($msg);
                            die();
                        }
                        if($data['id'] != 0){
                            $msg = array('msg'=> 'Invitado registrado con éxito', 'icono' => 'success');
                        }else if($data['id'] == 0){
                            $msg = array('msg'=> 'El Invitado ya se encuentra registrado', 'icono' => 'warning');
                        }else{
                            $msg = array('msg'=> 'Error al registrar el usuario', 'icono' => 'error');
                        }
                    }
                }
                $logMessage = rtrim($logMessage, '|');
            } else {
                $msg = array('Error al abrir el archivo CSV', 'icono' => 'error');
            }
        } else {
            $msg = array('Error al cargar el archivo CSV', 'icono' => 'error');
        }
        $this->model->registrarLog($_SESSION['id_usuario'], $logMessage, $ingresoFecha, $_SESSION['id_almacen'], 'toma_inv_detalle');
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarInv()
    {
        $id_usuario = $_SESSION['id_usuario'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d");
        $dni = !empty($_POST['dni']) ? (int)$_POST['dni'] : null;
        $apellidoMaterno = $_POST['a_materno'];
        $apellidoPaterno = $_POST['a_paterno'];
        $nombres = $_POST['nombre_inv'];
        $mesa = $_POST['mesa'];
        $ingresoFecha = date("Y-m-d h:i:s");
        if(empty($apellidoMaterno) || empty($apellidoPaterno) || empty($nombres)){
            $msg = array('msg'=> 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            try{
                $data = $this->model->registrarCarga($id_usuario, $dni, $apellidoPaterno, $apellidoMaterno, $nombres, $mesa, $fecha_actual);
                $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'asistencia_entrada');
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar Invitado Manualmente: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'asistencia_entrada');
                echo json_encode($msg);
                die();
            }
            if($data['id'] != 0){
                $msg = array('msg'=> 'Invitado registrado con éxito', 'icono' => 'success');
            }else if($data['id'] == 0){
                $msg = array('msg'=> 'El Invitado ya se encuentra registrado', 'icono' => 'warning');
            }else{
                $msg = array('msg'=> 'Error al registrar el usuario', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPromotor()
    {
        $data = $this->model->getPromotor();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarInvitado(int $id_promotor)
    {
        $data = $this->model->buscarInvitado($id_promotor);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function marcarAsistencia()
    {
        $json = file_get_contents('php://input');
        $datos = json_decode($json, true);
        date_default_timezone_set('America/Lima');
        $fecha_asist = date("Y-m-d");
        $hora_asist = date("H:i:s");
        $ingresoFecha = date("Y-m-d h:i:s");
        $logMessage = '';
        foreach ($datos as $row) {
            try{
                $data = $this->model->actualizarInvitado($fecha_asist, $hora_asist, 1, $row);
                $logMessage .= $data."|";
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al actualizar estado de Invitado: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'asistencia_entrada');
                echo json_encode($msg);
                die();
            }
        }
        $logMessage = rtrim($logMessage, '|');
        $this->model->registrarLog($_SESSION['id_usuario'], $logMessage, $ingresoFecha, $_SESSION['id_almacen'], 'toma_inv_detalle');
        $msg = array('msg'=> 'Asistencia Marcada', 'icono' => 'success');
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
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
        } else {
            $data = $this->model->getRangoFechas($desde, $hasta, $getUsu['id']);
        }
    
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_Custom();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $maxHeight = 230;
        $currentHeight = 0;
    
        foreach ($data as $row) {
            $cellHeight = 10;
            if ($currentHeight + $cellHeight > $maxHeight) {
                $pdf->AddPage();
                $currentHeight = 0;
            }
    
            $pdf->Cell(30, 10, $row['id'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['dni'], 1, 0, 'C');
            $pdf->Cell(100, 10, $row['nombres'], 1, 0, 'C');
            $pdf->Cell(30, 10, $row['fecha_asist'], 1, 1, 'C');
            $currentHeight += $cellHeight;
        }

        $total = array_sum(array_column($data, 'total'));
    
        if ($currentHeight + 60 > $maxHeight) {
            $pdf->AddPage();
            $currentHeight = 0;
        }
        if (!empty($data)) {
            if ($id_usu == "-1") {
                $conteo_r = $this->model->consultarTotalGen();
            } else {
                $conteo_r = $this->model->consultarTotal($id_usu);
            }
        } else {
            $conteo_r[0]['pago_comision'] = 0;
        }
        $pdf->Cell(0, 10, 'Total: S/. ' . number_format($conteo_r[0]['pago_comision'], 2), 0, 1, 'R');
        $pdf->Cell(0, -10, 'Fecha: ' . $desde . ' / ' . $hasta, 0, 1, 'L');
        if ($id_usu == "-1") {
            $pdf->Cell(0, 20, 'RESUMEN GENERAL', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 20, 'Vendedor: ' . $getUsu['nombre'], 0, 1, 'L');
        }
    
        $pdf->Output("I", "Resumen_de_Ventas.pdf", true);
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>