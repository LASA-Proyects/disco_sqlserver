<?php
class ProductosModel extends Query{
    private $codigo, $descripcion, $precio_compra, $precio_venta, $id_familia, $data, $id, $img;
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios($id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function getUsuariosAlmc($id_usuario)
    {
        $sql = "SELECT a.id, a.nombre FROM ".db_conect."detalle_permisos_almc da
        INNER JOIN ".db_conect."almacen a ON a.id = da.id_almacen
        WHERE da.id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }
    
    public function getProductos()
    {
        $sql = "SELECT p.*, f.nombre AS familia FROM ".db_conect."productos p 
        JOIN ".db_conect."familia f ON p.id_familia = f.id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getFamilias()
    {
        $sql = "SELECT * FROM ".db_conect."familia WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getLineas()
    {
        $sql = "SELECT * FROM ".db_conect."linea_productos WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProducRec($id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getProductosStock($fecha,$producto,$familia,$id_almacen)
    {
        $sql = "EXEC ".db_conect."Sp_vista_stock_por_almacen @p_hasta = ?, @p_idproducto = ?, @p_idfamilia = ?, @p_idalmacen = ?";
        $datos = array($fecha, $producto, $familia, $id_almacen);
        $data = $this->selectAll($sql, $datos);
        return $data;
    }

    public function getFamilia()
    {
        $sql = "SELECT * FROM ".db_conect."familia";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTArticulos()
    {
        $sql = "SELECT * FROM ".db_conect."t_articulo";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarProductoReceta(int $id_producto)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_receta WHERE id_producto = $id_producto";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarProducto(string $codigo, string $descripcion, int $afecta_compra, int $afecta_venta, int $afecta_igv, int $afecta_iss, int $stock_minimo, string $linea, string $ubicacion, string $origen, float $precio_compra, float $precio_venta, int $id_familia, int $id_tarticulo, string $img, ?string $unidad = NULL, ?float $cantidad = NULL, string $fecha_actual)
    {
        $sql = "EXEC ".db_conect."registrarProducto @p_codigo = ?, @p_descripcion = ?, @p_afecta_compra = ?, @p_afecta_venta = ?, @p_afecta_igv = ?, @p_afecta_iss = ?, @p_stock_minimo = ?, @p_linea = ?, @p_ubicacion = ?, @p_origen = ?, @p_precio_compra = ?, @p_precio_venta = ?, @p_id_familia = ?, @p_id_tarticulo = ?, @p_img = ?, @p_unidad_med = ?, @p_cantidad_med = ?, @p_fecha_actual = ?";
        $datos = array($codigo, $descripcion, $afecta_compra, $afecta_venta, $afecta_igv, $afecta_iss, $stock_minimo, $linea, $ubicacion, $origen, $precio_compra, $precio_venta, $id_familia, $id_tarticulo, $img, $unidad, $cantidad, $fecha_actual);
        $data = $this->save_exist($sql, $datos);
        if($data != 0){
            $res = "ok";
        }else{
            $res = "existe";
        }
        return $res;
    }

    public function modificarProducto(string $codigo, string $descripcion, int $afecta_compra, int $afecta_venta, int $afecta_igv, int $afecta_iss, int $stock_minimo, string $linea, string $ubicacion, string $origen, float $precio_compra, float $precio_venta, int $id_familia, int $id_tarticulo, string $img, ?string $unidad = NULL, ?float $cantidad = NULL, string $fecha_actual, int $id)
    {
        $sql = "EXEC ".db_conect."modificarProducto @p_codigo = ?, @p_descripcion = ?, @p_afecta_compra = ?, @p_afecta_venta = ?, @p_afecta_igv = ?, @p_afecta_iss = ?, @p_stock_minimo = ?, @p_linea = ?, @p_ubicacion = ?, @p_origen = ?, @p_precio_compra = ?, @p_precio_venta = ?, @p_id_familia = ?, @p_id_tarticulo = ?, @p_img = ?, @p_unidad_med = ?, @p_cantidad_med = ?, @p_fecha_actual = ?, @p_id = ?";
        $datos = array($codigo, $descripcion, $afecta_compra, $afecta_venta, $afecta_igv, $afecta_iss, $stock_minimo, $linea, $ubicacion, $origen, $precio_compra, $precio_venta, $id_familia, $id_tarticulo, $img, $unidad, $cantidad, $fecha_actual, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function editarProduct(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getAlmacenes()
    {
        $sql = "SELECT * FROM ".db_conect."almacen WHERE estado = 1";
        $data = $this->selectall($sql);
        return $data;
    }

    public function eliminarProduct(int $id)
    {
        $sql = "EXEC ".db_conect."eliminarProduct @p_id = ?";
        $datos = array($id);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res = "eliminado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function accionProducto(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE ".db_conect."productos SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre, int $permiso_hijo)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_permiso_hijo = $permiso_hijo AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getVistaRegulador()
    {
        $sql = "SELECT * FROM ".db_conect."vista_regulador_pedido";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function getVistaReguladorTmp()
    {
        $sql = "SELECT 
        rt.id,
        rt.id_detalle_pedido, 
        rt.id_pedido, 
        rt.id_usuario, 
        rt.id_producto, 
        rt.id_almacen, 
        COALESCE(dr.total, 0) AS precio, 
        COALESCE((dr.cantidad*cantidad_combo), 0) AS cantidad, 
        COALESCE((dr.total*(dr.cantidad*cantidad_combo)), 0) AS base, 
        0.00 AS igv, 
        COALESCE((dr.total*(dr.cantidad*cantidad_combo)), 0) AS total, 
        rt.id_producto_asoc,
        rt.diferencia
            FROM 
                ".db_conect."regulador_tmp rt 
            LEFT JOIN 
                ".db_conect."detalle_receta dr 
            ON 
                rt.id_producto_asoc = dr.id_receta AND rt.id_producto = dr.id_producto";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function regulador_tmp(int $id_detalle_pedido, int $id_pedido, int $id_usuario, int $id_producto, int $id_almacen, float $cantidad_combo, int $id_producto_asoc, float $diferencia)
    {
        $sql = "INSERT INTO ".db_conect."regulador_tmp(id_detalle_pedido, id_pedido, id_usuario, id_producto, id_almacen, cantidad_combo, id_producto_asoc, diferencia) VALUES (?,?,?,?,?,?,?,?)";
        $datos = array($id_detalle_pedido, $id_pedido, $id_usuario, $id_producto, $id_almacen, $cantidad_combo, $id_producto_asoc, $diferencia);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function buscarReceta(int $id_producto, int $id_producto_asoc)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_receta WHERE id_receta = 1";
        $data = $this->selectAll($sql);
        return $data; 
    }

    public function nuevoDato(int $id_pedido, int $id_usuario, int $id_producto, int $id_almacen, float $precio, float $cantidad, float $base, float $igv, float $total, int $id_producto_asoc)
    {
        $sql = "INSERT INTO ".db_conect."detalle_pedidos(id_pedido, id_usuario, id_producto, id_almacen, precio, cantidad, base, igv, total, id_producto_asoc) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $datos = array($id_pedido, $id_usuario, $id_producto, $id_almacen,  $precio, $cantidad,  $base,  $igv,  $total, $id_producto_asoc);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function eliminarDatos(int $id_detalle_pedido, int $id_pedido)
    {
        $sql = "DELETE FROM ".db_conect."detalle_pedidos WHERE id = ? AND id_pedido = ?";
        $datos = array($id_detalle_pedido, $id_pedido);
        $data = $this->save($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
        return $res;
    }

    public function insertarTomaInv(int $id_usuario, string $fecha_descarga)
    {
        $sql = "EXEC ".db_conect."InsertarTomaInventario @p_id_usuario = ?, @p_fecha_descarga = ?";
        $datos = array($id_usuario, $fecha_descarga);
        try {
            $data['id'] = $this->save_reg($sql, $datos);
            $data['sql'] = "CALL InsertarTomaInventario('$id_usuario', '$fecha_descarga')";
            return $data;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function insertarTomaInvDetalle(int $id_toma_inv, int $id_producto, int $id_almacen, float $stock)
    {
        $sql = "INSERT INTO ".db_conect."toma_inv_detalle(id_toma_inv, id_producto, id_almacen, stock_actual_sys) VALUES (?,?,?,?)";
        $datos = array($id_toma_inv, $id_producto, $id_almacen, $stock);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function getTomaInv()
    {
        $sql = "SELECT ti.*, u.nombre AS nombre_usuario FROM ".db_conect."toma_inventario ti
        JOIN ".db_conect."usuarios u On u.id = ti.id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarStockFisico(float $stock_actual_fisico, int $id_toma, int $id_producto)
    {
        $sql = "UPDATE ".db_conect."toma_inv_detalle SET stock_fisico = ? WHERE id_toma_inv = ? AND id_producto = ?";
        $datos = array($stock_actual_fisico, $id_toma, $id_producto);
        try {
            $data = $this->save_trans($sql, $datos);
            $res['res'] = "modificado";
            $res['sql'] = "UPDATE ".db_conect."toma_inv_detalle SET stock_fisico = $stock_actual_fisico WHERE id_toma_inv = $id_toma AND id_producto = $id_producto;";
            return $res;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function actualizarIdOp(int $id_op, int $id_toma, int $id_producto)
    {
        $sql = "UPDATE ".db_conect."toma_inv_detalle SET id_op = ? WHERE id_toma_inv = ? AND id_producto = ?";
        $datos = array($id_op, $id_toma, $id_producto);
        $data = $this->save($sql, $datos);
    }

    public function actualizarEstadoTomaInv(int $tipo_fecha, string $fecha_subida, int $estado, int $id)
    {
        if($tipo_fecha == 1){
            $sql = "UPDATE ".db_conect."toma_inventario SET fecha_subida = ?, estado = ? WHERE id = ?";
            $datos = array($fecha_subida, $estado, $id);
            $data = $this->save($sql, $datos);
        }else if($tipo_fecha == 2){
            $sql = "UPDATE ".db_conect."toma_inventario SET fecha_proceso = ?, estado = ? WHERE id = ?";
            $datos = array($fecha_subida, $estado, $id);
            $data = $this->save($sql, $datos);
        }
    }

    public function obtenerDatosTomaInv(int $id)
    {
        $sql = "SELECT * , (stock_fisico-stock_actual_sys) AS resta FROM ".db_conect."toma_inv_detalle WHERE id_toma_inv = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function reAjusteIngresos(int $id_usuario, ?int $id_venta = NULL, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?int $id_almacen_fin = NULL, string $serie, int $correlativo, float $total, string $fecha_actual)
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

    public function reAjusteIngresosDetalle(int $id_compra, ?int $id_venta = NULL, int $id_producto, int $id_almacen, float $cantidad, float $precio, string $sub_total)
    {
        $sql = "EXEC ".db_conect."registrarDetalleCompra @p_id_compra = ?, @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?";
        $datos = array($id_compra, $id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleCompra('$id_compra', '$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function reAjusteSalidas(int $id_usuario, int $id_proveedor, int $tipo_operacion, ?string $fecha_ingreso = NULL, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, float $total, string $fecha_actual)
    {
        $sql = "EXEC ".db_conect."registrarVenta @p_id_usuario = ?, @p_id_proveedor = ?, @p_id_tipo_operacion = ?, @p_fecha_ingreso = ?, @p_tipo_cambio = ?, @p_tipo_documento = ?, @p_id_almacen_ini = ?, @p_id_almacen_fin = ?, @p_serie = ?, @p_correlativo = ?, @p_total = ?, @p_fecha_actual = ?";
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

    public function reAjusteSalidasDetalle(int $id_venta, int $id_producto, int $id_almacen, float $cantidad, float $precio, string $sub_total)
    {
        $sql = "EXEC ".db_conect."registrarDetalleVenta @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?, @p_observacion = ?";
        $datos = array($id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleVenta('$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function verEstadoTomaInv(int $id)
    {
        $sql = "SELECT tid.*, 
        (tid.stock_fisico - tid.stock_actual_sys) AS resta, 
        p.descripcion,
        p.unidad_med,
        a.nombre AS almacen_nombre,
        COALESCE(
            CASE
                WHEN (tid.stock_fisico - tid.stock_actual_sys) > 0 THEN c.serie
                WHEN (tid.stock_fisico - tid.stock_actual_sys) < 0 THEN v.serie
                ELSE NULL
            END, '-') AS serie,
        COALESCE(
            CASE
                WHEN (tid.stock_fisico - tid.stock_actual_sys) > 0 THEN c.correlativo
                WHEN (tid.stock_fisico - tid.stock_actual_sys) < 0 THEN v.correlativo
                ELSE NULL
            END, '-') AS correlativo,
        COALESCE(
            CASE
                WHEN (tid.stock_fisico - tid.stock_actual_sys) > 0 THEN 'INGRESO'
                WHEN (tid.stock_fisico - tid.stock_actual_sys) < 0 THEN 'SALIDA'
                ELSE NULL
            END, '-') AS tabla
        FROM ".db_conect."toma_inv_detalle tid
        JOIN ".db_conect."productos p ON tid.id_producto = p.id
        JOIN ".db_conect."almacen a ON tid.id_almacen = a.id 
        LEFT JOIN ".db_conect."compra c ON tid.id_op = c.id AND (tid.stock_fisico - tid.stock_actual_sys) > 0
        LEFT JOIN ".db_conect."venta v ON tid.id_op = v.id AND (tid.stock_fisico - tid.stock_actual_sys) < 0
        WHERE tid.id_toma_inv = $id ORDER BY tid.id_producto";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function ImpTomaInvExcelExport(int $id)
    {
        $sql = "SELECT tid.*, a.nombre AS nombre_almacen, p.descripcion, p.unidad_med FROM ".db_conect."toma_inv_detalle tid
        JOIN ".db_conect."almacen a ON a.id = tid.id_almacen
        JOIN ".db_conect."productos p On p.id = tid.id_producto
        WHERE tid.id_toma_inv = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarReAjusteIngresos(string $table, int $correlativo ,int $id)
    {
        $sql = "UPDATE ".db_conect."$table SET correlativo = ? WHERE id = ?";
        $datos = array($correlativo, $id);
        $data = $this->save($sql, $datos);
    }

    public function anularTomaInv(int $estado, $id)
    {
        $sql = "UPDATE ".db_conect."toma_inventario SET estado = ? WHERE id = ?";
        $datos = array($estado, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "eliminado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarMovArt(int $id)
    {
        $sql = "SELECT TOP 1 * FROM ".db_conect."v_invdetal WHERE id_producto = $id AND id_familia != 9";
        $data = $this->select($sql);
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

    public function buscarActualData(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."toma_inv_detalle WHERE id = $id";
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

    public function verificarStockActual($id_usuario, $id_almacen)
    {
        $sql = "SELECT * FROM  ".db_conect."verstockkardex 
            WHERE CAST(fecha AS DATE) = CAST(GETDATE() AS DATE) 
            AND hora < '23:59:59' 
            AND id_usuario = $id_usuario 
            AND id_almacen = $id_almacen";
        $data = $this->select($sql);
        return $data;
    }

    public function verificarStock($id_usuario, $id_almacen, $fecha, $hora, $estado)
    {
        $sql = "INSERT INTO ".db_conect."verstockkardex(id_usuario, id_almacen, fecha, hora, estado_stock) VALUES (?,?,?,?,?)";
        $datos = array($id_usuario, $id_almacen, $fecha, $hora, $estado);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarKardex($id_usuario, $id_almacen, $fecha, $hora, $estado)
    {
        $sql = "INSERT INTO ".db_conect."verstockkardex(id_usuario, id_almacen, fecha, hora, estado_kardex) VALUES (?,?,?,?,?)";
        $datos = array($id_usuario, $id_almacen, $fecha, $hora, $estado);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function modificarStock($estado, $id_usuario, $id_almacen)
    {
        $sql = "UPDATE ".db_conect."verstockkardex SET estado_stock = ? WHERE id_usuario = ? AND id_almacen = ?";
        $datos = array($estado, $id_usuario, $id_almacen);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function modificarKardex($estado, $id_usuario, $id_almacen)
    {
        $sql = "UPDATE ".db_conect."verstockkardex SET estado_kardex = ? WHERE id_usuario = ? AND id_almacen = ?";
        $datos = array($estado, $id_usuario, $id_almacen);
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