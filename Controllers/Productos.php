<?php
require'C:/xampp/htdocs/disco2023/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class Productos extends Controller{
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
        $verificar = $this->model->verificarPermiso($id_usuario, 6, 14);
        if(!empty($verificar) || $id_usuario == 1){
            $data['familias'] = $this->model->getFamilia();
            $data['t_articulos'] = $this->model->getTArticulos();
            $data['lineas'] = $this->model->getLineas();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function stock()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 3, 4);
        if(!empty($verificar) || $id_usuario == 1){
            if($id_usuario == 1){
                 $data['almacenes'] = $this->model->getAlmacenes();           
            }else{
                $data['almacenes'] = $this->model->getUsuariosAlmc($id_usuario);
            }
            $data['productos'] = $this->model->getProductos();
            $data['familias'] = $this->model->getFamilias();
            $this->views->getView($this, "stock", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function stock_terminal()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERMINAL STOCK');
        if(!empty($verificar) || $id_usuario == 1){
            if($id_usuario == 1){
                 $data['almacenes'] = $this->model->getAlmacenes();           
            }else{
                $data['almacenes'] = $this->model->getUsuariosAlmc($id_usuario);
            }
            $data['productos'] = $this->model->getProductos();
            $data['familias'] = $this->model->getFamilias();
            $this->views->getView($this, "stock_terminal", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function TomaInv()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 3, 18);
        if(!empty($verificar) || $id_usuario == 1){
            $data['almacenes'] = $this->model->getAlmacenes();
            $this->views->getView($this, "tomainv", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function prueba_regulador()
    {
        $dataVista = $this->model->getVistaRegulador();
        foreach ($dataVista as $row) {
            $this->model->regulador_tmp($row['id'],$row['id_pedido'], $row['id_usuario'], $row['id_producto'], $row['id_almacen'], $row['cantcombo'], $row['id_producto_asoc'], $row['diferencia']);
        }
    }

    public function insertar_prueba_regulador()
    {
        $dataVista = $this->model->getVistaReguladorTmp();
        foreach ($dataVista as $row) {
            $this->model->nuevoDato($row['id_pedido'], $row['id_usuario'], $row['id_producto'], $row['id_almacen'], $row['precio'], $row['cantidad'], $row['base'], $row['igv'], $row['total'], $row['id_producto_asoc']);
        }
    }

    public function eliminar_regulador()
    {
        $dataVista = $this->model->getVistaReguladorTmp();
        foreach ($dataVista as $row) {
            $this->model->eliminarDatos($row['id_detalle_pedido'], $row['id_pedido']);
        }
    }

    public function receta($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $data['id_producto'] = $id;
        $data['producto'] = $this->model->getProducRec($id);
        $this->views->getView($this, "receta", $data);
    }

    public function listar()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $usuario = $this->model->getUsuarios($id_usuario);
        $id_almacen = $usuario['id_almacen'];
        $data = $this->model->getProductos();
        $ids_productos = array_column($data, 'id'); 
        $productos_con_registros = $this->model->verificarMovArtTodos($ids_productos);
        $ids_con_registros = array_column($productos_con_registros, 'id_producto');
        for ($i = 0; $i < count($data); $i++) {
            $existen_registros = in_array($data[$i]['id'], $ids_con_registros);
            if ($existen_registros) {
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . base_url . "Assets/img/" . $data[$i]['foto'] . '" width="56">';
                    $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                        <button class="btn btn-warning btn-sm" type="button" onclick="btnEditarProduct(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success btn-sm" type="button" onclick="btnEstadoProducto(' . $data[$i]['id'] . ');"><i class="fas fa-toggle-on"></i></button>
                        <div/>';
                } else {
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . base_url . "Assets/img/" . $data[$i]['foto'] . '" width="100">';
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-secondary" type="button" onclick="btnActivarProducto(' . $data[$i]['id'] . ');"><i class="fas fa-toggle-off"></i></button>
                        <div/>';
                }
            } else {
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                    $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . base_url . "Assets/img/" . $data[$i]['foto'] . '" width="56">';
                    $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                        <button class="btn btn-warning btn-sm" type="button" onclick="btnEditarProduct(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                        <a class="btn btn-primary btn-sm" href="' . base_url . 'Productos/receta/' . $data[$i]['id'] . '"><i class="fas fa-book"></i></a>
                        <button class="btn btn-success btn-sm" type="button" onclick="btnEstadoProducto(' . $data[$i]['id'] . ');"><i class="fas fa-toggle-on"></i></button>
                        <button class="btn btn-danger btn-sm" type="button" onclick="btnEliminarProduct(' . $data[$i]['id'] . ');"><i class="fas fa-trash"></i></button>
                        <div/>';
                } else {
                    $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                    $data[$i]['imagen'] = '<img class="img-thumbnail" src="' . base_url . "Assets/img/" . $data[$i]['foto'] . '" width="100">';
                    $data[$i]['acciones'] = '<div>
                        <button class="btn btn-secondary" type="button" onclick="btnActivarProducto(' . $data[$i]['id'] . ');"><i class="fas fa-toggle-off"></i></button>
                        <div/>';
                }
            }
        }
    
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarStock($id_almacen)
    {
        $id_usuario = $_SESSION['id_usuario'];
        $fecha = $_POST['fecha'];
        $producto = $_POST['producto'];
        $familia = $_POST['familia'];
        $data = $this->model->getProductosStock($fecha,$producto,$familia,$id_almacen);
        for ($i=0; $i < count($data); $i++){
            $data[$i]['foto'] = '<img class="img-thumbnail" src="'.base_url."Assets/img/".$data[$i]['foto'].'" width="56">';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarFamilia($id_producto)
    {
        $data = $this->model->getProducRec($id_producto);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d h:i:s");
        $codigo = $_POST['codigo'];
        $stock_minimo = $_POST['stock_minimo'];
        $descripcion = $_POST['descripcion'];
        $afecta_compra = isset($_POST['afecta_compra']) ? 1 : 0;
        $afecta_venta = isset($_POST['afecta_venta']) ? 1 : 0;
        $afecta_igv = isset($_POST['afecta_igv']) ? 1 : 0;
        $afecta_iss = isset($_POST['afecta_iss']) ? 1 : 0;
        $linea = $_POST['linea'];
        $ubicacion = $_POST['ubicacion'];
        $origen = $_POST['origen'];
        $precio_compra = $_POST['precio_compra'];
        $precio_venta = $_POST['precio_venta'];
        $id_familia = $_POST['familia'];
        $id_tarticulo = $_POST['t_articulo'];
        $unidad = !empty($_POST['unidad_medida']) ? $_POST['unidad_medida'] : null;
        $cantidad = !empty($_POST['cantidad']) ? (int)$_POST['cantidad'] : null;
        $id = $_POST['id'];
        $img = $_FILES['imagen'];
        $name = $img['name'];
        $tmpname = $img['tmp_name'];
        $fecha = date("YmdHis");
        $ingresoFecha = date("Y-m-d h:i:s");
        if(empty($codigo)){
            $msg = array('msg'=> 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            if(!empty($name)){
                $imgNombre = $fecha . '.jpg';
                $destino = "Assets/img/".$imgNombre;
            }else if(!empty($_POST['foto_actual']) && empty($name)){
                $imgNombre = $_POST['foto_actual'];
            }else{
                $imgNombre = 'default.jpg';
            }
            if($_POST['id'] == ""){
                if (($afecta_compra + $afecta_venta + $afecta_igv) === 2) {
                    if (!$afecta_compra) {
                        $afecta_compra = 0;
                    } elseif (!$afecta_venta) {
                        $afecta_venta = 0;
                    } elseif (!$afecta_igv) {
                        $afecta_igv = 0;
                    }
                } else {
                    $msg = array('msg'=> 'Error en los selectores', 'icono' => 'error');
                }
                $data = $this->model->registrarProducto($codigo, $descripcion, $afecta_compra, $afecta_venta, $afecta_igv, $afecta_iss, $stock_minimo, $linea, $ubicacion, $origen, $precio_compra, $precio_venta, $id_familia, $id_tarticulo, $imgNombre, $unidad, $cantidad ,$fecha_actual);
                if($data == "ok"){
                    if(!empty($name)){
                        move_uploaded_file($tmpname, $destino);
                    }
                    $msg = array('msg'=> 'Producto registrado con éxito', 'icono' => 'success');
                }else if($data == "existe"){
                    $msg = array('msg'=> 'El Producto ya existe', 'icono' => 'warning');
                }else{
                    $msg = array('msg'=> 'Error al ingresar el Producto', 'icono' => 'error');
                }
            }else{
                $imgDelete = $this->model->editarProduct($id);
                if($imgDelete['foto'] != 'default.jpg'){
                    if(file_exists("Assets/img/" . $imgDelete['foto'])){
                        unlink("Assets/img/" . $imgDelete['foto']);
                    }
                }
                $data = $this->model->modificarProducto($codigo, $descripcion, $afecta_compra, $afecta_venta, $afecta_igv, $afecta_iss, $stock_minimo, $linea, $ubicacion, $origen, $precio_compra, $precio_venta, $id_familia, $id_tarticulo, $imgNombre, $unidad, $cantidad, $fecha_actual, $id);
                if($data == "modificado"){
                    if(!empty($name)){
                        move_uploaded_file($tmpname, $destino);
                    }
                    $msg = array('msg'=> 'Producto modificado con éxito', 'icono' => 'success');
                }else{
                    $msg = array('msg'=> 'Error al modificar el Producto', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id)
    {
        $data = $this->model->editarProduct($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {
        $verificar_receta = $this->model->verificarProductoReceta($id);
        if(!empty($verificar_receta)){
            $msg = array('msg'=> 'El producto se encuentra con una receta activa', 'icono' => 'warning');
        }else{
            $data = $this->model->eliminarProduct($id);
            if($data == "eliminado"){
                $msg = array('msg'=> 'Producto eliminado con éxito', 'icono' => 'success');
            }else{
                $msg = array('msg'=> 'Error al eliminar el Producto', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function desactivar(int $id)
    {
        $data = $this->model->accionProducto(0, $id);
        if($data == 1){
            $msg = array('msg'=> 'Producto desactivado con éxito', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al desactivar el Producto', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function activar(int $id)
    {
        $data = $this->model->accionProducto(1, $id);
        if($data == 1){
            $msg = array('msg'=> 'Producto Activado con éxito', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al activar el Producto', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }

    public function listarTomaInv()
    {
        $data = $this->model->getTomaInv();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-info">Generado</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                <button class="btn btn-primary btn-sm" type="button" onclick="btnSubirTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-upload"></i></button>
                <button class="btn btn-secondary btn-sm" type="button" onclick="btnVerEstadoTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-eye"></i></button>
                <button class="btn btn-info btn-sm" type="button" onclick="ImpTomaInvExcelExport(' . $data[$i]['id'] . ');"><i class="fas fa-arrow-alt-circle-down"></i></button>
                </div>';
            }else if($data[$i]['estado'] == 2){
                $data[$i]['estado'] = '<span class="badge badge-primary">Subido</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-success btn-sm" type="button" onclick="btnProcesarTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-cog"></i> Procesar</button>
                <button class="btn btn-secondary btn-sm" type="button" onclick="btnVerEstadoTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-eye"></i></button>
                </div>';
            }else if($data[$i]['estado'] == 0){
                $data[$i]['estado'] = '<span class="badge badge-success">Procesado</span>';
                $data[$i]['acciones'] = '
                <button class="btn btn-secondary btn-sm" type="button" onclick="btnVerEstadoTomaInv(' . $data[$i]['id'] . ');"><i class="fas fa-eye"></i></button>
                ';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-warning">Anulado</span>';
                $data[$i]['acciones'] = '';
            }
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die(); 
    }

    public function TomaInvExcelExport()
    {
        $hasta = $_POST['hasta'];
        $id_almacen = $_POST['almacen'];
        $ingresoFecha = date("Y-m-d h:i:s");
        $stock_actual = $this->model->getProductosStock($hasta,-1,-1,$id_almacen);
        if(empty($stock_actual)){
            echo "No hay datos disponibles para exportar.";
        }else{
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'HASTA ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
            $sheet->setCellValue('A2', 'N°');
            $sheet->setCellValue('B2', 'Id Toma');
            $sheet->setCellValue('C2', 'Id Producto');
            $sheet->setCellValue('D2', 'Descripción');
            $sheet->setCellValue('E2', 'Unidad');
            $sheet->setCellValue('F2', 'Id Almacen');
            $sheet->setCellValue('G2', 'Almacén');
            $sheet->setCellValue('H2', 'Stock Actual Sys');
            $sheet->setCellValue('I2', 'Stock Físico');
            $id = 1;
            $row = 3;
            date_default_timezone_set('America/Lima');
            $fecha_descarga = date("Y-m-d h:i:s");
            try{
                $registro_toma_inv = $this->model->insertarTomaInv($_SESSION['id_usuario'], $fecha_descarga);
                $this->model->registrarLog($_SESSION['id_usuario'],$registro_toma_inv['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'toma_inventario');
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al generar Toma de Inventario: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'toma_inventario');
                echo json_encode($msg);
                die();
            }
            foreach ($stock_actual as $row_data) {
                $sheet->setCellValue('A' . $row, $id);
                $sheet->setCellValue('B' . $row, $registro_toma_inv['id']);
                $sheet->setCellValue('C' . $row, $row_data['id_producto']);
                $sheet->setCellValue('D' . $row, $row_data['descripcion']);
                $sheet->setCellValue('E' . $row, $row_data['unidad_med']);
                $sheet->setCellValue('F' . $row, $row_data['id_almacen']);
                $sheet->setCellValue('G' . $row, $row_data['almacen']);
                $sheet->setCellValue('H' . $row, $row_data['stock']);
                $sheet->setCellValue('I' . $row, 0.00);
                $id++;
                $row++;
                $this->model->insertarTomaInvDetalle($registro_toma_inv['id'], $row_data['id_producto'], $row_data['id_almacen'], $row_data['stock']);
            }
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="TOMA INVENTARIO (' . $row_data['almacen'] . ').xlsx"');
            header('Cache-Control: max-age=0');
        
            $writer->save('php://output');
        
            exit;
        }
    }

    public function ImpTomaInvExcelExport(int $id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'RE - DESCARGA');
        $sheet->setCellValue('A2', 'N°');
        $sheet->setCellValue('B2', 'Id Toma');
        $sheet->setCellValue('C2', 'Id Producto');
        $sheet->setCellValue('D2', 'Descripción');
        $sheet->setCellValue('E2', 'Unidad');
        $sheet->setCellValue('F2', 'Id Almacen');
        $sheet->setCellValue('G2', 'Almacén');
        $sheet->setCellValue('H2', 'Stock Actual Sys');
        $sheet->setCellValue('I2', 'Stock Físico');
        $ingresoFecha = date("Y-m-d h:i:s");
        $id_aum = 1;
        $row = 3;
        $registro_toma_inv = $this->model->ImpTomaInvExcelExport($id);
        foreach ($registro_toma_inv as $row_data) {
            $sheet->setCellValue('A' . $row, $id_aum);
            $sheet->setCellValue('B' . $row, $id);
            $sheet->setCellValue('C' . $row, $row_data['id_producto']);
            $sheet->setCellValue('D' . $row, $row_data['descripcion']);
            $sheet->setCellValue('E' . $row, $row_data['unidad_med']);
            $sheet->setCellValue('F' . $row, $row_data['id_almacen']);
            $sheet->setCellValue('G' . $row, $row_data['nombre_almacen']);
            $sheet->setCellValue('H' . $row, $row_data['stock_actual_sys']);
            $sheet->setCellValue('I' . $row, 0.00);
            $id_aum++;
            $row++;
        }
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RE - DESCARGA TOMA INVENTARIO (' . $row_data['nombre_almacen'] . ').xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        $this->model->registrarLog($_SESSION['id_usuario'], "Re - Descarga Toma Inventario", $ingresoFecha, $_SESSION['id_almacen'], '-');
        exit;
    }

    public function importar(int $id)
    {
        if ($_FILES['archivoExcel']['error'] === UPLOAD_ERR_OK) {
            $tempFile = $_FILES['archivoExcel']['tmp_name'];
            $spreadsheet = IOFactory::load($tempFile);
            $worksheet = $spreadsheet->getActiveSheet();
            $ingresoFecha = date("Y-m-d h:i:s");
            $data = $worksheet->toArray();
            $datos_actualizados = [];
            for ($i = 2; $i < count($data); $i++) {
                $row = $data[$i];
                if($row[1] != $id){
                    $msg = array('msg'=> 'La Toma Ingresada no Coinciden', 'icono' => 'error');
                }else{
                    $id_toma = $row[1];
                    $id_producto = $row[2];
                    $id_almacen = $row[4];
                    $stock_actual_sys = $row[7];
                    $stock_actual_fisico = $row[8];
                    if($row[8] == 0 && $row[7] == 0){
                        $msg = array('msg'=> 'No Existe Diferencia entre Stock del Sistema y el Stock Físico', 'icono' => 'warning');
                    }else{
                        try{
                            $actualizar = $this->model->actualizarStockFisico($stock_actual_fisico, $id_toma,$id_producto);
                            if($row[8] != 0){
                                $datos_actualizados[] = [
                                    'id_toma' => $id_toma,
                                    'id_producto' => $id_producto,
                                    'id_almacen' => $id_almacen,
                                    'stock_actual_sys' => $stock_actual_sys,
                                    'stock_actual_fisico' => $stock_actual_fisico
                                ];
                            }
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al actualizar Stock Físico: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'toma_inv_detalle');
                            echo json_encode($msg);
                            die();
                        }
                    }
                }
            }
            if($actualizar['res'] == "modificado"){
                date_default_timezone_set('America/Lima');
                $fecha_subida = date("Y-m-d h:i:s");
                $this->model->actualizarEstadoTomaInv(1, $fecha_subida ,2, $id_toma);
                $logMessage = '';
                foreach ($datos_actualizados as $row) {
                    $logMessage .= "id_toma={$row['id_toma']},id_producto={$row['id_producto']},id_almacen={$row['id_almacen']},stock_actual_sys={$row['stock_actual_sys']},stock_actual_fisico={$row['stock_actual_fisico']}|";
                }
                $logMessage = rtrim($logMessage, '|');
                $this->model->registrarLog($_SESSION['id_usuario'], "UPDATE toma_inv_detalle SET {".$logMessage."}", $ingresoFecha, $_SESSION['id_almacen'], 'toma_inv_detalle');
                $msg = array('msg'=> 'Toma de Inventario N°'.$id_toma.' Actualizado', 'icono' => 'success');
            }else{
                $msg = array('msg'=> 'Error al Actualizar Toma de Inventario N°'.$id_toma, 'icono' => 'success');
            }
        } else {
            $msg = "Error al cargar el archivo XLSX";
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }

    public function procesarTomaInv(int $id)
    {
        $datos = $this->model->obtenerDatosTomaInv($id);
        date_default_timezone_set('America/Lima');
        $fecha_ingreso = date("Y-m-d");
        $ingresoFecha = date("Y-m-d h:i:s");
        $entradas = [];
        $salidas = [];
        $logMessageIngresos = '';
        $logMessageSalidas = '';
        $id_reAjusteIngresos = null;
        $id_reAjusteSalidas = null;
        
        foreach ($datos as $row) {
            if ($row['resta'] > 0) {
                $entradas[] = $row;
            } else if ($row['resta'] < 0) {
                $salidas[] = $row;
            }
        }
        
        if (!empty($entradas)) {
            $primer_producto_entrada = $entradas[0];
            $id_almacen_entradas = $primer_producto_entrada['id_almacen'];
            try{
                $id_reAjusteIngresos = $this->model->reAjusteIngresos($_SESSION['id_usuario'], NULL, 99998, 8, $fecha_ingreso, 0, 4, $primer_producto_entrada['id_almacen'], NULL, "EAJ", 0, 0, $fecha_ingreso);
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar Cabecera de Re-Ajuste Ingresos: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'compra');
                echo json_encode($msg);
                die();
            }
            $this->model->actualizarReAjusteIngresos("compra", $id_reAjusteIngresos['id'], $id_reAjusteIngresos['id']);
            foreach ($entradas as $valorEntradas) {
                try{
                    $data_ingresos = $this->model->reAjusteIngresosDetalle($id_reAjusteIngresos['id'], NULL, $valorEntradas['id_producto'], $valorEntradas['id_almacen'], abs($valorEntradas['resta']), 0, 0);
                    $logMessageIngresos .= $data_ingresos."|";
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar Ingresos de Re-Ajuste: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_compras');
                    echo json_encode($msg);
                    die();
                }
                $this->model->actualizarIdOp($id_reAjusteIngresos['id'], $id, $valorEntradas['id_producto']);
            }
            $logMessageIngresos = rtrim($logMessageIngresos, '|');
        }
        
        if (!empty($salidas)) {
            $primer_producto_salida = $salidas[0];
            $id_almacen_salidas = $primer_producto_salida['id_almacen'];
            try{
                $id_reAjusteSalidas = $this->model->reAjusteSalidas($_SESSION['id_usuario'], 99998, 9, $fecha_ingreso, 0, 4, $primer_producto_salida['id_almacen'], NULL, "SAJ", 0, 0, $fecha_ingreso);
            } catch (PDOException $e) {
                $msg = array('msg' => 'Error al registrar Cabecera de Re-Ajuste Salidas: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'venta');
                echo json_encode($msg);
                die();
            }
            $this->model->actualizarReAjusteIngresos("venta", $id_reAjusteSalidas['id'], $id_reAjusteSalidas['id']);
            foreach ($salidas as $valorSalidas) {
                try{
                    $data_salidas = $this->model->reAjusteSalidasDetalle($id_reAjusteSalidas['id'], $valorSalidas['id_producto'], $valorSalidas['id_almacen'], abs($valorSalidas['resta']), 0, 0);
                    $logMessageSalidas .= $data_salidas."|";
                } catch (PDOException $e) {
                    $msg = array('msg' => 'Error al registrar Salidas de Re-Ajuste: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                    $this->model->registrarLog($_SESSION['id_usuario'],$e->getMessage(), $ingresoFecha, $_SESSION['id_almacen'], 'detalle_ventas');
                    echo json_encode($msg);
                    die();
                }
                $this->model->actualizarIdOp($id_reAjusteSalidas['id'], $id, $valorSalidas['id_producto']);
            }
            $logMessageSalidas = rtrim($logMessageSalidas, '|');
        }

        $fecha_proceso = date("Y-m-d h:i:s");
        $this->model->actualizarEstadoTomaInv(2, $fecha_proceso ,0, $id);
        $this->model->registrarLog($_SESSION['id_usuario'], ($id_reAjusteIngresos['sql'] ?? ''). "|" . "{$logMessageIngresos}" . "|" . ($id_reAjusteSalidas['sql'] ?? ''). "|" . "{$logMessageSalidas}", $ingresoFecha, $_SESSION['id_almacen'], 'toma_inv_detalle');
        $msg = array('msg'=> 'Re-Ajuste realizado con Éxito', 'icono' => 'success');
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }

    
    public function verEstadoTomaInv(int $id)
    {
        $datos = $this->model->verEstadoTomaInv($id);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function anularTomaInv(int $id)
    {
        $data = $this->model->anularTomaInv(3, $id);
        if($data == "eliminado"){
            $msg = array('msg'=> 'Pedido Cancelado', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al Cancelar Pedido', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function validarStockTerminal()
    {
        $verificar_stock_actual = $this->model->verificarStockActual($_SESSION['id_usuario'], $_SESSION['almacen']);
        if($verificar_stock_actual == false){
            date_default_timezone_set('America/Lima');
            $this->model->verificarStock($_SESSION['id_usuario'], $_SESSION['almacen'], date("Y-m-d"), date("h:i:s"), 1);
            $msg = array('msg'=> 'Stock Verificado', 'icono' => 'success');
        }else if($verificar_stock_actual['estado_stock'] == 0){
            $this->model->modificarStock(1, $_SESSION['id_usuario'], $_SESSION['almacen']);
            $msg = array('msg'=> 'Stock Verificado', 'icono' => 'success');
        }else if ($verificar_stock_actual['estado_stock'] == 1){
            $msg = array('msg'=> 'El Stock ya se Encuentra Verificado', 'icono' => 'warning');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function validarKardexTerminal()
    {
        $verificar_stock_actual = $this->model->verificarStockActual($_SESSION['id_usuario'], $_SESSION['almacen']);
        if($verificar_stock_actual == false){
            date_default_timezone_set('America/Lima');
            $this->model->verificarKardex($_SESSION['id_usuario'], $_SESSION['almacen'], date("Y-m-d"), date("h:i:s"), 1);
            $msg = array('msg'=> 'Kardex Verificado', 'icono' => 'success');
        }else if($verificar_stock_actual['estado_kardex'] == 0){
            $this->model->modificarKardex(1, $_SESSION['id_usuario'], $_SESSION['almacen']);
            $msg = array('msg'=> 'Kardex Verificado', 'icono' => 'success');
        }else if ($verificar_stock_actual['estado_kardex'] == 1){
            $msg = array('msg'=> 'El Kardex ya se Encuentra Verificado', 'icono' => 'warning');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verValidacionStock()
    {
        $verificar_stock_actual = $this->model->verificarStockActual($_SESSION['id_usuario'], $_SESSION['almacen']);
        if($verificar_stock_actual == false){
            $msg = array('msg'=> 'no', 'icono' => 'success');
        }else if($verificar_stock_actual['estado_stock'] == 0){
            $msg = array('msg'=> 'no', 'icono' => 'error');
        }else if($verificar_stock_actual['estado_stock'] == 1){
            $msg = array('msg'=> 'si', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verValidacionKardex()
    {
        $verificar_stock_actual = $this->model->verificarStockActual($_SESSION['id_usuario'], $_SESSION['almacen']);
        if($verificar_stock_actual == false){
            $msg = array('msg'=> 'no', 'icono' => 'success');
        }else if($verificar_stock_actual['estado_kardex'] == 0){
            $msg = array('msg'=> 'no', 'icono' => 'error');
        }else if($verificar_stock_actual['estado_kardex'] == 1){
            $msg = array('msg'=> 'si', 'icono' => 'success');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>