<?php
class EntradasModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTipoDoc()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_documento";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getId()
    {
        $sql = "SELECT MAX(id) AS id_pedido FROM ".db_conect."pedidos";
        $data = $this->select($sql);
        return $data;
    }

    public function getEntradas()
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE linea = 4";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultarPrecioProd(int $id_entrada)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = '$id_entrada'";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarPedido(int $id_usuario, int $id_almacen_ini, ?string $id_almacen_fin = null, float $base, float $igv, float $total, string $fecha_actual, int $tipo_pedido)
    {
        $sql = "EXEC ".db_conect."registrarPedido @p_id_usuario = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_base = ?, @p_igv = ?, @p_total = ?, @p_fecha_actual = ?, @p_tipo_pedido = ?";
        $datos = array($id_usuario, $id_almacen_ini, $id_almacen_fin, $base, $igv, $total, $fecha_actual, $tipo_pedido);
        try {
            $data = $this->save_reg($sql, $datos);
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function actualizarEstadoPedido(?int $tipo_documento = NULL, ?string $serie = NULL, ?int $correlativo = NULL, ?int $dni = NULL, ?string $nombres = NULL, ?string $apellido_paterno = NULL, ?string $apellido_materno = NULL, ?string $ruc = NULL, ?string $razon_social = NULL, ?string $direccion = NULL, string $efectivo, string $visa, string $master, string $diners, string $a_express, string $yape, string $plin, string $izipay, string $niubiz, ?string $pos = NULL, ?string $transferencia = NULL, string $op_visa, string $op_mast, string $op_diners, string $op_express, string $op_yape, string $op_plin, string $op_izipay, string $op_niubiz, ?string $op_pos = NULL, ?string $op_transf = NULL, int $estado, ?string $propina = NULL, int $idPedido)
    {
        $efectivo = floatval($efectivo);
        $visa = floatval($visa);
        $master = floatval($master);
        $diners = floatval($diners);
        $a_express = floatval($a_express);
        $yape = floatval($yape);
        $plin = floatval($plin);
        $izipay = floatval($izipay);
        $niubiz = floatval($niubiz);
        $pos = NULL;
        $propina = NULL;
        $transferencia = NULL;
        $op_pos = NULL;
        $op_transf = NULL;

        $op_visa = empty($op_visa) ? null : $op_visa;
        $op_mast = empty($op_mast) ? null : $op_mast;
        $op_diners = empty($op_diners) ? null : $op_diners;
        $op_express = empty($op_express) ? null : $op_express;
        $op_yape = empty($op_yape) ? null : $op_yape;
        $op_plin = empty($op_plin) ? null : $op_plin;
        $op_izipay = empty($op_izipay) ? null : $op_izipay;
        $op_niubiz = empty($op_niubiz) ? null : $op_niubiz;
        $op_pos = empty($op_pos) ? null : $op_pos;
        $op_transf = empty($op_transf) ? null : $op_transf;

        $sql = "EXEC ".db_conect."actualizarEstadoPedido @p_tipo_documento = ?, @p_serie = ?, @p_correlativo = ?, @p_dni = ?, @p_nombres = ?, @p_apellido_paterno = ?, @p_apellido_materno = ?, @p_ruc = ?, @p_razon_social = ?, @p_direccion = ?, @p_efectivo = ?, @p_visa = ?, @p_master = ?, @p_diners = ?, @p_a_express = ?, @p_yape = ?, @p_plin = ?, @p_izipay = ?, @p_niubiz = ?, @p_pos = ?, @p_transferencia = ?, @p_op_visa = ?, @p_op_mast = ?, @p_op_diners = ?, @p_op_express = ?, @p_op_yape = ?, @p_op_plin = ?, @p_op_izipay = ?, @p_op_niubiz = ?, @p_op_pos = ?, @p_op_transf = ?, @p_estado = ?, @p_propina = ?, @p_idPedido = ?";
        $datos = array($tipo_documento, $serie, $correlativo, $dni, $nombres, $apellido_paterno, $apellido_materno, $ruc, $razon_social, $direccion, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $pos, $transferencia, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $op_pos, $op_transf, $estado, $propina, $idPedido);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function registrarDetallePedido(string $id_pedido, int $id_usuario, int $id_producto, int $id_almacen, string $precio_venta, float $cantidad, ?float $base = NULL, ?float $igv = NULL, float $total, ?int $id_producto_asoc = null)
    {
        $sql = "EXEC ".db_conect."registrarDetallePedido @p_id_pedido = ?, @p_id_usuario = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_precio_venta = ?, @p_cantidad = ?, @p_base = ?, @p_igv = ?, @p_total = ?, @p_id_producto_asoc = ?";
        $datos = array($id_pedido, $id_usuario, $id_producto, $id_almacen, $precio_venta, $cantidad, $base, $igv, $total, $id_producto_asoc);
        try {
            $data = $this->save_trans($sql, $datos);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function getEntrada($id_entrada)
    {
        $sql = "SELECT * FROM ".db_conect."entradas WHERE id = $id_entrada";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarCorr(string $parametro, int $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."series WHERE id_almacen = $id_almacen AND serie LIKE '$parametro%'";
        $data = $this->select($sql);
        return $data;
    }

    public function getProduct($id_producto)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data;
    }

    public function getProductCort()
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE codigo = 'TKCORTESI'";
        $data = $this->select($sql);
        return $data;
    }

    public function getProductPromot()
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE codigo = 'TKPROMOT'";
        $data = $this->select($sql);
        return $data;
    }

    public function getEntrNorm()
    {
        $sql = "SELECT * FROM ".db_conect."entradas WHERE cod_prod = 'TKVENTA'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getEntrCort()
    {
        $sql = "SELECT * FROM ".db_conect."entradas WHERE cod_prod = 'TKCORTESI'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getEntrPromot()
    {
        $sql = "SELECT * FROM ".db_conect."entradas WHERE cod_prod = 'TKPROMOT'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function regEntrNormal(int $id_usuario, string $cod_prod, ?string $dni, ?string $ruc, string $nombre_cli, string $genero, string $fecha_actual, string $total, float $tipo_cambio)
    {
        $dni = ($dni === " ") ? null : $dni;
        $ruc = ($ruc === " ") ? null : $ruc;
        $sql = "EXEC disco_2023.reg_entrada_normal 
        @p_id_usuario = ?, 
        @p_cod_prod = ?, 
        @p_dni = ?, 
        @p_ruc = ?, 
        @p_nombre_cli = ?, 
        @p_genero = ?, 
        @p_fecha_actual = ?, 
        @p_total = ?, 
        @p_tipo_cambio = ?";
        $datos = array($id_usuario, $cod_prod, $dni, $ruc, $nombre_cli, $genero, $fecha_actual, $total, $tipo_cambio);
        try {
            $data = $this->save_trans($sql, $datos);
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function regEntrCort(int $id_usuario, string $cod_prod, string $nombre_cli, string $genero, string $token, string $fecha_actual, string $total)
    {
        $sql = "INSERT INTO ".db_conect."entradas (id_usuario, cod_prod, nombre, genero, token, fecha, total) VALUES (?,?,?,?,?,?,?)";
        $datos = array($id_usuario, $cod_prod, $nombre_cli, $genero, $token, $fecha_actual, $total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function regEntrPromot(int $id_usuario, string $cod_prod, string $nombre_cli, string $genero, string $token, string $fecha_actual, string $total)
    {
        $sql = "INSERT INTO ".db_conect."entradas (id_usuario, cod_prod, nombre, genero, token, fecha, total) VALUES (?,?,?,?,?,?,?)";
        $datos = array($id_usuario, $cod_prod, $nombre_cli, $genero, $token, $fecha_actual, $total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarVenta(int $id_usuario, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin, ?string $serie = NULL, ?int $correlativo = NULL, string $total, string $fecha_actual)
    {
        $sql = "INSERT INTO ".db_conect."venta (id_usuario, tipo_cambio, id_tipo_doc, id_almacen_ini, id_almacen_fin, serie, correlativo, total, fecha) VALUES (?,?,?,?,?,?,?,?,?)";
        $datos = array($id_usuario, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $fecha_actual);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function actualizarSerieVnt(string $parametro, string $correlativo, int $id_almacen)
    {
        $sql = "UPDATE ".db_conect."series SET correlativo = ? WHERE serie LIKE '$parametro%' AND id_almacen = $id_almacen";
        $datos = array($correlativo);
        $data = $this->save($sql, $datos);
        return $data;
    }


    public function registrarDetalleVenta(int $id_venta, int $id_producto, int $tipo_operacion, int $id_almacen, int $cantidad, string $precio, string $sub_total)
    {
        $sql = "INSERT INTO ".db_conect."detalle_ventas (id_venta, id_producto, id_tipo_operacion, id_almacen, cantidad, precio, sub_total) VALUES (?,?,?,?,?,?,?)";
        $datos = array($id_venta, $id_producto, $tipo_operacion, $id_almacen, $cantidad, $precio, $sub_total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarTokenCort($token)
    {
        $sql = "SELECT 
        token_pedido, 
        cantidad, 
        solicitante, 
        (cantidad - (SELECT COUNT(token) FROM entradas WHERE token = token_pedido)) AS disponible
    FROM token_pedido
    WHERE token_pedido = '$token' AND estado = 1 AND id_tipo_token = 1;";
        $data = $this->select($sql);
        $data = $this->select($sql);
        return $data;
    }

    public function verificarTokenPromot($token)
    {
        $sql = "SELECT 
        token_pedido, 
        cantidad, 
        solicitante, 
        (cantidad - (SELECT COUNT(token) FROM entradas WHERE token = token_pedido)) AS disponible
    FROM token_pedido
    WHERE token_pedido = '$token' AND estado = 1 AND id_tipo_token = 2;";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarTokenCort($token)
    {
        $sql = "UPDATE token_pedido SET estado = ? WHERE token_pedido = ?";
        $datos = array(0, $token);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function contarCantTokPromot($token)
    {
        $sql = "SELECT COUNT(cod_prod) as cant_tok FROM entradas WHERE cod_prod = 'TKPROMOT' AND token = '$token'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, string $permiso)
    {
        $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM ".db_conect."permisos p
        INNER JOIN ".db_conect."detalle_permisos d ON p.id = d.id_permiso
        WHERE d.id_usuario = $id_usuario AND p.permiso = '$permiso'";
        $data = $this->selectAll($sql);
        return $data;
    }
}
?>