<?php
require'C:/xampp/htdocs/disco2023/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Kardex extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 3, 5);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['almacenes'] = $this->model->getAlmacenes();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function kardex_terminal()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'TERMINAL KARDEX');
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['almacenes'] = $this->model->getUsuariosAlmc($id_usuario);
            $this->views->getView($this, "kardex_terminal", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function listar()
    {
        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnCodProd(' . $data[$i]['id'] . ');"><i class="fas fa-plus"></i></button>
            </div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarKardexProd()
    {
        $cod = $_POST['cod_prod'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $id_almacen = $_POST['almacen'];
        if($id_almacen < 0){
            $data = $this->model->buscarKardexProdGen($cod, $fecha_hasta);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }else{
            $data = $this->model->buscarKardexProd($cod, $fecha_hasta, $id_almacen);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function buscarKardexId(string $ids)
    {
        $ids_array = explode(',', $ids);
        $id_producto = $ids_array[0];
        $id_almacen = $ids_array[1];
        $data = $this->model->buscarKardexId($id_producto, $id_almacen);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarProdId($id)
    {
        $data = $this->model->buscarProdId($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function ExportarRangoCodigo()
    {
        // Crear un nuevo libro de Excel y obtener la hoja activa
        $spreadsheet = new Spreadsheet();
        $writer = new Xlsx($spreadsheet);
    
        $codigo_inicial = $_POST['codigo_inicial'];
        $codigo_final = $_POST['codigo_final'];
        $busca_idcodigo_inicial = $this->model->buscarProdCod($codigo_inicial);
        $busca_idcodigo_final = $this->model->buscarProdCod($codigo_final);
        $fecha_inicial = $_POST['fecha_inicial'];
        $fecha_final = $_POST['fecha_final'];
        $rango_codigo = $this->model->rangoCodigo($busca_idcodigo_inicial['id'], $busca_idcodigo_final['id'], $fecha_inicial, $fecha_final);
        if (empty($rango_codigo)) {
            echo "No hay datos para exportar.";
            exit;
        }else{
            $datos_por_codigo = [];
            $datos_por_descripcion = [];
            foreach ($rango_codigo as $dato) {
                $descripcion_producto = $dato['descripcion'];
                if (!isset($datos_por_descripcion[$descripcion_producto])) {
                    $datos_por_descripcion[$descripcion_producto] = [];
                }
                $datos_por_descripcion[$descripcion_producto][] = $dato;
            }
            
            foreach ($datos_por_descripcion as $descripcion_producto => $datos) {
                $descripcion_producto_truncada = substr($descripcion_producto, 0, 31); // Truncar la descripción del producto a 31 caracteres
                $stock = 0; // Reiniciamos el stock para cada producto
                $sheet = $spreadsheet->createSheet()->setTitle($descripcion_producto_truncada);
            
                $sheet->setCellValue('A1', 'FECHA');
                $sheet->setCellValue('B1', 'OPERACIÓN');
                $sheet->setCellValue('C1', 'SERIE');
                $sheet->setCellValue('D1', 'CORRELATIVO');
                $sheet->setCellValue('E1', 'ALM. INICIAL');
                $sheet->setCellValue('F1', 'INGRESO');
                $sheet->setCellValue('G1', 'SALIDA');
                $sheet->setCellValue('H1', 'STOCK');
            
                $fila = 2;
                foreach ($datos as $dato) {
                    // Calculamos el saldo
                    $saldo = $stock + ($dato['ingresos'] - $dato['salidas']);
            
                    // Llenamos las celdas con los valores correspondientes
                    $sheet->setCellValue('A' . $fila, $dato['fecha_ingreso']);
                    $sheet->setCellValue('B' . $fila, $dato['operacion']);
                    $sheet->setCellValue('C' . $fila, $dato['serie']);
                    $sheet->setCellValue('D' . $fila, $dato['correlativo']);
                    $sheet->setCellValue('E' . $fila, $dato['almacen']);
                    $sheet->setCellValue('F' . $fila, $dato['ingresos']);
                    $sheet->setCellValue('G' . $fila, $dato['salidas']);
                    $sheet->setCellValue('H' . $fila, $saldo);
            
                    // Actualizamos el valor de $stock para la próxima iteración
                    $stock = $saldo;
            
                    $fila++;
                }
            }
            $spreadsheet->removeSheetByIndex(0);
        
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_de_Ventas.xlsx"');
            header('Cache-Control: max-age=0');
        
            $writer->save('php://output');
        
            exit;
        }
    }

    public function ExportarKardexGen()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = [
            'cperiodoc', 'Origen', 'IdOperacion', 'id_detalle', 'id_producto', 'id_familia', 'nombre_familia', 'id_receta',
            'CodigoProd', 'descripcion', 'unidad_med', 'foto', 'precio_venta', 'id_tarticulo', 'estado_producto', 'afecta_venta',
            'id_tipo_operacion', 'codigo_operacion', 'operacion', 'cstacodigo', 'fecha', 'fecha_ingreso', 'serie', 'correlativo',
            'id_almacen', 'almacen', 'ingresos', 'salidas', 'precio', 'sub_total', 'igv', 'total', 'flag_dbf', 'cconnumero', 'cperiodo',
            'cta_ventas', 'cta_mventas', 'cta_mvtacor', 'cta_mvtarepre', 'cta_mdesctrab', 'cta_cventas', 'codigolinea', 'nombrelinea',
            'tipo_pedido', 'nombreped', 'cta_total', 'glosa', 'autorizado', 'fecha_desc', 'trab_desc', 'consumo_artista', 'cfmanivel',
            'cfmaserie', 'cfmanumero'
        ];

        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $column++;
        }
        $fecha_inicial_gen = $_POST['fecha_inicial_gen'];
        $fecha_final_gen = $_POST['fecha_final_gen'];
        $data = $this->model->getKardexGen($fecha_inicial_gen, $fecha_final_gen);
        $row = 2;
        foreach ($data as $row_data) {
            $column = 'A';
            foreach ($headers as $key => $header) {
                $sheet->setCellValue($column . $row, $row_data[$header]);
                $column++;
            }
            $row++;
        }
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="REPORTE KARDEX GENERAL.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    
        exit;
    }


    public function bucarInvdetal(string $id_detalle)
    {
        $data = $this->model->bucarInvdetal($id_detalle, $_POST['id_producto']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function getPrimeraFecha()
    {
        $data['desde'] = $this->model->getPrimeraFecha(1);
        $data['hasta'] = $this->model->getPrimeraFecha(2);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>