<?php
class FacturacionModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getGuiaCorrl(int $id_almacen, string $cod)
    {
        $sql = "SELECT * FROM series WHERE id_almacen = $id_almacen AND cod LIKE 'G%'";
        $data = $this->select($sql);
        return $data;
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function getDatosPais(string $tabla)
    {
        $sql = "SELECT * FROM $tabla";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarProvincia($cod_provincia)
    {
        $length_condition = strlen($cod_provincia);
        $sql = "
            SELECT *
            FROM ubigeo_provincias
            WHERE 
                cod LIKE '$cod_provincia%'
                AND LENGTH(cod) = CASE 
                    WHEN $length_condition = 1 THEN 3
                    WHEN $length_condition = 2 THEN 4
                    ELSE LENGTH(cod)
                END
        ";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarDistrito($cod_distrito)
    {
        $length_condition = strlen($cod_distrito);
        $sql = "
            SELECT *
            FROM ubigeo_distritos
            WHERE 
                cod LIKE '$cod_distrito%'
                AND LENGTH(cod) = CASE 
                    WHEN $length_condition = 3 THEN 5
                    WHEN $length_condition = 4 THEN 6
                    ELSE LENGTH(cod)
                END
        ";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarProductos()
    {
        $sql = "SELECT * FROM productos WHERE id_tarticulo != 3 AND estado = 1 AND afecta_compra = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProdCod(string $cod)
    {
        $sql = "SELECT * FROM productos WHERE id = '$cod'";
        $data = $this->select($sql);
        return $data;
    }

    public function getDatosGenerales(string $table)
    {
        $sql = "SELECT * FROM $table";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function dataDestinatario(int $id)
    {
        $sql = "SELECT * FROM contactos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarGuia(
        string $serie, int $numero, string $f_emision, string $f_traslado, int $ruc, string $razon_social, 
        int $id_motivo, int $id_modalidad, ?int $transporte_ruc = NULL, ?string $transporte_razon_social = NULL, 
        ?int $num_registro_mtc = NULL, ?string $chofer_nombres = NULL, ?string $chofer_apellidos = NULL, ?int $chofer_dni = NULL, 
        ?string $chofer_licencia = NULL, ?string $carro_placa = NULL, int $distrito_llegada, string $direccion_llegada, 
        int $distrito_partida, string $direccion_partida, ?float $peso_bruto_total = NULL, ?int $numero_bultos = NULL, 
        ?int $dest_ruc = NULL, ?string $dest_razon_social = NULL, ?int $dest_numero_documento = NULL, ?string $dest_apellidoPaterno = NULL, 
        ?string $dest_apellidoMaterno = NULL, ?string $dest_nombres = NULL
    )
    {
        $sql = "CALL registrarGuiaElectronica(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($serie, $numero, $f_emision, $f_traslado, $ruc, $razon_social, 
        $id_motivo, $id_modalidad, $transporte_ruc, $transporte_razon_social, 
        $num_registro_mtc, $chofer_nombres, $chofer_apellidos, $chofer_dni, 
        $chofer_licencia, $carro_placa, $distrito_llegada, $direccion_llegada, 
        $distrito_partida, $direccion_partida, $peso_bruto_total, $numero_bultos, 
        $dest_ruc, $dest_razon_social, $dest_numero_documento, $dest_apellidoPaterno, 
        $dest_apellidoMaterno, $dest_nombres);
        try {
            $data = $this->save_reg($sql, $datos);
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function registrarGuiaDetalle(int $id_guia, int $id_producto, float $cantidad, string $descripcion)
    {
        $sql = "CALL registrarGuiaDetalle(?, ?, ?, ?)";
        $datos = array($id_guia, $id_producto, $cantidad, $descripcion);
        try {
            $data = $this->save_trans($sql, $datos);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }
    public function actualizarGuiaTicket(string $numero_ticket, string $cod_resp, string $mj_rsp, ?string $hash = NULL, int $id_guia)
    {
        $sql = "UPDATE guias_electronicas SET ticket_guia = ?, respuesta_sunat_codigo = ?, respuesta_sunat_descripcion = ?, respuesta_hash = ? WHERE id = ?";
        $datos = array($numero_ticket, $cod_resp, $mj_rsp, $hash, $id_guia);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function listarGuiasElectronicas()
    {
        $sql = "SELECT * FROM guias_electronicas";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultaTipoProceso()
    {
        $sql = "SELECT proceso_guias FROM parametr";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarTicketGuia(int $id)
    {
        $sql = "SELECT * FROM guias_electronicas WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarTicketGuiaManual(string $serie, int $correlativo)
    {
        $sql = "SELECT * FROM guias_electronicas WHERE serie = '$serie' AND numero = $correlativo";
        $data = $this->select($sql);
        return $data;
    }
}
?>