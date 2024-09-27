<?php
require'C:/xampp/htdocs/disco2023/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Exports extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function exportToExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $titulo = $_POST['titulo'];
        $cabeceras = json_decode($_POST['cabeceras'], true);
        $contenido = json_decode($_POST['contenido'], true);

        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells('A1:' . chr(65 + count($cabeceras) - 1) . '1'); // Fusionar celdas para el título

        $columna = 'A';
        foreach ($cabeceras as $cabecera) {
            $sheet->setCellValue($columna . '2', $cabecera);
            $columna++;
        }
        $fila = 3;
        foreach ($contenido as $datosFila) {
            $columna = 'A';
            foreach ($datosFila as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $titulo . '.xlsx"'); // Usar el título como nombre de archivo
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
?>