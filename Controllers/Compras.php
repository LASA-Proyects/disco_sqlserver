<?php

class Compras extends Controller{
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
        $verificar = $this->model->verificarPermiso($id_usuario, 7, 9);
        $id_permiso_accion = [];
        foreach ($verificar as $datos) {
            $id_permiso_accion[] = $datos['id_permiso_accion'];
        }
        $id_permiso_accion = array_unique($id_permiso_accion);
        $buscar_tipos_operacion = $this->model->buscarTipoOperaciones($id_permiso_accion);
        $id_acciones = [];
        foreach ($buscar_tipos_operacion as $tipo_operacion) {
            $id_acciones[] = $tipo_operacion['id_accion'];
        }
        $id_acciones = array_unique($id_acciones);
        if(!empty($verificar) || $id_usuario == 1){
            $data['proveedores'] = $this->model->getProveedores();
            if($id_usuario == 1){
                $data['tipos_operaciones'] = $this->model->getTipoOperacionesCompraGen();
            }else{
                $data['tipos_operaciones'] = $this->model->getTipoOperacionesCompra($id_acciones);
            }
            $data['t_documentos'] = $this->model->getTDoc();
            $data['almacenes'] = $this->model->getAlmacen();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }
    public function ventas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 8, 11);
        $id_permiso_accion = [];
        foreach ($verificar as $datos) {
            $id_permiso_accion[] = $datos['id_permiso_accion'];
        }
        $id_permiso_accion = array_unique($id_permiso_accion);
        $buscar_tipos_operacion = $this->model->buscarTipoOperaciones($id_permiso_accion);
        $id_acciones = [];
        foreach ($buscar_tipos_operacion as $tipo_operacion) {
            $id_acciones[] = $tipo_operacion['id_accion'];
        }
        $id_acciones = array_unique($id_acciones);
        if(!empty($verificar) || $id_usuario == 1){
            $data['proveedores'] = $this->model->getProveedores();
            if($id_usuario == 1){
                $data['tipos_operaciones'] = $this->model->getTipoOperacionesVentaGen();
            }else{
                $data['tipos_operaciones'] = $this->model->getTipoOperacionesVenta($id_acciones);
            }
            $data['t_documentos'] = $this->model->getTDoc();
            $data['almacenes'] = $this->model->getAlmacen();
            $this->views->getView($this, "ventas", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function editarCompras($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $data['proveedores'] = $this->model->getProveedores();
        $data['tipos_operaciones'] = $this->model->getTipoOperacionesCompraGen();
        $data['t_documentos'] = $this->model->getTDoc();
        $data['almacenes'] = $this->model->getAlmacen();
        $this->views->getView($this, "editarCompras", $data);
    }

    public function editarVentas($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $data['proveedores'] = $this->model->getProveedores();
        $data['tipos_operaciones'] = $this->model->getTipoOperacionesVentaGen();
        $data['t_documentos'] = $this->model->getTDoc();
        $data['almacenes'] = $this->model->getAlmacen();
        $this->views->getView($this, "editarVentas", $data);
    }


    public function buscarCodigo($cod)
    {
        $data = $this->model->getProdCod($cod);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarCompras()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        foreach ($json as $productos){
            $result = $this->model->getProdCod($productos['cod_producto']);
            $data['id'] = $result['id'];
            $data['codigo'] = $result['codigo'];
            $data['nombre'] = $result['descripcion'];
            $data['unidad'] = $result['unidad_med'];
            $data['precio'] = $productos['precio'];
            $data['cantidad'] = $productos['cantidad'];
            $sub_total = $productos['precio']*$productos['cantidad'];
            $data['sub_total'] = number_format($sub_total, 2);
            $total += $sub_total;
            array_push($array['productos'],$data);
        }
        $array['total'] = number_format($total, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarVentas()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        foreach ($json as $productos){
            $result = $this->model->getProdCod($productos['cod_producto']);
            $data['id'] = $result['id'];
            $data['codigo'] = $result['codigo'];
            $data['nombre'] = $result['descripcion'];
            $data['unidad'] = $result['unidad_med'];
            $data['cantidad'] = $productos['cantidad'];
            $data['precio'] = $result['precio_venta'];
            $sub_total = $result['precio_venta']*$productos['cantidad'];
            $data['sub_total'] = number_format($sub_total, 2);
            $total += $sub_total;
            array_push($array['productos'],$data);
        }
        $array['total'] = number_format($total, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function ingresarVenta()
    {
        $id = $_POST['id'];
        $datos = $this->model->getProductos($id);
        $id_producto = $datos['id'];
        $id_usuario = $_SESSION['id_usuario'];
        $precio = $datos['precio_venta'];
        $cantidad = $_POST['cantidad'];
        $comprobar = $this->model->consultarDetalle('detalle_tmp',$id_producto, $id_usuario);
        if(empty($comprobar)){
            $sub_total = $precio*$cantidad;
            $data = $this->model->registrarDetalle('detalle_tmp',$id_producto, $id_usuario, $precio, $cantidad, $sub_total);
            if($data == "ok"){
                $msg = "ok";
            }else{
                $msg = "Error al ingresar el producto";
            }
        }else{
            $total_cantidad = $comprobar['cantidad'] + $cantidad;
            $sub_total = $total_cantidad * $precio;
            $data = $this->model->actualizarDetalle('detalle_tmp',$precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
            if($data == "modificado"){
                $msg = "modificado";
            }else{
                $msg = "Error al modificado el producto";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function listar($table)
    {
        $id_usuario = $_SESSION['id_usuario'];
        $data['detalle'] = $this->model->getDetalle($table, $id_usuario);
        $data['total_pagar'] = $this->model->calcularCompra($table, $id_usuario);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function delete($id)
    {
        $data = $this->model->deleteDetalle('detalle',$id);
        if($data == 'ok'){
            $msg = 'ok';
        }else{
            $msg = 'error';
        }
        echo json_encode($msg);
        die();
    }

    public function deleteVenta($id)
    {
        $data = $this->model->deleteDetalle('detalle_tmp',$id);
        if($data == 'ok'){
            $msg = 'ok';
        }else{
            $msg = 'error';
        }
        echo json_encode($msg);
        die();
    }

    public function registrarCompra()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $datos_compra = $data['datos_compra'];
        $listaCompras = $data['listaCompras'];
        $tipo_cambio = $datos_compra['tipo_cambio'];
        $tipo_documento = $datos_compra['t_documento'];
        $fecha_ingreso = !empty($datos_compra['fecha_ingreso']) ? $datos_compra['fecha_ingreso'] : null;
        $serie = $datos_compra['serie_doc'];
        $correlativo = $datos_compra['correl_doc'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d");
        $id_usuario = $_SESSION['id_usuario'];
        $tipo_operacion = isset($datos_compra['t_operacion']) ? $datos_compra['t_operacion'] : "";
        $id_almacen_ini = isset($datos_compra['id_almacen_ini']) ? $datos_compra['id_almacen_ini'] : "";
        $id_almacen_fin = isset($datos_compra['id_almacen_fin']) && $datos_compra['id_almacen_fin'] !== "" ? $datos_compra['id_almacen_fin'] : null;
        $id_compra_edit = $datos_compra['id_compra'];
        $ingresoFecha = date("Y-m-d h:i:s");
        $id_proveedor = $datos_compra['proveedor'];
        $total = $datos_compra['total_compra'];
        if(empty($tipo_cambio) || empty($tipo_documento) || empty($fecha_ingreso) || empty($serie) || empty($correlativo) || empty($tipo_operacion) || empty($id_almacen_ini)){
            $msg = array('msg' => 'Completar todos los campos', 'icon' => 'warning');
        }else{
            if($id_compra_edit == 0){
                try {
                    $data = $this->model->registrarCompra($id_usuario, NULL, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total,$fecha_actual);
                    $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar la Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    $this->model->registrarLog($_SESSION['id_usuario'], $e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                    echo json_encode($msg);
                    die();
                }
                foreach ($listaCompras as $row){
                    $cod_producto = $row['cod_producto'];
                    $listaProductos = $this->model->getProdCod($cod_producto);
                    $cantidad = $row['cantidad'];
                    $precio = $row['precio'];
                    $id_producto = $listaProductos['id'];
                    $sub_total = $cantidad*$precio;
                    try {
                        $data_detalle = $this->model->registrarDetalleCompra($data['id'], NULL, $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                        $this->model->registrarLog($_SESSION['id_usuario'],$data_detalle, $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                    } catch (PDOException $e) {
                        $msg = array('msg' => 'Error al registrar el Detalle Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                        $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                        echo json_encode($msg);
                        die();
                    }
                }
                $msg = array('msg' => 'ok', 'id_compra' => $data['id'], 'icon' => 'success');
                /*$correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                $this->model->actualizarSerie($correlativoAum);*/
            }else{
                try {
                    $data_editCompra = $this->model->editarCompra($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $id_compra_edit);
                    $this->model->registrarLog($_SESSION['id_usuario'], "id_usuario={$id_usuario}, id_proveedor={$id_proveedor}, tipo_operacion={$tipo_operacion}, fecha_ingreso={$fecha_ingreso}, tipo_cambio={$tipo_cambio}, tipo_documento={$tipo_documento}, id_almacen_ini={$id_almacen_ini}, id_almacen_fin={$id_almacen_fin}, serie={$serie}, correlativo={$correlativo}, total={$total}" . ' | ' . $data_editCompra['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al editar la Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                    echo json_encode($msg);
                    die();
                }
                $eliminarDetalle = $this->model->eliminarDetalleCompra($id_compra_edit);
                if($eliminarDetalle['res'] == "eliminado"){
                    foreach ($listaCompras as $row){
                        $cod_producto = $row['cod_producto'];
                        $listaProductos = $this->model->getProdCod($cod_producto);
                        $cantidad = $row['cantidad'];
                        $precio = $row['precio'];
                        $id_producto = $listaProductos['id'];
                        $sub_total = $cantidad*$precio;
                        try {
                            $this->model->registrarDetalleCompra($id_compra_edit, NULL, $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al re-registrar el Detalle Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                            echo json_encode($msg);
                            die();
                        }
                        $msg = array('msg' => 'ok', 'id_compra' => $id_compra_edit, 'icon' => 'success');
                    }
                }else{
                    $msg = 'Error';
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarVenta()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $datos_venta = $data['datos_venta'];
        $listaVentas = $data['listaVentas'];
        $id_usuario = $_SESSION['id_usuario'];
        $tipo_cambio = $datos_venta['tipo_cambio'];
        $tipo_documento = $datos_venta['t_documento'];
        $fecha_ingreso = !empty($datos_venta['fecha_ingreso']) ? $datos_venta['fecha_ingreso'] : null;
        $serie = $datos_venta['serie_doc_vnt'];
        $correlativo = $datos_venta['correl_doc_vnt'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d");
        $tipo_operacion = isset($datos_venta['t_operacion']) ? $datos_venta['t_operacion'] : "";
        $id_almacen_ini = isset($datos_venta['id_almacen_ini']) ? $datos_venta['id_almacen_ini'] : "";
        $id_almacen_fin = isset($datos_venta['id_almacen_fin']) && $datos_venta['id_almacen_fin'] !== "" ? $datos_venta['id_almacen_fin'] : null;
        $id_venta_edit = $datos_venta['id_venta'];
        $id_proveedor = $datos_venta['proveedor'];
        $total = $datos_venta['total_venta'];
        $ingresoFecha = date("Y-m-d h:i:s");
        $condicion_completa = "";
        if($id_venta_edit == 0){
            if(empty($tipo_cambio) || empty($tipo_documento) || empty($fecha_ingreso) || empty($serie) || empty($correlativo) || empty($tipo_operacion)){
                $msg = array('msg' => 'Completar todos los campos', 'icon' => 'warning');
            }else{
                if($tipo_operacion == 2){
                    if(empty($id_almacen_ini) || empty($id_almacen_fin)){
                        $msg = array('msg' => 'Seleccionar los almacenes', 'icon' => 'warning');
                    }else{
                        $condicion_completa = "ok";
                    }
                }else{
                    if(empty($id_almacen_ini)){
                        $msg = array('msg' => 'Seleccionar el almacen', 'icon' => 'warning');
                    }else{
                        $condicion_completa = "ok";
                    }
                }
            }
            if($condicion_completa == "ok"){
                if($tipo_operacion != 2){
                    try {
                        $data = $this->model->registrarVenta($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total,$fecha_actual);
                        $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'venta');
                    } catch (PDOException $e) {
                        $msg = array('msg' => 'Error al registrar la Salida: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                        $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'venta');
                        echo json_encode($msg);
                        die();
                    }
                    foreach ($listaVentas as $row){
                        $cod_producto = $row['cod_producto'];
                        $listaProductos = $this->model->getProdCod($cod_producto);
                        $cantidad = $row['cantidad'];
                        $precio = $listaProductos['precio_venta'];
                        $id_producto = $listaProductos['id'];
                        $sub_total = $cantidad*$precio;
                        try{
                        $data_detalle = $this->model->registrarDetalleVenta($data['id'], $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                        $this->model->registrarLog($_SESSION['id_usuario'],$data_detalle, $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle de la Salida: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                            echo json_encode($msg);
                            die();
                        }
                    }
                    /*$correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                    $this->model->actualizarSerieVnt($correlativoAum);*/
                    $msg = array('msg' => 'ok', 'id_venta' => $data['id']);
                }else{
                    try {
                        $id_venta = $this->model->registrarVenta($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total,$fecha_actual);
                        $this->model->registrarLog($_SESSION['id_usuario'],$id_venta['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'venta');
                    } catch (PDOException $e) {
                        $msg = array('msg' => 'Error al registrar la Salida: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                        $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'venta');
                        echo json_encode($msg);
                        die();
                    }
                    try {
                        $id_compra = $this->model->registrarCompra($id_usuario, $id_venta['id'], 99999, 1, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_fin, NULL, $serie, $correlativo, $total,$fecha_actual);
                        $this->model->registrarLog($_SESSION['id_usuario'],$id_compra['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                    } catch (PDOException $e) {
                        $msg = array('msg' => 'Error al registrar el Ingreso Automático: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                        $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                        echo json_encode($msg);
                        die();
                    }
                    foreach ($listaVentas as $row){
                        $cod_producto = $row['cod_producto'];
                        $listaProductos = $this->model->getProdCod($cod_producto);
                        $cantidad = $row['cantidad'];
                        $precio = $listaProductos['precio_venta'];
                        $id_producto = $listaProductos['id'];
                        $sub_total = $cantidad*$precio;
                        try{
                            $data_detalle = $this->model->registrarDetalleVenta($id_venta['id'], $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                            $this->model->registrarLog($_SESSION['id_usuario'],$data_detalle, $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle de la Salida: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                            echo json_encode($msg);
                            die();
                        }
                        try{
                            $data_detalle_compra = $this->model->registrarDetalleCompra($id_compra['id'], $id_venta['id'], $id_producto, $id_almacen_fin, $cantidad, $precio, $sub_total);
                            $this->model->registrarLog($_SESSION['id_usuario'],$data_detalle_compra, $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Detalle del ingreso Automático: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                            echo json_encode($msg);
                            die();
                        }
                    }
                    /*$correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                    $this->model->actualizarSerieVnt($correlativoAum);*/
                    $msg = array('msg' => 'ok', 'id_venta' => $id_venta['id']);
                }
            }
        }else{
            try{
                $data_editVenta = $this->model->editarVenta($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $id_venta_edit);
                $this->model->registrarLog($_SESSION['id_usuario'], "id_usuario={$id_usuario}, id_proveedor={$id_proveedor}, tipo_operacion={$tipo_operacion}, fecha_ingreso={$fecha_ingreso}, tipo_cambio={$tipo_cambio}, tipo_documento={$tipo_documento}, id_almacen_ini={$id_almacen_ini}, id_almacen_fin={$id_almacen_fin}, serie={$serie}, correlativo={$correlativo}, total={$total}" . ' | ' . $data_editVenta['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'venta');
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al editar la Venta: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                echo json_encode($msg);
                die();
            }
            if($tipo_operacion != 2){
                $eliminarDetalleVnt = $this->model->eliminarDetalleVenta($id_venta_edit);
                if($eliminarDetalleVnt['res'] == "eliminado"){
                    foreach ($listaVentas as $row){
                        $cod_producto = $row['cod_producto'];
                        $listaProductos = $this->model->getProdCod($cod_producto);
                        $cantidad = $row['cantidad'];
                        $precio = $listaProductos['precio_venta'];
                        $id_producto = $listaProductos['id'];
                        $sub_total = $cantidad*$precio;
                        try{
                            $this->model->registrarDetalleVenta($id_venta_edit, $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al re-registrar el Detalle Venta: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                            echo json_encode($msg);
                            die();
                        }
                        $msg = array('msg' => 'ok', 'id_venta' => $id_venta_edit);
                    }
                }else{
                    $msg = 'Error';
                }
            }else{
                try{
                    $data_editReCompra = $this->model->editarCompraTransf($id_usuario, 99999, 1, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_fin, NULL, $serie, $correlativo, $total, $id_venta_edit);
                    $this->model->registrarLog($_SESSION['id_usuario'], "id_usuario={$id_usuario}, id_proveedor={$id_proveedor}, tipo_operacion={$tipo_operacion}, fecha_ingreso={$fecha_ingreso}, tipo_cambio={$tipo_cambio}, tipo_documento={$tipo_documento}, id_almacen_ini={$id_almacen_ini}, id_almacen_fin={$id_almacen_fin}, serie={$serie}, correlativo={$correlativo}, total={$total}" . ' | ' . $data_editReCompra['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al editar la Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                    echo json_encode($msg);
                    die();
                }
                $eliminarDetalleVnt = $this->model->eliminarDetalleVenta($id_venta_edit);
                $this->model->eliminarDetalleCompraTransf($id_venta_edit);
                if($eliminarDetalleVnt['res'] == "eliminado"){
                    foreach ($listaVentas as $row){
                        $cod_producto = $row['cod_producto'];
                        $listaProductos = $this->model->getProdCod($cod_producto);
                        $cantidad = $row['cantidad'];
                        $precio = $listaProductos['precio_venta'];
                        $id_producto = $listaProductos['id'];
                        $sub_total = $cantidad*$precio;
                        try{
                            $this->model->registrarDetalleVenta($id_venta_edit, $id_producto, $id_almacen_ini, $cantidad, $precio, $sub_total);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al re-registrar el Detalle Venta: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                            echo json_encode($msg);
                            die();
                        }
                        $id_compra_transf = $this->model->getDetalleTransf($id_venta_edit);
                        try{
                            $this->model->registrarDetalleCompra($id_compra_transf['id'], $id_venta_edit, $id_producto, $id_almacen_fin, $cantidad, $precio, $sub_total);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al re-registrar el Detalle Compra: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                            echo json_encode($msg);
                            die();
                        }
                        $msg = array('msg' => 'ok', 'id_venta' => $id_venta_edit);
                    }
                }else{
                    $msg = 'Error';
                }
            }
        }
        echo json_encode($msg);
        die();
    }

    public function consulta_tipo_cambio()
    {
        $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
        $fecha = date('Y-m-d');
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

    public function generarPdf($id_compra)
    {
        $empresa = $this->model->getEmpresa();
        $compra = $this->model->getCompra($id_compra);
        $detalle_compra = $this->model->getDetalleCompras($id_compra);
        $usuario_compra = $this->model->getUsuario_Compra($compra['id_usuario']);
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
        $pdf->MultiCell(0,3,utf8_decode("TERMINAL:".$compra['nombre_almacen']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode("Telefono: ".'+51 '.$empresa['telefono']),0,'C',false);
        $pdf->Ln(1);
        $fecha_actual = date('d/m/Y', strtotime($compra['fecha']));
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("COMPROBANTE DE INGRESO")),0,'C',false);
        $pdf->SetFont('Arial','B',10.5);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("I000-".$id_compra)),0,'C',false);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(3);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(25, 4, utf8_decode("DOCUMENTO:"), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        if(isset($compra['nombres']) && $compra['razon_social'] == NULL){
            $pdf->MultiCell(0, 4, utf8_decode($compra['documento']." ".$compra['serie']." - ".$compra['correlativo']), 0, 'R');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 4, utf8_decode("PROVEEDOR:"), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 4, utf8_decode($compra['nombres']), 0, 'R');
        }else if(isset($compra['razon_social']) && $compra['nombres'] == NULL){
            $pdf->MultiCell(0, 4, utf8_decode($compra['documento']." ".$compra['serie']." - ".$compra['correlativo']), 0, 'R');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 4, utf8_decode("PROVEEDOR:"), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 4, utf8_decode($compra['razon_social']), 0, 'R');
        }

        $pdf->Ln(1);
        $pdf->Cell(17,5,utf8_decode("CANT."),0,0,'A');
        $pdf->Cell(38.5,5,utf8_decode("PRECIO UNIT."),0,0,'C');
        $pdf->MultiCell(17,5,utf8_decode("TOTAL"),0,'R',false);

        $pdf->Ln(2);
        $total = 0.00;
        foreach ($detalle_compra as $row) {
            $total += $row['sub_total'];
            $pdf->Cell(70,4,utf8_decode($row['codigo']),0,1,'L');
            $pdf->MultiCell(70, 4, utf8_decode($row['nombre_producto']), 0, 'L');
            $pdf->Cell(12,4,utf8_decode($row['cantidad']),0,0,'A');
            $pdf->Cell(49,4,"             ",0,0,'C');
            $pdf->Cell(11.5,4,utf8_decode(number_format($row['cantidad'] * $row['precio'],2, '.',',')),0,0,'R');
            $pdf->Ln(2);
            if($row['observacion'] !== NULL && $row['observacion'] !== ''){
                $pdf->Ln(2);
                $pdf->SetFont('Arial','B',9);
                $pdf->MultiCell(0, 3, utf8_decode("OBSERVACIÓN: ".$row['observacion']), 0, 'A', false);
                $pdf->SetFont('Arial','',9);
            }
            $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
            $pdf->Ln(4);
        }

        $pdf->Ln(4);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("TOTAL"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode(number_format($total, 2, '.', ',')), 0, 0, 'R');
    
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
        $pdf->Cell(54,5,utf8_decode("FEC. DE VENCIMIENTO"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($fecha_actual),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,utf8_decode("VENDEDOR"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0, 5, utf8_decode($usuario_compra['nombre']), 0, 'R');
        $numero = $total;

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

        $totalEnLetras = convertirNumeroALetras($total);

        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode("SON " . $totalEnLetras), 0, 'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Output("I","COMPRA ".$id_compra."_".$fecha_actual.".pdf",true);
    }

    public function generarPdfVenta($id_venta)
    {
        $empresa = $this->model->getEmpresa();
        $venta = $this->model->getVenta($id_venta);
        $detalle_venta = $this->model->getDetalleVentas($id_venta);
        $usuario_venta = $this->model->getUsuario_Compra($venta['id_usuario']);
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
        $pdf->MultiCell(0,3,utf8_decode("TERMINAL:".$venta['nombre_almacen']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode("Telefono: ".'+51 '.$empresa['telefono']),0,'C',false);
        $pdf->Ln(1);
        $fecha_actual = date('d/m/Y', strtotime($venta['fecha']));
        $pdf->SetFont('Arial','B',12);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("COMPROBANTE DE SALIDA")),0,'C',false);
        $pdf->SetFont('Arial','B',10.5);
        $pdf->MultiCell(0,5,utf8_decode(strtoupper("S000-".$id_venta)),0,'C',false);

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(3);

        $pdf->Ln(1);
        $pdf->Cell(17,5,utf8_decode("CANT."),0,0,'A');
        $pdf->Cell(38.5,5,utf8_decode("PRECIO UNIT."),0,0,'C');
        $pdf->MultiCell(17,5,utf8_decode("TOTAL"),0,'R',false);

        $pdf->Ln(2);
        $total = 0.00;
        foreach ($detalle_venta as $row) {
            $total += $row['sub_total'];
            $pdf->Cell(70,4,utf8_decode($row['codigo']),0,1,'L');
            $pdf->MultiCell(70, 4, utf8_decode($row['nombre_producto']), 0, 'L');
            $pdf->Cell(12,4,utf8_decode($row['cantidad']),0,0,'A');
            $pdf->Cell(49,4,utf8_decode(number_format($row['precio'],2, '.',',')),0,0,'C');
            $pdf->Cell(11.5,4,utf8_decode(number_format($row['cantidad'] * $row['precio'],2, '.',',')),0,0,'R');
            $pdf->Ln(2);
            $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
            $pdf->Ln(4);
        }

        $pdf->Ln(4);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("TOTAL"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode(number_format($total, 2, '.', ',')), 0, 0, 'R');
    
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
        $pdf->Cell(54,5,utf8_decode("FEC. DE VENCIMIENTO"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($fecha_actual),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,utf8_decode("VENDEDOR"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0, 5, utf8_decode($usuario_venta['nombre']), 0, 'R');
        $numero = $total;

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

        $totalEnLetras = convertirNumeroALetras($total);

        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode("SON " . $totalEnLetras), 0, 'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Output("I","VENTA ".$id_venta."_".$fecha_actual.".pdf",true);
    }
    
    public function historial()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 7, 10);
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "historial");
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }
    public function historial_ventas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 8, 12);
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "historial_ventas");
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function listar_historial_ventas()
    {
        $data = $this->model->getHistorialVentas($_SESSION['id_almacen']);
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Vigente</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-warning btn-sm" onclick="btnAnularVenta('. $data[$i]['id'].')"><i class="fas fa-ban"></i></button>
                <a class="btn btn-info btn-sm" href="' . base_url . 'Compras/editarVentas/' . $data[$i]['id'] . '"><i class="fas fa-edit"></i></a>
                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Compras/generarPdfVenta/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                $data[$i]['acciones'] = ' ';
            }
    }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function buscarVenta($cod)
    {
        $data = $this->model->getProdCod($cod);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function anularCompra($id_compra)
    {
        $data = $this->model->anularCompra($id_compra);
        if($data == "ok"){
            $msg = array('msg' => 'Compra Anulada', 'icono' => 'success');
        }else{
            $msg = array('msg' => 'Error al Cancelar Compra', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularVenta($id_venta)
    {
        $data = $this->model->anularVenta($id_venta);
        if($data == "ok"){
            $msg = array('msg' => 'Venta Anulada', 'icono' => 'success');
        }else{
            $msg = array('msg' => 'Error al Anular', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarProductos()
    {
        $data = $this->model->getListaProductos();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="buscarProductoLista(' . $data[$i]['id'] . ');"><i class="fas fa-plus"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    
    public function buscarProdId($id)
    {
        $data = $this->model->getProductos($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultarCompra($id)
    {
        $data['compra'] = $this->model->getCompra($id);
        $data['detalle_compra'] = $this->model->getDetalleCompras($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultarVenta($id)
    {
        $data['venta'] = $this->model->getVenta($id);
        $data['detalle_ventas'] = $this->model->getDetalleVentas($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarComprasFecha($value)
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $data = $this->model->getHistorialCompras($desde, $hasta, $_SESSION['id_almacen']);
        for ($i=0; $i < count($data); $i++){
            if($data[$i]['id_tipo_operacion'] == 1 || $data[$i]['id_tipo_operacion'] == 12){
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge badge-success">Vigente</span>';
                    $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                    <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Compras/generarPdf/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                    $data[$i]['acciones'] = '';
                }
            }else{
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge badge-success">Vigente</span>';
                    $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                    <button class="btn btn-warning btn-sm" onclick="btnAnularCompra('. $data[$i]['id'].')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-info btn-sm" href="' . base_url . 'Compras/editarCompras/' . $data[$i]['id'] . '"><i class="fas fa-edit"></i></a>
                    <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Compras/generarPdf/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }else{
                    $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                    $data[$i]['acciones'] = ' ';
                }
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarVentasFecha()
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $data = $this->model->getHistorialVentas($desde, $hasta,$_SESSION['id_almacen']);
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Vigente</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-warning btn-sm" onclick="btnAnularVenta('. $data[$i]['id'].')"><i class="fas fa-ban"></i></button>
                <a class="btn btn-info btn-sm" href="' . base_url . 'Compras/editarVentas/' . $data[$i]['id'] . '"><i class="fas fa-edit"></i></a>
                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Compras/generarPdfVenta/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Anulado</span>';
                $data[$i]['acciones'] = ' ';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}