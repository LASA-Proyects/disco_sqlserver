<?php
class PedidosModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductos()
    {
        $sql = "SELECT p.*, f.nombre AS nombre_familia FROM ".db_conect."productos p INNER JOIN familia f ON p.id_familia = f.id";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getProdRect($id)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_receta WHERE id_receta = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function listarHistorialEntradas(int $id_usuario)
    {
        $sql = "SELECT p.id, p.fecha, u.nombre AS nombre_usuario, a.nombre AS nombre_almacen, td.nombre AS documento, p.serie, p.correlativo, p.estado FROM ".db_conect."pedidos p
        JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        JOIN ".db_conect."tipo_documento td ON td.id = p.tipo_documento
        JOIN ".db_conect."usuarios u ON u.id = p.id_usuario
        WHERE tipo_pedido = 0 AND p.id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getEntradasFecha(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT p.id, p.fecha, u.nombre AS nombre_usuario, a.nombre AS nombre_almacen, td.nombre AS documento, p.serie, p.correlativo, p.estado, p.total FROM ".db_conect."pedidos p
        JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        JOIN ".db_conect."tipo_documento td ON td.id = p.tipo_documento
        JOIN ".db_conect."usuarios u ON u.id = p.id_usuario
        WHERE tipo_pedido = 0 AND p.id_usuario = $id_usuario AND p.fecha BETWEEN '$desde' AND '$hasta'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoDocumentos()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_documento";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function validarIGV($id_producto)
    {
        $sql = "SELECT afecta_igv FROM ".db_conect."productos WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data;
    }

    public function getBebidasCombo($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina)
    {
        $sql = "EXEC disco_2023.stock_general_combo @p_id_almacen = ?, @p_id_familia = ?, @p_linea = ?, @p_id_tarticulo = ?, @p_desde = ?, @p_porPagina = ?";
        $datos = array($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina);
        $data = $this->selectAll($sql, $datos);
        return $data;
    }

    public function getProductosCocteleria($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina)
    {
        $sql = "EXEC stock_general($id_almacen,$id_familia, $linea, $id_tarticulo, $desde,$porPagina)";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getProductosCocina($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina)
    {
        $sql = "EXEC stock_general($id_almacen,$id_familia, $linea, $id_tarticulo, $desde,$porPagina)";
        $data = $this->selectall($sql);
        return $data;
    }

    public function verificarArqueo()
    {
        $sql = "SELECT TOP 1 * FROM ".db_conect."arqueo_caja ORDER BY id DESC";
        $data = $this->select($sql);
        return $data;
    }

    public function getProductoPed(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getFamilia($id_linea)
    {
        $sql = "SELECT f.* FROM ".db_conect."familia f JOIN ".db_conect." linea_productos lp ON lp.id = f.id_linea
        WHERE f.id_linea = $id_linea AND f.id != 3";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getUsuariosLogin()
    {
        $sql = "SELECT *
        FROM ".db_conect."usuarios
        WHERE GETDATE() BETWEEN fecha_ini AND fecha_fin AND tipo_usuario = 4";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuariosLoginDesc()
    {
        $sql = "SELECT *
        FROM ".db_conect."usuarios
        WHERE GETDATE() BETWEEN fecha_ini AND fecha_fin AND tipo_usuario = 3";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getAlmacen($id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."almacen WHERE id = $id_almacen";
        $data = $this->select($sql);
        return $data;
    }

    public function getTokensPedidos()
    {
        $sql = "SELECT * FROM ".db_conect."token_pedido";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getUsuarios(int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function getUsuariosPedidoTerminal(int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT * FROM ".db_conect."usuarios";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRuta()
    {
        $sql = "SELECT * FROM ".db_conect."ruta_facturador";
        $data = $this->select($sql);
        return $data;
    }

    public function getRucEmpresa()
    {
        $sql = "SELECT * FROM ".db_conect."configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function getDetallePed(int $id)
    {
        $sql = "SELECT d.*, p.id AS id_pro, p.descripcion FROM ".db_conect."detalle_ped_tmp d
        INNER JOIN ".db_conect."productos p ON d.id_producto = p.id
        WHERE d.id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechas(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT p.* FROM ".db_conect."pedidos p
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario AND p.tipo_pedido = 1
        ORDER BY p.id ASC";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasGeneral(string $desde, string $hasta)
    {
        $sql = "SELECT p.* FROM ".db_conect."pedidos p
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.tipo_pedido = 1
        ORDER BY p.id ASC";
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
        SUM(efectivo) AS EFECTIVO,
        SUM(visa) AS VISA,
        SUM(master_c) AS MASTER_CARD,
        SUM(diners) AS DINERS_CLUB,
        SUM(a_express) AS A_EXPRESS,
        SUM(yape) AS YAPE,
        SUM(plin) AS PLIN,
        SUM(izipay) AS IZIPAY,
        SUM(niubiz) AS NIUBIZ ,
        SUM(IF(efectivo > 0, 1, 0)) AS cant_efectivo,
        SUM(IF(visa > 0, 1, 0)) AS cant_visa,
        SUM(IF(master_c > 0, 1, 0)) AS cant_master_c,
        SUM(IF(diners > 0, 1, 0)) AS cant_diners,
        SUM(IF(a_express > 0, 1, 0)) AS cant_a_express,
        SUM(IF(yape > 0, 1, 0)) AS cant_yape,
        SUM(IF(plin > 0, 1, 0)) AS cant_plin,
        SUM(IF(izipay > 0, 1, 0)) AS cant_izipay,
        SUM(IF(niubiz > 0, 1, 0)) AS cant_niubiz

    FROM ".db_conect."pedidos
    WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND tipo_pedido = 1 AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPagosGeneral(string $desde, string $hasta)
    {
        $sql = "SELECT 
        SUM(efectivo) AS EFECTIVO,
        SUM(visa) AS VISA,
        SUM(master_c) AS MASTER_CARD,
        SUM(diners) AS DINERS_CLUB,
        SUM(a_express) AS A_EXPRESS,
        SUM(yape) AS YAPE,
        SUM(plin) AS PLIN,
        SUM(izipay) AS IZIPAY,
        SUM(niubiz) AS NIUBIZ ,
        SUM(IF(efectivo > 0, 1, 0)) AS cant_EFECTIVO,
        SUM(IF(visa > 0, 1, 0)) AS cant_VISA,
        SUM(IF(master_c > 0, 1, 0)) AS cant_MASTER_CARD,
        SUM(IF(diners > 0, 1, 0)) AS cant_DINERS_CLUB,
        SUM(IF(a_express > 0, 1, 0)) AS cant_A_EXPRESS,
        SUM(IF(yape > 0, 1, 0)) AS cant_YAPE,
        SUM(IF(plin > 0, 1, 0)) AS cant_PLIN,
        SUM(IF(izipay > 0, 1, 0)) AS cant_IZIPAY,
        SUM(IF(niubiz > 0, 1, 0)) AS cant_NIUBIZ
    FROM
        ".db_conect."pedidos
    WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND tipo_pedido = 1";
        $data = $this->selectAll($sql);
        return $data;
    }


    public function verificarDNI(int $dni)
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE dni = $dni";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarContactoDNI(int $dni, string $correo, string $nombres, string $apellidoPaterno, string $apellidoMaterno, int $id_tipo_persona, string $fecha_alta)
    {
        $sql = "INSERT INTO ".db_conect."contactos(dni, correo, nombres, apellidoPaterno, apellidoMaterno, id_tipo_persona, fecha_alta) VALUES (?,?,?,?,?,?,?)";
        $datos = array($dni, $correo, $nombres, $apellidoPaterno, $apellidoMaterno, $id_tipo_persona, $fecha_alta);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    
    public function registrarContactoRUC(string $ruc, string $correo, string $razon_social, string $direccion, int $id_tipo_persona, string $fecha_alta)
    {
        $sql = "INSERT INTO ".db_conect."contactos(ruc, correo, razon_social, direccion, id_tipo_persona, fecha_alta) VALUES (?,?,?,?,?,?)";
        $datos = array($ruc, $correo, $razon_social, $direccion, $id_tipo_persona, $fecha_alta);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarRUC(int $ruc)
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE ruc = $ruc";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarEstadoPedido(?int $tipo_documento = NULL, ?string $serie = NULL, ?int $correlativo = NULL, ?int $dni = NULL, ?string $nombres = NULL, ?string $apellido_paterno = NULL, ?string $apellido_materno = NULL, ?string $ruc = NULL, ?string $razon_social = NULL, ?string $direccion = NULL, string $efectivo, string $visa, string $master, string $diners, string $a_express, string $yape, string $plin, string $izipay, string $niubiz, string $pos, string $transferencia, string $op_visa, string $op_mast, string $op_diners, string $op_express, string $op_yape, string $op_plin, string $op_izipay, string $op_niubiz, string $op_pos, string $op_transf, int $estado, ?string $propina = NULL, int $idPedido)
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
        $pos = floatval($pos);
        $propina = floatval($propina);
        $transferencia = floatval($transferencia);

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

    public function EditarPedido(string $efectivo, string $visa, string $master, string $diners, string $a_express, string $yape, string $plin, string $izipay, string $niubiz, string $pos, string $transferencia, string $op_visa, string $op_mast, string $op_diners, string $op_express, string $op_yape, string $op_plin, string $op_izipay, string $op_niubiz, string $op_pos, string $op_transf, int $idPedido)
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
        $pos = floatval($pos);
        $transferencia = floatval($transferencia);

        $sql = "EXEC EditarPedido(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $datos = array($efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $pos, $transferencia, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $op_pos, $op_transf, $idPedido);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function actualizarSerieVnt(string $parametro, string $correlativo, int $id_almacen)
    {
        $sql = "UPDATE ".db_conect."series SET correlativo = ? WHERE serie LIKE '$parametro" . "00$id_almacen'";
        $datos = array($correlativo);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function getProdCodPedido(string $codigoPed)
    {
        $sql = "SELECT * FROM ".db_conect."pedidos WHERE id = '$codigoPed'";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarSCB(string $parametro, int $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."series WHERE id_almacen = $id_almacen AND serie LIKE '$parametro%'";
        $data = $this->select($sql);
        return $data;
    }

    public function getNuevosProductos()
    {
        $sql = "SELECT TOP 8 * FROM ".db_conect."productos ORDER BY id DESC";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getProductosFam($id_almacen, $id_familia, $linea, $id_tarticulo, $id_tarticulo_dos, $desde, $porPagina)
    {
        $sql = "EXEC ".db_conect."stock_general_bebidas @p_id_almacen = ?, @p_id_familia = ?, @p_linea = ?, @p_id_tarticulo = ?, @p_id_tarticulo2 = ?, @p_desde = ?, @p_porPagina = ?";
        $datos = array($id_almacen, $id_familia, $linea, $id_tarticulo, $id_tarticulo_dos, $desde, $porPagina);
        $data = $this->selectAll($sql, $datos);
        return $data;
    }

    public function bebidas_cortesia($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina)
    {
        $sql = "EXEC ".db_conect."stock_general @p_id_almacen = ?, @p_id_familia = ?, @p_linea = ?, @p_id_tarticulo = ?, @p_desde = ?, @p_porPagina = ?";
        $datos = array($id_almacen, $id_familia, $linea, $id_tarticulo, $desde, $porPagina);
        $data = $this->selectAll($sql, $datos);
        return $data;
    }

    public function TotalProductosFam($id_familia)
    {
        $sql = "SELECT COUNT(*) AS total FROM ".db_conect."productos WHERE id_familia = $id_familia";
        $data = $this->select($sql);
        return $data;
    }

    public function TotalProductosLinea($id_linea)
    {
        $sql = "SELECT COUNT(*) AS total FROM ".db_conect."productos WHERE linea = $id_linea";
        $data = $this->select($sql);
        return $data;
    }

    public function getPedidos()
    {
        $sql = "SELECT id_pedido, COUNT(*) as cantidad FROM ".db_conect."detalle_pedidos GROUP BY id_pedido";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getPedidosId($idPedido)
    {
        $sql = "SELECT * FROM ".db_conect."pedidos WHERE id = $idPedido";
        $data = $this->select($sql);
        return $data;
    }

    public function getRangoFechasExcelGen(string $desde, string $hasta)
    {
        $sql = "SELECT p.*, t.codigo_sunat FROM ".db_conect."pedidos p
        JOIN ".db_conect."tipo_documento t ON t.id = p.tipo_documento
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND tipo_pedido = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasExcelGenDet(string $desde, string $hasta)
    {
        $sql = "SELECT
        ROW_NUMBER() OVER (ORDER BY p.fecha) AS id,
        p.id AS id_pedido,
        p.fecha,
        t.codigo_sunat,
        p.serie,
        p.correlativo,
        p.dni,
        p.ruc,
        p.nombres,
        p.razon_social,
        pr.descripcion,
        dp.cantidad,
        dp.precio,
        pr.codigo,
        a.nombre AS almacen,
        dp.base,
        dp.igv,
        dp.total,
        p.propina
        FROM
            ".db_conect."pedidos p
        JOIN ".db_conect."tipo_documento t ON t.id = p.tipo_documento
        JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
        JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
        JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        WHERE
        p.fecha BETWEEN '$desde' AND '$hasta'
        AND p.estado = 0
        AND (dp.id_producto_asoc IS NULL);";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasExcelDet(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT
        ROW_NUMBER() OVER (ORDER BY p.fecha) AS id,
        p.id AS id_pedido,
        p.fecha,
        t.codigo_sunat,
        p.serie,
        p.correlativo,
        p.dni,
        p.ruc,
        p.nombres,
        p.razon_social,
        pr.descripcion,
        dp.cantidad,
        dp.precio,
        pr.codigo,
        a.nombre AS almacen,
        dp.base,
        dp.igv,
        dp.total
        FROM
            ".db_conect."pedidos p
        JOIN ".db_conect."tipo_documento t ON t.id = p.tipo_documento
        JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
        JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
        JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        WHERE
        p.fecha BETWEEN '$desde' AND '$hasta' AND p.id_usuario = $id_usuario
        AND p.estado = 0
        AND (dp.id_producto_asoc IS NULL);";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getRangoFechasExcel(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT p.*,t.codigo_sunat FROM ".db_conect."pedidos p
        JOIN ".db_conect."tipo_documento t ON t.id = p.tipo_documento
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProductoId($id, $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."v_stock_por_almacen WHERE id_producto = $id AND id_almacen = $id_almacen";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarDetalleTmp(int $id_producto, int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_ped_tmp WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function calcularVentaPed(int $id_usuario)
    {
        $sql = "SELECT SUM(sub_total) AS total FROM ".db_conect."detalle_ped_tmp WHERE id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function deleteDetallePed($id)
    {
        $sql = "EXEC ".db_conect."deleteDetallePed @p_id = ?";
        $datos = array($id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function actualizarDetalleTmp(float $precio, int $cantidad, int $tipo_pedido, float $sub_total, int $id_producto, int $id_usuario)
    {
        $sql = "EXEC ".db_conect."actualizarDetalleTmp @p_precio = ?, @p_cantidad = ?, @p_tipo_pedido = ?, @p_sub_total = ?, @p_id_producto = ?, @p_id_usuario = ?";
        $datos = array($precio, $cantidad, $tipo_pedido, $sub_total, $id_producto, $id_usuario);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarDetalleTmp(int $id_producto, int $id_usuario, float $precio, int $cantidad, int $tipo_pedido, float $sub_total)
    {
        $sql = "EXEC ".db_conect."registrarDetalleTmp @p_id_producto = ?, @p_id_usuario = ?, @p_precio = ?, @p_cantidad = ?, @p_tipo_pedido = ?, @p_sub_total = ?";
        $datos = array($id_producto, $id_usuario, $precio, $cantidad, $tipo_pedido, $sub_total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function actualizarStock(int $cantidad, int $id_producto, int $id_almacen){
        $sql = "EXEC ".db_conect."actualizarStock @p_cantidad = ?, @p_id_producto = ?, @p_id_almacen = ?";
        $datos = array($cantidad, $id_producto);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function verPedido($idPedido)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $idPedido";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarPedido($idPedido)
    {
        $sql = "SELECT * FROM ".db_conect."pedidos WHERE id = $idPedido";
        $data = $this->select($sql);
        return $data;
    }

    public function cancelarPedido($estado, $idPedido)
    {
        $sql = "UPDATE ".db_conect."pedidos SET estado = ? WHERE id = ?";
        $datos = array($estado, $idPedido);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "eliminado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function mostrarStockProducto($idProducto)
    {
        $sql = "SELECT * FROM ".db_conect."v_stock_por_almacen WHERE id_producto = $idProducto";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getId()
    {
        $sql = "SELECT MAX(id) AS id_pedido FROM ".db_conect."pedidos";
        $data = $this->select($sql);
        return $data;
    }

    public function ObtenerPedidos($estado, $id_usuario, $desde, $hasta)
    {
        $sql = "SELECT p.*, u.nombre, tp.nombre AS nombre_tipo_pedido, a.nombre AS almacen
        FROM ".db_conect."pedidos p
        INNER JOIN ".db_conect."usuarios u ON p.id_usuario = u.id
        LEFT JOIN ".db_conect."tipo_pedido tp ON tp.id = p.tipo_pedido
        LEFT JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        WHERE p.estado = $estado AND p.tipo_pedido != 0 AND p.id_usuario = $id_usuario AND p.fecha BETWEEN '$desde' AND '$hasta'";

        $data = $this->selectall($sql);
        return $data;
    }

    public function ObtenerPedidosGen($estado, $desde, $hasta)
    {
        $sql = "SELECT p.*, u.nombre,td.nombre AS tipo_doc , a.nombre AS almacen
        FROM ".db_conect."pedidos p
        INNER JOIN ".db_conect."usuarios u ON p.id_usuario = u.id
        LEFT JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        JOIN ".db_conect."tipo_documento td ON td.id = p.tipo_documento
        WHERE p.estado = $estado AND p.tipo_pedido != 0 AND p.fecha BETWEEN '$desde' AND '$hasta'";

        $data = $this->selectall($sql);
        return $data;
    }

    public function ObtenerPedidosAdmin($estado, $desde, $hasta)
    {
        $sql = "SELECT p.*, u.nombre, tp.nombre AS nombre_tipo_pedido, a.nombre AS almacen
        FROM ".db_conect."pedidos p
        INNER JOIN ".db_conect."usuarios u ON p.id_usuario = u.id
        LEFT JOIN ".db_conect."tipo_pedido tp ON tp.id = p.tipo_pedido
        LEFT JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        WHERE p.estado = $estado AND p.fecha BETWEEN '$desde' AND '$hasta'";

        $data = $this->selectall($sql);
        return $data;
    }

    public function editarPedidoFact($id)
    {
        $sql = "SELECT * FROM ".db_conect."pedidos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getListaPedidos($id_producto)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data;
    }

    public function getListaCarrito($id_producto)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id_producto";
        $data = $this->select($sql);
        return $data;
    }

    public function vaciarDetalle(int $id_usuario)
    {
        $sql = "DELETE FROM ".db_conect."detalle_ped_tmp WHERE id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function getListaTotalPedidos($id_pedido)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_pedidos WHERE id = $id_pedido";
        $data = $this->select($sql);
        return $data;
    }

    public function eliminarPermisos(int $id_usuario)
    {
        $sql = "DELETE FROM ".db_conect."detalle_permisos WHERE id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function eliminarPedido(int $id_pedido)
    {
        $sql = "EXEC ".db_conect."eliminar_pedido @pedido_id = ?";
        $datos = array($id_pedido);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function obtenerResumenVentasGen(string $desde, string $hasta)
    {
        $sql = "SELECT tp.nombre, COUNT(*) AS cantidad_pedidos, SUM(p.total) AS total_por_pedido
        FROM ".db_conect."pedidos p
        JOIN ".db_conect."tipo_pedido tp ON tp.id = p.tipo_pedido
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0
        GROUP BY tp.nombre";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function obtenerResumenVentas(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT tp.nombre, COUNT(*) AS cantidad_pedidos, SUM(p.total) AS total_por_pedido
        FROM ".db_conect."pedidos p
        JOIN ".db_conect."tipo_pedido tp ON tp.id = p.tipo_pedido
        WHERE p.fecha BETWEEN '$desde' AND '$hasta' AND id_usuario = $id_usuario
        GROUP BY tp.nombre";
        $data = $this->selectAll($sql);
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

    public function registrarPedidoTipo(int $id_usuario, int $id_almacen_ini, ?string $id_almacen_fin = null, float $base, float $igv, float $total, string $fecha_actual, int $estado, string $glosa, string $autorizado, ?string $fecha_desc = NULL, int $trab_desc, int $tipo_pedido)
    {
        $sql = "EXEC ".db_conect."registrarPedidoTipo @p_id_usuario = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_base = ?, @p_igv = ?, @p_total = ?, @p_fecha_actual = ?, @p_estado = ?, @p_glosa = ?, @p_autorizado = ?, @p_fecha_desc = ?, @p_trab_desc = ?, @p_tipo_pedido = ?";
        $datos = array($id_usuario, $id_almacen_ini, $id_almacen_fin, $base, $igv, $total, $fecha_actual, $estado, $glosa, $autorizado, $fecha_desc, $trab_desc, $tipo_pedido);
        try {
            $data = $this->save_reg($sql, $datos);
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function registrarPedidoT2(int $id_usuario, int $id_almacen_ini, ?string $id_almacen_fin = null, float $base, float $igv, float $total, string $fecha_actual, int $estado, string $glosa, string $autorizado, int $tipo_pedido, ?int $consumo_artista = null)
    {
        $sql = "EXEC ".db_conect."registrarPedidoT2 @p_id_usuario = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_base = ?, @p_igv = ?, @p_total = ?, @p_fecha_actual = ?, @p_estado = ?, @p_glosa = ?, @p_autorizado = ?, @p_tipo_pedido = ?, @p_consumo_artista = ?";
        $datos = array($id_usuario, $id_almacen_ini, $id_almacen_fin, $base, $igv, $total, $fecha_actual, $estado, $glosa, $autorizado, $tipo_pedido, $consumo_artista);
        try {
            $data = $this->save_reg($sql, $datos);
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function consultarCliente(int $doc, int $dato)
    {
        if($doc == 1){
            $sql = "SELECT * FROM ".db_conect."contactos WHERE dni = $dato";
            $data = $this->select($sql);
            return $data;
        }else{
            $sql = "SELECT * FROM ".db_conect."contactos WHERE ruc = $dato";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function getDetalle(int $id)
    {
        $sql = "SELECT d.*, p.id AS id_pro, p.descripcion FROM ".db_conect."detalle_ped_tmp d
        INNER JOIN ".db_conect."productos p ON d.id_producto = p.id
        WHERE d.id_usuario = $id";
        $data = $this->selectAll($sql);
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

    public function generarTokenPedido($id_usuario, $solicitante, $token_pedido, $id_tipo_token, $cantidad, $fecha_actual, $fecha_caduca)
    {
        $sql = "INSERT INTO ".db_conect."token_pedido (id_usuario, solicitante, token_pedido, id_tipo_token, cantidad, fecha, fecha_caduca) VALUES (?,?,?,?,?,?,?)";
        $datos = array($id_usuario, $solicitante, $token_pedido, $id_tipo_token, $cantidad, $fecha_actual, $fecha_caduca);
        $data = $this->save($sql, $datos);
        if($data > 0){
            $res = $data;
        }else{
            $res = 0;
        }
        return $res;
    }

    public function verificarTokenPedido($op_cort)
    {
        $sql = "SELECT * FROM ".db_conect."token_pedido  WHERE token_pedido = '$op_cort' AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarTokenPedido($op_cort)
    {
        $sql = "UPDATE ".db_conect."token_pedido SET estado = ? WHERE token_pedido = ?";
        $datos = array(0, $op_cort);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function getEmpresa()
    {
        $sql = "SELECT * FROM ".db_conect."configuracion";
        $data = $this->select($sql);
        return $data;
    }

    public function getUsuario_Compra($id)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getPedido($id_pedido/*$serie, $correlativo*/)
    {
        /*$sql = "SELECT p.id, p.tipo_documento, p.serie, p.correlativo, p.efectivo, p.id_usuario, p.id_almacen_ini, p.dni, p.ruc, p.nombres, 
        p.apellido_paterno, p.apellido_materno, p.razon_social, p.direccion, p.fecha, p.base, p.igv, p.total, a.nombre AS nombre_almacen
        FROM pedidos p JOIN almacen a ON a.id = p.id_almacen_ini WHERE p.serie = '$serie' AND p.correlativo = $correlativo";*/
        $sql = "SELECT p.id, p.propina, p.tipo_pedido, tp.nombre AS nombre_pedido, p.tipo_documento, p.serie, p.correlativo, p.efectivo, p.visa, p.master_c, p.diners, p.a_express, p.yape, p.plin, p.izipay, p.niubiz, p.pos, p.transferencia, p.id_usuario, p.id_almacen_ini, p.dni, p.ruc, p.nombres, 
        p.apellido_paterno, p.apellido_materno, p.razon_social, p.direccion, p.fecha, p.base, p.igv, p.total, a.nombre AS nombre_almacen
        FROM ".db_conect."pedidos p
        JOIN ".db_conect."almacen a ON a.id = p.id_almacen_ini
        JOIN ".db_conect."tipo_pedido tp ON tp.id = p.tipo_pedido
        WHERE p.id = $id_pedido";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getDetallePedido($id_pedido)
    {
        $sql = "SELECT dp.*, p.descripcion AS nombre_producto, p.codigo FROM ".db_conect."detalle_pedidos dp
        JOIN ".db_conect."productos p ON p.id = dp.id_producto
        WHERE dp.id_pedido = $id_pedido AND dp.id_producto_asoc IS NULL";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermisoTerm(int $id_usuario, string $permiso)
    {
        $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM ".db_conect."permisos p
        INNER JOIN ".db_conect."detalle_permisos d ON p.id = d.id_permiso
        WHERE d.id_usuario = $id_usuario AND p.permiso = '$permiso'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function totalVentasGen(int $valor, string $desde, string $hasta)
    {
        if($valor == 1){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND al.nombre LIKE 'BEBIDAS%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 2){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND al.nombre LIKE 'COCTELERIA%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 3){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND al.nombre LIKE 'COCINA%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 4){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p 
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND al.nombre LIKE 'BOLETERIA%'";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function totalVentas(int $valor, string $desde, string $hasta, int $id_usuario)
    {
        if($valor == 1){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz,
                SUM(p.propina) AS propina
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario AND al.nombre LIKE 'BEBIDAS%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 2){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario AND al.nombre LIKE 'COCTELERIA%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 3){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario AND al.nombre LIKE 'COCINA%'";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 4){
            $sql = "SELECT SUM(p.total) AS total_ventas,
                SUM(p.efectivo) AS efectivo,
                SUM(p.visa) AS visa,
                SUM(p.master_c) AS master_c,
                SUM(p.diners) AS diners,
                SUM(p.a_express) AS a_express,
                SUM(p.yape) AS yape,
                SUM(p.plin) AS plin,
                SUM(p.izipay) AS izipay,
                SUM(p.niubiz) AS niubiz
            FROM ".db_conect."pedidos p 
            JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
            WHERE fecha BETWEEN '$desde' AND '$hasta' AND p.estado = 0 AND p.id_usuario = $id_usuario AND al.nombre LIKE 'BOLETERIA%'";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function totalNoVentasGen(int $valor, string $desde, string $hasta){
        if($valor == 1){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND tipo_pedido = 2";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 2){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND tipo_pedido = 3";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 3){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND tipo_pedido = 4";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function totalNoVentas(int $valor, string $desde, string $hasta, int $id_usuario){
        if($valor == 1){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND id_usuario = $id_usuario AND tipo_pedido = 2";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 2){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND id_usuario = $id_usuario AND tipo_pedido = 3";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 3){
            $sql = "SELECT SUM(total) AS total_ventas FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND id_usuario = $id_usuario AND tipo_pedido = 4";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function getTotalPropinasGeneral(string $desde, string $hasta)
    {
        $sql = "SELECT SUM(propina) as propina FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0";
        $data = $this->select($sql);
        return $data;
    }

    public function getTotalPropinas(string $desde, string $hasta, int $id_usuario)
    {
        $sql = "SELECT SUM(propina) as propina FROM ".db_conect."pedidos WHERE fecha BETWEEN '$desde' AND '$hasta' AND estado = 0 AND id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function getPedidosResumenGeneral(int $valor, string $cod, string $desde, string $hasta)
    {
        if($valor == 1){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0 AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }else if($valor !=4){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0  AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }else if($valor == 4){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            u_trab_desc.nombre AS desc_trab,
            u_autorizado.nombre AS autorizador,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                JOIN ".db_conect."usuarios u_trab_desc ON u_trab_desc.id = p.trab_desc
                JOIN ".db_conect."usuarios u_autorizado ON u_autorizado.id = p.autorizado
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0 AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY p.trab_desc, p.autorizado, pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }
    }

    public function getPedidosResumen(int $valor, string $cod, string $desde, string $hasta, int $id_usuario)
    {
        if($valor == 1){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0 AND p.id_usuario = $id_usuario AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }else if($valor !=4){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0 AND p.id_usuario = $id_usuario AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }else if($valor == 4){
            $sql = "SELECT pr.descripcion AS nombre_producto,
            u_trab_desc.nombre AS desc_trab,
            u_autorizado.nombre AS autorizador,
            COUNT(pr.descripcion) AS cantidad,
            dp.precio,
            SUM(dp.cantidad) as cantidad_ven,
			SUM(dp.total) AS suma_totales
                FROM ".db_conect."pedidos p
                JOIN ".db_conect."detalle_pedidos dp ON dp.id_pedido = p.id
                JOIN ".db_conect."productos pr ON pr.id = dp.id_producto
                JOIN ".db_conect."almacen al ON al.id = p.id_almacen_ini
                JOIN ".db_conect."usuarios u_trab_desc ON u_trab_desc.id = p.trab_desc
                JOIN ".db_conect."usuarios u_autorizado ON u_autorizado.id = p.autorizado
                WHERE dp.id_producto_asoc IS NULL AND p.tipo_pedido = $valor AND p.estado = 0 AND p.id_usuario = $id_usuario AND p.fecha BETWEEN '$desde' AND '$hasta' AND al.nombre LIKE '$cod%'
                GROUP BY p.trab_desc, p.autorizado, pr.descripcion, dp.precio";
            $data = $this->selectAll($sql);
            return $data;
        }
    }

    public function getBusquedaProdPantalla(string $valor, string $id_almacen, string $numero, string $linea)
    {
        if ($numero == 1) {
            $sql = "SELECT TOP 10 id_producto, foto, precio_venta, descripcion, almacen, stock 
                    FROM ".db_conect."v_stock_por_almacen
                    WHERE id_almacen = :id_almacen 
                        AND afecta_venta = 1 
                        AND estado_producto = 1
                        AND linea = :linea
                        AND CONCAT(' ', descripcion, ' ') LIKE :valor";
        
            $stmt = $this->pdo->prepare($sql);
            $valor = "%$valor%";
            $stmt->bindParam(':id_almacen', $id_almacen, PDO::PARAM_INT);
            $stmt->bindParam(':linea', $linea, PDO::PARAM_INT);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else if ($numero == 2) {
            $sql = "SELECT TOP 5
                        id_familia, 
                        id AS id_producto, 
                        foto, 
                        precio_venta, 
                        descripcion, 
                        :id_almacen AS id_almacen, 
                        'VARIOS' AS almacen, 
                        1.00 AS stock 
                    FROM ".db_conect."productos 
                    WHERE 
                        id_tarticulo = 3 AND 
                        estado = 1 AND 
                        afecta_venta = 1 AND 
                        linea = :linea AND 
                        CONCAT(' ', descripcion, ' ') LIKE :valor
                    UNION ALL
                    SELECT 
                        v.id_familia, 
                        v.id_producto, 
                        v.foto, 
                        v.precio_venta, 
                        v.descripcion, 
                        v.id_almacen, 
                        v.almacen, 
                        v.stock
                    FROM 
                        ".db_conect."v_stock_por_almacen v 
                        JOIN ".db_conect."productos p ON v.id_producto = p.id 
                    WHERE 
                        v.id_almacen = :id_almacen AND 
                        p.linea = :linea AND 
                        v.afecta_venta = 1 AND 
                        v.estado_producto = 1 AND 
                        CONCAT(' ', p.descripcion, ' ') LIKE :valor";
        
            $stmt = $this->pdo->prepare($sql);
            $valor = "%$valor%";
            $stmt->bindParam(':id_almacen', $id_almacen, PDO::PARAM_INT);
            $stmt->bindParam(':linea', $linea, PDO::PARAM_INT);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } else if ($numero == 3) {
            $sql = "SELECT TOP 10
                        id_familia, 
                        id AS id_producto, 
                        foto, 
                        precio_venta, 
                        descripcion, 
                        :id_almacen AS id_almacen, 
                        'VARIOS' AS almacen, 
                        1.00 AS stock
                    FROM ".db_conect."productos 
                    WHERE 
                        estado = 1 AND 
                        afecta_venta = 1 AND 
                        linea = :linea AND 
                        CONCAT(' ', descripcion, ' ') LIKE :valor
                    UNION ALL
                    SELECT 
                        v.id_familia, 
                        v.id_producto, 
                        v.foto, 
                        v.precio_venta, 
                        v.descripcion, 
                        v.id_almacen, 
                        v.almacen, 
                        v.stock
                    FROM 
                        ".db_conect."v_stock_por_almacen v 
                        JOIN ".db_conect."productos p ON v.id_producto = p.id 
                    WHERE 
                        v.id_almacen = :id_almacen AND
                        p.linea = :linea AND
                        v.afecta_venta = 1 AND
                        v.estado_producto = 1 AND
                        CONCAT(' ', p.descripcion, ' ') LIKE :valor";
        
            $stmt = $this->pdo->prepare($sql);
            $valor = "%$valor%";
            $stmt->bindParam(':id_almacen', $id_almacen, PDO::PARAM_INT);
            $stmt->bindParam(':linea', $linea, PDO::PARAM_INT);
            $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
    }

    public function buscarRutaRPTA(int $parametro)
    {
        if($parametro == 1){
            $sql = "SELECT ruta_rpta FROM ".db_conect."parametr";
            $data = $this->select($sql);
            return $data;
        }else if($parametro == 2){
            $sql = "SELECT ruta_firma FROM ".db_conect."parametr";
            $data = $this->select($sql);
            return $data;
        }
    }
    public function buscarPedidoSunat(int $id_pedido)
    {
        $sql = "SELECT * FROM ".db_conect."pedidos WHERE Fcfmanumero = $id_pedido";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarCodigoVnd(int $id_usuario)
    {
        $sql = "SELECT codigo_vendedor FROM ".db_conect."usuarios WHERE id = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarBanco(int $id_banco)
    {
        $sql = "SELECT id FROM ".db_conect."bancos WHERE id_almacen = $id_banco";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarIngresoCaja(int $id_usuario, int $id_almacen, int $id_banco_ini, ?int $id_banco_fin= NULL, ?string $numero_operacion = NULL, ?int $documento = NULL, ?string $nombre = NULL, ?int $tipo_doc = NULL, ?string $serie = NULL, ?string $numero = NULL, float $monto_ingreso, float $monto_total, string $glosa, $b_200, $b_100, $b_50, $b_20, $b_10, $m_5, $m_2, $m_1, $m_050, $m_020, $m_010, string $fecha, int $tipo_operacion_banco)
    {
        $sql = "EXEC ".db_conect."registrarIngresoBancos @p_id_usuario = ?, @p_id_almacen = ?, @p_id_banco_ini = ?, @p_id_banco_fin = ?, @p_numero_operacion = ?, @p_documento = ?, @p_nombre = ?, @p_tipo_doc = ?, @p_serie = ?, @p_numero = ?, @p_monto_ingreso = ?, @p_monto_total = ?, @p_glosa = ?, @p_b_200 = ?, @p_b_100 = ?, @p_b_50 = ?, @p_b_20 = ?, @p_b_10 = ?, @p_m_5 = ?, @p_m_2 = ?, @p_m_1 = ?, @p_m_050 = ?, @p_m_020 = ?, @p_m_010 = ?, @p_fecha = ?, @p_tipo_operacion_banco = ?";
        $datos = array(
            $id_usuario, $id_almacen, $id_banco_ini, $id_banco_fin, $numero_operacion, $documento, $nombre, $tipo_doc, $serie, $numero, $monto_ingreso, $monto_total, $glosa,
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
            $fecha, $tipo_operacion_banco
        );
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "EXEC registrarIngresoBancos(id_usuario=$id_usuario, id_almacen=$id_almacen, id_banco_ini=$id_banco_ini, id_banco_fin=$id_banco_fin, numero_operacion=$numero_operacion, documento=$documento, nombre=$nombre, tipo_doc=$tipo_doc, serie=$serie, numero=$numero, monto_ingreso=$monto_ingreso, monto_total=$monto_total, glosa=$glosa, b_200=$b_200, b_100=$b_100, b_50=$b_50, b_20=$b_20, b_10=$b_10, m_5=$m_5, m_2=$m_2, m_1=$m_1, m_050=$m_050, m_020=$m_020, m_010=$m_010, fecha=$fecha, tipo_operacion_banco=$tipo_operacion_banco)";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }
}
?>