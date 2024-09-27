<?php
require('fpdf.php');

class PDF_Custom extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 20);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(0, 10, utf8_decode('RESUMEN DE PEDIDOS'), 1, 1, 'C', true);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(30, 10, '#', 1, 0, 'C', true);
        $this->Cell(30, 10, 'DNI', 1, 0, 'C', true);
        $this->Cell(100, 10, 'NOMBRE', 1, 0, 'C', true);
        $this->Cell(30, 10, 'FECHA', 1, 1, 'C', true); // Agregué este Cell para la fecha
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($title) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($title), 0, 1);
    }
}

class PDF_Reporte extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(255, 255, 255, 0);
        $this->Cell(0, 4, utf8_decode('REPORTE DE VENTAS'), 0, 1, 'C', true);
        $this->SetFont('Arial', 'B', 7);
        $this->Cell(9, 4, utf8_decode('N° Ped.'), 0, 0, 'L', true);
        $this->Cell(16.5, 4, utf8_decode('Fecha'), 0, 0, 'C', true);
        $this->Cell(12, 4, utf8_decode('Dni/Ruc'), 0, 0, 'L', true);
        $this->Cell(56, 4, utf8_decode('Nombre / Razón Social'), 0, 0, 'L', true);
        $this->Cell(20, 4, utf8_decode('Asiento Cont.'), 0, 0, 'C', true);
        $this->Cell(13, 4, utf8_decode('Tipo Doc.'), 0, 0, 'C', true);
        $this->Cell(11, 4, utf8_decode('Serie'), 0, 0, 'C', true);
        $this->Cell(10, 4, utf8_decode('Numer'), 0, 0, 'C', true);
        $this->Cell(14, 4, utf8_decode('Cantidad'), 0, 0, 'C', true);
        $this->Cell(13, 4, utf8_decode('P. Unitario'), 0, 0, 'R', true);
        $this->Cell(18, 4, utf8_decode('Sub Total'), 0, 1, 'C', true);
    }

}

class PDF_Reporte_Otro extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 200, 200);
        $anchoPagina = $this->GetPageWidth();
        $anchoTablaPagos = 91;
        $centrarTablaX = ($anchoPagina - $anchoTablaPagos) / 2;
        $this->SetX($centrarTablaX);
        $this->Cell($anchoTablaPagos, 10, utf8_decode('RESUMEN POR TIPO DE PEDIDOS'), 1, 1, 'C', true);
        $this->SetX($centrarTablaX);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(50, 10, 'TIPO PEDIDO', 1, 0, 'C', true);
        $this->Cell(20, 10, 'CANTIDAD', 1, 0, 'C', true);
        $this->Cell(21, 10, 'SUB TOTAL', 1, 1, 'C', true);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($title) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($title), 0, 1);
    }
}

class PDF_movimiento_bancos extends FPDF {

    function __construct() {
        parent::__construct('L', 'mm', 'A4');
    }
    function Header() {
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(200, 200, 200);
        $this->Cell(0, 5, utf8_decode('RESUMEN DE MOVIMIENTOS BANCARIOS'), 1, 1, 'C', true);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, utf8_decode('FECHA'), 1, 0, 'C', true);
        $this->Cell(35, 5, utf8_decode('OPERACIÓN'), 1, 0, 'C', true);
        $this->Cell(30, 5, utf8_decode('N° OPERACIÓN'), 1, 0, 'C', true);
        $this->Cell(35, 5, utf8_decode('NOMBRE'), 1, 0, 'C', true);
        $this->Cell(30, 5, utf8_decode('CUENTA'), 1, 0, 'C', true);
        $this->Cell(30, 5, utf8_decode('CUENTA CONTABLE'), 1, 0, 'C', true);
        $this->Cell(14, 5, utf8_decode('TOTAL'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('B200'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('B100'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('B50'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('B20'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('B10'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M5'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M2'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M1'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M050'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M020'), 1, 0, 'C', true);
        $this->Cell(8, 5, utf8_decode('M010'), 1, 1, 'C', true);
    }
    

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($title) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($title), 0, 1);
    }
}

class PDF_Reporte_Entradas extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 200, 200);
        $anchoPagina = $this->GetPageWidth();
        $anchoTablaPagos = 200;
        $centrarTablaX = ($anchoPagina - $anchoTablaPagos) / 2;
        $this->SetX($centrarTablaX);
        $this->Cell($anchoTablaPagos, 5, utf8_decode('RESUMEN DE ENTRADAS'), 1, 1, 'C', true);
        $this->SetX($centrarTablaX);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(10, 5, utf8_decode('N°'), 1, 0, 'C', true);
        $this->Cell(20, 5, utf8_decode('FECHA'), 1, 0, 'C', true);
        $this->Cell(60, 5, utf8_decode('USUARIO'), 1, 0, 'C', true);
        $this->Cell(39, 5, utf8_decode('ALMACEN'), 1, 0, 'C', true);
        $this->Cell(25, 5, utf8_decode('DOCUMENTO'), 1, 0, 'C', true);
        $this->Cell(21, 5, utf8_decode('SERIE'), 1, 0, 'C', true);
        $this->Cell(25, 5, utf8_decode('CORRELATIVO'), 1, 1, 'C', true);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function ChapterTitle($title) {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 6, utf8_decode($title), 0, 1);
    }
}

?>