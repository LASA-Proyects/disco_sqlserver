<?php
class Entradas extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 'entradas');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "index");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function listar()
    {
        $data = $this->model->getEntrNorm();
            for ($i=0; $i < count($data); $i++){
            $data[$i]['acciones'] = '<div>
            <a class="btn btn-danger" type="button" href="'.base_url."Entradas/pdfEntrNorml/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
            <div/>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarCort()
    {
        $data = $this->model->getEntrCort();
            for ($i=0; $i < count($data); $i++){
            $data[$i]['acciones'] = '<div>
            <a class="btn btn-danger" type="button" href="'.base_url."Entradas/pdfEntrCort/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
            <div/>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarPromot()
    {
        $data = $this->model->getEntrPromot();
            for ($i=0; $i < count($data); $i++){
            $data[$i]['acciones'] = '<div>
            <a class="btn btn-danger" type="button" href="'.base_url."Entradas/pdfEntrPromot/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
            <div/>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function regEntrNorml()
    {
        $efectivo = $_POST['efectivo'];
        $visa = $_POST['visa'];
        $master = $_POST['master_c'];
        $diners = $_POST['diners'];
        $a_express = $_POST['a_express'];
        $yape = $_POST['yape'];
        $plin = $_POST['plin'];
        $izipay = $_POST['izipay'];
        $niubiz = $_POST['niubiz'];
        $op_visa = $_POST['op_visa'];
        $op_mast = $_POST['op_mast'];
        $op_diners = $_POST['op_diners'];
        $op_express = $_POST['op_express'];
        $op_yape = $_POST['op_yape'];
        $op_plin = $_POST['op_plin'];
        $op_izipay = $_POST['op_izipay'];
        $op_niubiz = $_POST['op_niubiz'];
        $dni = !empty($_POST['dni']) ? (int)$_POST['dni'] : null;
        $ruc = $_POST['ruc'];
        $parametro = $_POST['parametro'];
        $genero = $_POST['genero'];
        $nombre_cli = $_POST['nombre_cli'];
        $tipo_cambio = $_POST['tipo_cambio'];
        $tipo_doc = $_POST['tipo_doc'];
        $serie = $_POST['serie_doc'];
        $correlativo = $_POST['correl_doc'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = $_POST['fecha_oper'];
        $id_usuario = $_SESSION['id_usuario'];
        $producto = $this->model->getProduct($_POST['tipo_entrada']);
        $cantidad = $_POST['cantidad'];
        $total = $cantidad*$producto['precio_venta'];
        $igv = 0.00;
        $base = $total;
        $id_almacen_ini = $_SESSION['almacen'];
        try {
            $regNormal = $this->model->regEntrNormal($id_usuario, $producto['codigo'],$dni, $ruc, $nombre_cli, $genero, $fecha_actual, $total, $tipo_cambio);
        } catch (PDOException $e) {
            $msg = array('msg' => 'Error al registrar la Entrada: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
            echo json_encode($msg);
            die();
        }
        if($serie != '' && $correlativo != ''){
            $verificar_corr = $this->model->buscarCorr($parametro, $id_almacen_ini);
            if($verificar_corr['correlativo'] === $correlativo){
                if($tipo_doc == 1){
                    try {
                        $id_pedido_registrado = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                    } catch (PDOException $e) {
                        $msg = array('msg' => 'Error al registrar el Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                        echo json_encode($msg);
                        die();
                    }
                    if($producto['afecta_igv'] == 0){
                        $total_prod = $cantidad*$producto['precio_venta'];
                        $igv_prod = 0.00;
                        $base_prod = $total_prod;
                        $this->model->actualizarEstadoPedido($tipo_doc, $serie, $correlativo, $dni, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                        try {
                            $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                    }else{
                        $total_prod = $cantidad*$producto['precio_venta'];
                        $base_prod = $total_prod;
                        $igv_prod = $total_prod*0.18;
                        $total_final = $base_prod + $igv_prod;
                        $this->model->actualizarEstadoPedido($tipo_doc, $serie, $correlativo, $dni, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                        try {
                            $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_final);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                    }
                }else if($tipo_doc == 2){
                    $data = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                    if($producto['afecta_igv'] == 0){
                        $total_prod = $cantidad*$producto['precio_venta'];
                        $base_prod = $total_prod;
                        $igv_prod = 0.00;
                        $this->model->actualizarEstadoPedido($tipo_doc, $serie, $correlativo, $dni, NULL, NULL, NULL, $ruc, $nombre_cli, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                        try {
                            $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                    }else{
                        $total_prod = $cantidad*$producto['precio_venta'];
                        $base_prod = $total_prod;
                        $igv_prod = $total_prod*0.18;
                        $total_final = $base_prod + $igv_prod;
                        $this->model->actualizarEstadoPedido($tipo_doc, $serie, $correlativo, $dni, NULL, NULL, NULL, $ruc, $nombre_cli, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                        try{
                            $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_final);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                    }
                }
            }else if($verificar_corr['correlativo'] != $correlativo && $_POST['serie_doc'] != ''){
                $nuevo_correlativo = $verificar_corr['correlativo'];
                if($regNormal == 'ok'){
                    if($tipo_doc == 1){
                        $data = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                        if($producto['afecta_igv'] == 0){
                            $total_prod = $cantidad*$producto['precio_venta'];
                            $igv_prod = 0.00;
                            $base_prod = $total_prod;
                            $this->model->actualizarEstadoPedido($tipo_doc, $serie, $nuevo_correlativo, $dni, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                            try{
                                $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                echo json_encode($msg);
                                die();
                            }
                            $correlativoAum = str_pad(intval($nuevo_correlativo) + 1, strlen($nuevo_correlativo), '0', STR_PAD_LEFT);
                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                        }else{
                            $total_prod = $cantidad*$producto['precio_venta'];
                            $base_prod = $total_prod;
                            $igv_prod = $total_prod*0.18;
                            $total_final = $base_prod + $igv_prod;
                            $this->model->actualizarEstadoPedido($tipo_doc, $serie, $nuevo_correlativo, $dni, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                            try{
                                $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_final);
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                echo json_encode($msg);
                                die();
                            }
                            $correlativoAum = str_pad(intval($nuevo_correlativo) + 1, strlen($nuevo_correlativo), '0', STR_PAD_LEFT);
                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                        }
                    }else if($tipo_doc == 2){
                        $data = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                        if($producto['afecta_igv'] == 0){
                            $total_prod = $cantidad*$producto['precio_venta'];
                            $igv_prod = 0.00;
                            $base_prod = $total_prod;
                            $this->model->actualizarEstadoPedido($tipo_doc, $serie, $nuevo_correlativo, $dni, NULL, NULL, NULL, $ruc, $nombre_cli, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                            try{
                                $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                echo json_encode($msg);
                                die();
                            }
                            $correlativoAum = str_pad(intval($nuevo_correlativo) + 1, strlen($nuevo_correlativo), '0', STR_PAD_LEFT);
                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                        }else{
                            $total_prod = $cantidad*$producto['precio_venta'];
                            $base_prod = $total_prod;
                            $igv_prod = $total_prod*0.18;
                            $total_final = $base_prod + $igv_prod;
                            $this->model->actualizarEstadoPedido($tipo_doc, $serie, $nuevo_correlativo, $dni, NULL, NULL, NULL, $ruc, $nombre_cli, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                            try{
                                $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_final);
                            } catch (PDOException $e) {
                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                echo json_encode($msg);
                                die();
                            }
                            $correlativoAum = str_pad(intval($nuevo_correlativo) + 1, strlen($nuevo_correlativo), '0', STR_PAD_LEFT);
                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $id_almacen_ini);
                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
                        }
                    }
                }else{
                    $msg = array('msg'=> 'Error al registrar la Entrada', 'icono' => 'error');
                }
            }
        }else{
            $serie_null = null;
            $correlativo_null = null;
            if($producto['afecta_igv'] == 0){
                $total_prod = $cantidad*$producto['precio_venta'];
                $igv_prod = 0.00;
                $base_prod = $total_prod;
                try{
                    $id_pedido_registrado = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar el Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    echo json_encode($msg);
                    die();
                }
                $this->model->actualizarEstadoPedido($tipo_doc, $serie_null, $correlativo_null, NULL, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                try{
                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    echo json_encode($msg);
                    die();
                }
                $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
            }else{
                $total_prod = $cantidad*$producto['precio_venta'];
                $base_prod = $total_prod;
                $igv_prod = $total_prod*0.18;
                $total_final = $base_prod + $igv_prod;
                try{
                    $id_pedido_registrado = $this->model->registrarPedido($id_usuario, $id_almacen_ini, NULL, $base, $igv, $total, $fecha_actual,0);
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar el Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    echo json_encode($msg);
                    die();
                }
                $this->model->actualizarEstadoPedido($tipo_doc, $serie_null, $correlativo_null, NULL, $nombre_cli, NULL, NULL, NULL, NULL, NULL, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, NULL, NULL, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, NULL, NULL, 0, NULL, $id_pedido_registrado);
                try{
                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $producto['id'], $id_almacen_ini, $producto['precio_venta'], $cantidad, $base_prod, $igv_prod, $total_prod);
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    echo json_encode($msg);
                    die();
                }
                $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'total' => $total);
            }
        }
        echo json_encode($msg);
        die();
    }

    public function normal()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'BOLETERIA VENTA');
        if(!empty($verificar) || $id_usuario == 1){
            $data['tipo_docs'] = $this->model->getTipoDoc();
            $data['tipo_entradas'] = $this->model->getEntradas();
            $this->views->getView($this, "normal", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }



    public function cortesia()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'cortesia');
        if(!empty($verificar) || $id_usuario == 1){
            $data['tipo_docs'] = $this->model->getTipoDoc();
            $this->views->getView($this, "cortesia", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function regEntrCort()
    {
        $genero = $_POST['genero'];
        $nombre_cli = $_POST['nombre_cli'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d h:i:s");
        $id_usuario = $_SESSION['id_usuario'];
        $producto = $this->model->getProductCort();
        $total = $producto['precio_venta'];
        $id_almacen_ini = $_SESSION['almacen'];
        $token = $_POST['token'];
        $regNormal = $this->model->regEntrCort($id_usuario, $producto['codigo'],$nombre_cli, $genero, $token, $fecha_actual, $total);
        if($regNormal == 'ok'){
            $this->model->actualizarTokenCort($token);
        }else{
            $msg = 'Error al realizar la venta';
        }
        if($regNormal == 'ok'){
            $msg = array('msg'=> 'Entrada registrada con éxito', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al registrar la Entrada', 'icono' => 'error');
        }
        echo json_encode($msg);
        die();
    }

    public function promotor()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'promotor');
        if(!empty($verificar) || $id_usuario == 1){
            $data['tipo_docs'] = $this->model->getTipoDoc();
            $this->views->getView($this, "promotor", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }
    public function regEntrPromot()
    {
        $genero = $_POST['genero'];
        $nombre_cli = $_POST['nombre_cli'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d h:i:s");
        $id_usuario = $_SESSION['id_usuario'];
        $producto = $this->model->getProductPromot();
        $total = $producto['precio_venta'];
        $id_almacen_ini = $_SESSION['almacen'];
        $token = $_POST['token'];
        $cantidad_token = $this->model->verificarTokenPromot($token);
        if (is_array($cantidad_token) && isset($cantidad_token['disponible'])) {
            $regNormal = $this->model->regEntrPromot($id_usuario, $producto['codigo'], $nombre_cli, $genero, $token, $fecha_actual, $total);
            $msg = array('msg' => 'Registrado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error en la consulta', 'icono' => 'error');
        }
    
        echo json_encode($msg);
        die();
    }
    public function buscarSolicitante($token)
    {
        $data = $this->model->verificarTokenCort($token);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultarPrecioProd($idEntrada)
    {
        $data = $this->model->consultarPrecioProd($idEntrada);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die(); 
    }

    public function buscarSolicitantPromot($token)
    {
        $comprobar = $this->model->verificarTokenPromot($token);
        if ($comprobar['disponible'] == 0) {
            $this->model->actualizarTokenCort($token);
        }
        $data = $this->model->verificarTokenPromot($token);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function consultaRUC($ruc)
    {
        $verificarRUC = $this->model->consultarCliente(2, $ruc);
        if(empty($verificarRUC)){
            $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
            ));

            echo $response = curl_exec($curl);
            curl_close($curl);
            die();
        }else{
            echo json_encode($verificarRUC, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function consultaDNI($dni)
    {
        $verificarDNI = $this->model->consultarCliente(1, $dni);
        if(empty($verificarDNI)){
            $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
            ),
            ));
    
            echo $response = curl_exec($curl);
            curl_close($curl);
            die();
        }else{
            echo json_encode($verificarDNI, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function consulta_tipo_cambio()
    {
        $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
        $fecha = date('Y-m-d');
        
        // Iniciar llamada a API
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/tipo-cambio?date=' . $fecha,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 2,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
            'Authorization: Bearer ' . $token
          ),
        ));
        
        echo $response = curl_exec($curl);
        curl_close($curl);
        die();
    }

    public function buscarCorr($parametro)
    {
        $data = $this->model->buscarCorr($parametro, $_SESSION['almacen']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function pdfEntrNorml($id_entrada)
    {
        require('Libraries/tcpdf/tcpdf.php');
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $fecha_actual = date('Y-m-d');
        
        // Configurar datos de encabezado
        $pdf->SetCreator('Nombre de tu discoteca');
        $pdf->SetAuthor('Nombre de tu discoteca');
        $pdf->SetTitle('Entrada para la Discoteca');
        
        // Agregar una página al PDF
        $pdf->AddPage('L', array(120, 65)); // Orientación horizontal y tamaño personalizado (120x60 mm)
        
        // Cuadro izquierdo para fecha y nombre de la discoteca
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(10, 10, 40, 40, 'F'); // Cuadro blanco
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->MultiCell(81, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->StartTransform();
        $pdf->Rotate(89, 40, 15);
        $pdf->Text(20, 30, 'DISCO 2000');
        $pdf->StopTransform();
        
        // Línea divisoria entre los cuadros
        $pdf->SetLineWidth(0.2);
        $pdf->Line(60, 10, 60, 40);
        
        // Cuadro derecho para detalles del ticket
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(65, 10, 45, 40, 'F');
        $pdf->MultiCell(180, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text(65, 15, 'DISCO 2000');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Text(65, 21, 'ENTRADA PERSONAL');
        $pdf->Text(65, 24, 'FECHA: '.date('Y-m-d'));
        $pdf->Text(65, 27, 'HORA: '.date('H:i:s'));

        $archivo_salida = 'entrada_discoteca.pdf';

        $pdf->Output('example.pdf','I');
    }

    public function pdfEntrCort($id_entrada)
    {
        require('Libraries/tcpdf/tcpdf.php');
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $fecha_actual = date('Y-m-d');
        $datos = $this->model->getEntrada($id_entrada);
        
        // Configurar datos de encabezado
        $pdf->SetCreator('Nombre de tu discoteca');
        $pdf->SetAuthor('Nombre de tu discoteca');
        $pdf->SetTitle('Entrada para la Discoteca');
        
        // Agregar una página al PDF
        $pdf->AddPage('L', array(120, 65)); // Orientación horizontal y tamaño personalizado (120x60 mm)
        
        // Cuadro izquierdo para fecha y nombre de la discoteca
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(10, 10, 40, 40, 'F'); // Cuadro blanco
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->MultiCell(81, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->StartTransform();
        $pdf->Rotate(89, 40, 15);
        $pdf->Text(20, 30, 'DISCO 2000');
        $pdf->StopTransform();
        
        // Línea divisoria entre los cuadros
        $pdf->SetLineWidth(0.2);
        $pdf->Line(60, 10, 60, 40);
        
        // Cuadro derecho para detalles del ticket
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(65, 10, 45, 40, 'F');
        $pdf->MultiCell(180, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text(65, 15, 'DISCO 2000');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Text(65, 21, 'ENTRADA POR CORTESÍA');
        $pdf->Text(65, 24, 'FECHA: '.date('Y-m-d'));
        $pdf->Text(65, 27, 'HORA: '.date('H:i:s'));
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text(65, 35, 'TOKEN: '. $datos['token']);

        $archivo_salida = 'entrada_discoteca.pdf';

        $pdf->Output('example.pdf','I');
    }

    public function pdfEntrPromot($id_entrada)
    {
        require('Libraries/tcpdf/tcpdf.php');
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $fecha_actual = date('Y-m-d');
        $datos = $this->model->getEntrada($id_entrada);
        
        // Configurar datos de encabezado
        $pdf->SetCreator('Nombre de tu discoteca');
        $pdf->SetAuthor('Nombre de tu discoteca');
        $pdf->SetTitle('Entrada para la Discoteca');
        
        // Agregar una página al PDF
        $pdf->AddPage('L', array(120, 65)); // Orientación horizontal y tamaño personalizado (120x60 mm)
        
        // Cuadro izquierdo para fecha y nombre de la discoteca
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(10, 10, 40, 40, 'F'); // Cuadro blanco
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->MultiCell(81, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->StartTransform();
        $pdf->Rotate(89, 40, 15);
        $pdf->Text(20, 30, 'DISCO 2000');
        $pdf->StopTransform();
        
        // Línea divisoria entre los cuadros
        $pdf->SetLineWidth(0.2);
        $pdf->Line(60, 10, 60, 40);
        
        // Cuadro derecho para detalles del ticket
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Rect(65, 10, 45, 40, 'F');
        $pdf->MultiCell(180, 10, $id_entrada, 0, 'C', 1, 1, 16, 12);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text(65, 15, 'DISCO 2000');
        $pdf->SetFont('helvetica', '', 8);
        $pdf->Text(65, 21, 'ENTRADA POR PROMOTOR');
        $pdf->Text(65, 24, 'FECHA: '.date('Y-m-d'));
        $pdf->Text(65, 27, 'HORA: '.date('H:i:s'));
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Text(65, 35, 'TOKEN: '. $datos['token']);

        $archivo_salida = 'entrada_discoteca.pdf';

        $pdf->Output('example.pdf','I');
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>