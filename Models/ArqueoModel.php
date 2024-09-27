<?php
class ArqueoModel extends Query{
    private $nombre, $data, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getArqueo()
    {
        $sql = "SELECT ac.*, u.nombre FROM ".db_conect."arqueo_caja ac
        JOIN ".db_conect."usuarios u ON ac.id_usuario = u.id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuarioId($id_usu)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usu";
        $data = $this->select($sql);
        return $data; 
    }

    public function getProductosStock($id_almacen)
    {
        $sql = "SELECT v.*, a.nombre AS nombre_almacen FROM ".db_conect."v_stock_por_almacen v
        JOIN ".db_conect."almacen a ON v.id_almacen = a.id
        JOIN ".db_conect."productos p ON v.id_producto = p.id
        WHERE id_almacen = $id_almacen AND id_tarticulo != 2 AND stock != 0";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function traerCaja($id)
    {
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }


    public function registrarArqueo(int $id_usuario, int $id_almacen, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, float $monto_inicial, string $fecha_apertura)
    {
        $sql = "CALL registrar_arqueo(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($id_usuario, $id_almacen,
        empty($b_200) ? null : $b_200,
        empty($b_100) ? null : $b_100,
        empty($b_50) ? null : $b_50,
        empty($b_20) ? null : $b_20,
        empty($b_10) ? null : $b_10,
        empty($m_5) ? null : $m_5,
        empty($m_2) ? null : $m_2,
        empty($m_1) ? null : $m_1,
        empty($m_050) ? null : $m_050,
        empty($m_020) ? null : $m_020,
        empty($m_010) ? null : $m_010,
        $monto_inicial, $fecha_apertura);
        $data = $this->save($sql, $datos);
        if ($data != 0) {
            $res = "ok";
        } else {
            $res = "existe";
        }
        return $res;
    }

    public function modificarArqueo(int $id, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, float $monto_inicial){
        $sql = "CALL modificarArqueo(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($id,
        empty($b_200) ? null : $b_200,
        empty($b_100) ? null : $b_100,
        empty($b_50) ? null : $b_50,
        empty($b_20) ? null : $b_20,
        empty($b_10) ? null : $b_10,
        empty($m_5) ? null : $m_5,
        empty($m_2) ? null : $m_2,
        empty($m_1) ? null : $m_1,
        empty($m_050) ? null : $m_050,
        empty($m_020) ? null : $m_020,
        empty($m_010) ? null : $m_010,
        $monto_inicial);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function primerCorteEdit($id)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE id_arqueo = $id AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarArqueoPrimCorte($id)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE id = $id AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function ultimoCorteEdit($id)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE id_arqueo = $id AND estado = 2";
        $data = $this->select($sql);
        return $data;
    }

    public function editarPrimerCorte(int $id, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_total)
    {
        $sql = "CALL editarPrimerCorte(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($id,
        empty($b_200) ? null : $b_200,
        empty($b_100) ? null : $b_100,
        empty($b_50) ? null : $b_50,
        empty($b_20) ? null : $b_20,
        empty($b_10) ? null : $b_10,
        empty($m_5) ? null : $m_5,
        empty($m_2) ? null : $m_2,
        empty($m_1) ? null : $m_1,
        empty($m_050) ? null : $m_050,
        empty($m_020) ? null : $m_020,
        empty($m_010) ? null : $m_010, $monto_total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function primerCorte(int $id, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $fecha_corte, $monto_total, $estado)
    {
        $sql = "CALL realizar_primer_corte(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($id,
        empty($b_200) ? null : $b_200,
        empty($b_100) ? null : $b_100,
        empty($b_50) ? null : $b_50,
        empty($b_20) ? null : $b_20,
        empty($b_10) ? null : $b_10,
        empty($m_5) ? null : $m_5,
        empty($m_2) ? null : $m_2,
        empty($m_1) ? null : $m_1,
        empty($m_050) ? null : $m_050,
        empty($m_020) ? null : $m_020,
        empty($m_010) ? null : $m_010,$fecha_corte, $monto_total, $estado);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function editarUltimoCorte(int $id, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, $monto_total)
    {
        $sql = "CALL editarUltimoCorte(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $datos = array($id,
        empty($b_200) ? null : $b_200,
        empty($b_100) ? null : $b_100,
        empty($b_50) ? null : $b_50,
        empty($b_20) ? null : $b_20,
        empty($b_10) ? null : $b_10,
        empty($m_5) ? null : $m_5,
        empty($m_2) ? null : $m_2,
        empty($m_1) ? null : $m_1,
        empty($m_050) ? null : $m_050,
        empty($m_020) ? null : $m_020,
        empty($m_010) ? null : $m_010, $monto_total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function actualizarSerieDev(string $correlativo)
    {
        $sql = "UPDATE ".db_conect."series SET correlativo = ? WHERE cod = 'SDV'";
        $datos = array($correlativo);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function getMontoPrimerCorte($id_arqueo)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE id_arqueo = $id_arqueo";
        $data = $this->select($sql);
        return $data;
    }

    public function verificarCajaAbierta()
    {
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE estado = 1 OR estado = 2";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarArqueo(string $final, string $cierre, string $ventas, string $general, int $id)
    {
        $sql = "CALL actualizar_arqueo(?, ?, ?, ?, ?, ?)";
        $datos = array($final, $cierre, $ventas, $general, 0, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function actualizarCorteArqueo(string $monto_corte, int $id)
    {
        $sql = "CALL actualizar_corte_arqueo(?, ?, ?)";
        $datos = array($monto_corte, 2, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function modificarMontoArqueo(string $monto_pcorte, int $id)
    {
        $sql = "UPDATE ".db_conect."arqueo_caja SET monto_corte = ? WHERE id = ?";
        $datos = array($monto_pcorte, $id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function modificarMontoCierre(string $monto_cierre, int $id)
    {
        $sql = "UPDATE ".db_conect."arqueo_caja SET monto_final = ? WHERE id = ?";
        $datos = array($monto_cierre, $id);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function getMontoTotalVentas(int $id_usuario){
        $sql = "SELECT SUM(total) AS total FROM ".db_conect."pedidos WHERE id_usuario = $id_usuario AND estado = 0 AND apertura = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function getTotalVentas(int $id_usuario){
        $sql = "SELECT COUNT(total) AS total FROM ".db_conect."pedidos WHERE id_usuario = $id_usuario AND estado = 0 AND apertura = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function getMontoInicial(int $id_usuario){
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE estado = 1 OR estado = 2";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarApertura(int $id_usuario)
    {
        $sql = "UPDATE ".db_conect."pedidos SET apertura=? WHERE id_usuario = ?";
        $datos = array(0, $id_usuario);
        $this->save($sql, $datos);
    }

    public function VerificarStock()
    {
        $sql = "SELECT * FROM ".db_conect."ingreso_stock";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarEstadoStock($estado)
    {
        $sql = "UPDATE ".db_conect."ingreso_stock SET estado=? WHERE id = 1";
        $datos = array($estado);
        $this->save($sql, $datos);
    }

    public function getPedidos()
    {
        $sql = "SELECT * FROM ".db_conect."detalle_pedidos";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasGen(string $desde, string $hasta)
    {
        $sql = "SELECT dp.id, pd.descripcion, dp.precio, dp.cantidad, dp.id_producto_asoc
        FROM ".db_conect."detalle_pedidos dp
        JOIN ".db_conect."productos pd ON dp.id_producto = pd.id
        JOIN ".db_conect."pedidos p ON p.id = dp.id_pedido
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND pd.estado != 2 AND dp.id_producto_asoc IS NULL
        ORDER BY dp.id ASC";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechas(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT dp.id, pd.descripcion, dp.precio, dp.cantidad, dp.id_producto_asoc
        FROM ".db_conect."detalle_pedidos dp
        JOIN ".db_conect."productos pd ON dp.id_producto = pd.id
        JOIN ".db_conect."pedidos p ON p.id = dp.id_pedido
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND pd.estado != 2 AND p.id_usuario = $id_usuario AND dp.id_producto_asoc IS NULL
        ORDER BY dp.id ASC";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoPagos()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_pago";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPagosFecha(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT 
        SUM(IF(efectivo > 0.00, 1, 0)) AS EFECTIVO,
        SUM(IF(visa > 0.00, 1, 0)) AS VISA,
        SUM(IF(master_c > 0.00, 1, 0)) AS MASTER_CARD,
        SUM(IF(diners > 0.00, 1, 0)) AS DINERS_CLUB,
        SUM(IF(a_express > 0.00, 1, 0)) AS A_EXPRESS,
        SUM(IF(yape > 0.00, 1, 0)) AS YAPE,
        SUM(IF(plin > 0.00, 1, 0)) AS PLIN,
        SUM(IF(izipay > 0.00, 1, 0)) AS IZIPAY,
        SUM(IF(niubiz > 0.00, 1, 0)) AS NIUBIZ,
        SUM(efectivo) AS cant_EFECTIVO,
        SUM(visa) AS cant_VISA,
        SUM(master_c) AS cant_MASTER_CARD,
        SUM(diners) AS cant_DINERS_CLUB,
        SUM(a_express) AS cant_A_EXPRESS,
        SUM(yape) AS cant_YAPE,
        SUM(plin) AS cant_PLIN,
        SUM(izipay) AS cant_IZIPAY,
        SUM(niubiz) AS cant_NIUBIZ
        FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPagosFechaGen(string $desde, string $hasta)
    {
        $sql = "SELECT 
        SUM(IF(efectivo > 0.00, 1, 0)) AS EFECTIVO,
        SUM(IF(visa > 0.00, 1, 0)) AS VISA,
        SUM(IF(master_c > 0.00, 1, 0)) AS MASTER_CARD,
        SUM(IF(diners > 0.00, 1, 0)) AS DINERS_CLUB,
        SUM(IF(a_express > 0.00, 1, 0)) AS A_EXPRESS,
        SUM(IF(yape > 0.00, 1, 0)) AS YAPE,
        SUM(IF(plin > 0.00, 1, 0)) AS PLIN,
        SUM(IF(izipay > 0.00, 1, 0)) AS IZIPAY,
        SUM(IF(niubiz > 0.00, 1, 0)) AS NIUBIZ,
        SUM(efectivo) AS cant_EFECTIVO,
        SUM(visa) AS cant_VISA,
        SUM(master_c) AS cant_MASTER_CARD,
        SUM(diners) AS cant_DINERS_CLUB,
        SUM(a_express) AS cant_A_EXPRESS,
        SUM(yape) AS cant_YAPE,
        SUM(plin) AS cant_PLIN,
        SUM(izipay) AS cant_IZIPAY,
        SUM(niubiz) AS cant_NIUBIZ
        FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getArqueoFechas(string $desde, string $hasta)
    {
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE fecha_cierre BETWEEN '$desde' AND '$hasta'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPrimerCorteFechas(string $desde, string $hasta)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarDetalleCompra(int $id_compra, ?int $id_venta = NULL, int $id_producto, int $id_almacen, float $cantidad, string $precio, string $sub_total, ?string $observacion = NULL)
    {
        $sql = "EXEC ".db_conect." registrarDetalleCompra @p_id_compra = ?, @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?, @p_observacion = ?";
        $datos = array($id_compra, $id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total, $observacion);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleCompra('$id_compra', '$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total', '$observacion')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function getPedidosId($id_salida)
    {
        $sql = "SELECT v.*, a_ini.nombre AS nombre_ini, a_fin.nombre AS nombre_fin, u.nombre AS nombre_usuario
        FROM ".db_conect."venta v
        JOIN ".db_conect."almacen a_ini ON a_ini.id = v.id_almacen_ini
        JOIN ".db_conect."almacen a_fin ON a_fin.id = v.id_almacen_fin
        INNER JOIN ".db_conect."usuarios u ON u.id = v.id_usuario
        WHERE v.correlativo = $id_salida";
        $data = $this->select($sql);
        return $data; 
    }

    public function getDetalleSalida($id)
    {
        $sql = "SELECT d.*, p.descripcion, p.codigo FROM ".db_conect."detalle_ventas d
        JOIN ".db_conect."productos p ON p.id = d.id_producto WHERE d.id_venta = $id";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM ".db_conect."configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarCompra(int $id_usuario, ?int $id_venta = NULL, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, string $total, string $fecha_actual)
    {
        $sql = "EXEC ".db_conect."registrarCompra @p_id_usuario = ?, @p_id_venta = ?, @p_id_proveedor = ?, @p_id_tipo_operacion = ?, @p_fecha_ingreso = ?, @p_tipo_cambio = ?, @p_tipo_documento = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_serie = ?, @p_correlativo = ?, @p_total = ?, @p_fecha_actual = ?";
        $datos = array($id_usuario, $id_venta, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $fecha_actual);
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "CALL registrarCompra('$id_usuario', '$id_venta', '$id_proveedor', '$tipo_operacion', '$fecha_ingreso', '$tipo_cambio', '$tipo_documento', '$id_almacen_ini', '$id_almacen_fin', '$serie', '$correlativo', '$total', '$fecha_actual')";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function registrarVenta(int $id_usuario, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, ?string $serie = null, ?int $correlativo = NULL, string $total, string $fecha_actual)
    {
        $sql = "EXEC ".db_conect." registrarVenta @p_id_usuario = ?, @p_id_proveedor = ?, @p_id_tipo_operacion = ?, @p_fecha_ingreso = ?, @p_tipo_cambio = ?, @p_tipo_documento = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_serie = ?, @p_correlativo = ?, @p_total = ?, @p_fecha_actual = ?";
        $datos = array($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $fecha_actual);
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "CALL registrarVenta('$id_usuario', '$id_proveedor', '$tipo_operacion', '$fecha_ingreso', '$tipo_cambio', '$tipo_documento', '$id_almacen_ini', '$id_almacen_fin', '$serie', '$correlativo', '$total', '$fecha_actual')";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function registrarDetalleVenta(int $id_venta, int $id_producto, int $id_almacen, float $cantidad, string $precio, string $sub_total, ?string $observacion = NULL)
    {
        $sql = "EXEC ".db_conect." registrarDetalleVenta @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?, @p_observacion = ?";
        $datos = array($id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total, $observacion);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleVenta('$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total', '$observacion')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function editarApertura(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE id = $id";
        $data = $this->select($sql);
        return $data; 
    }
    
    public function getSeries()
    {
        $sql = "SELECT * FROM ".db_conect."series WHERE cod = 'SDV'";
        $data = $this->select($sql);
        return $data; 
    }

    public function getId($tabla)
    {
        $sql = "SELECT MAX(id) AS id FROM ".db_conect."$tabla";
        $data = $this->select($sql);
        return $data;
    }

    public function getUltimoCorteFechas(string $desde, string $hasta)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_arqueo WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 2";
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

    public function consutlarEstados($id_usuario, $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."verstockkardex 
        WHERE CAST(fecha AS DATE) = CAST(GETDATE() AS DATE) 
        AND hora < '23:59:59' 
        AND id_usuario = $id_usuario 
        AND id_almacen = $id_almacen 
        AND estado_stock = 1 
        AND estado_kardex = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarLog(int $id_usuario, string $accion, string $fecha, int $id_almacen, ?string $tabla_nombre = NULL)
    {
        $sql = "INSERT INTO ".db_conect."logs(id_usuario, accion, fecha_hora, id_almacen, tabla_nombre) VALUES (?,?,?,?,?)";
        $datos = array($id_usuario, $accion, $fecha, $id_almacen, $tabla_nombre);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }
}
?>