<?php
class ComprasModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }
    public function getProdCod(string $cod)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE codigo = '$cod'";
        $data = $this->select($sql);
        return $data;
    }
    public function getProductos(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getListaProductos()
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id_tarticulo != 3 AND estado = 1 AND afecta_compra = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProveedores()
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE estado = 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTDoc()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_documento";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoOperacionesCompra(array $id_acciones)
    {
        if (empty($id_acciones)) {
            return [];
        }
        $id_acciones = array_map('intval', $id_acciones);
        $ids_string = implode(',', $id_acciones);
        $sql = "SELECT * FROM ".db_conect."tipo_operaciones WHERE tipo = 'IN' AND id IN ($ids_string)";
        $data = $this->selectAll($sql);
    
        return $data;
    }

    public function getTipoOperacionesCompraGen()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_operaciones WHERE tipo = 'IN'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarTipoOperaciones(array $id_permiso_accion)
    {
        if (empty($id_permiso_accion)) {
            return [];
        }
        $id_permiso_accion = array_map('intval', $id_permiso_accion);
        $id_permiso_accion_string = implode(',', $id_permiso_accion);
        $sql = "SELECT * FROM ".db_conect."permiso_accion WHERE id IN ($id_permiso_accion_string)";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoOperacionesVenta(array $id_acciones)
    {
        if (empty($id_acciones)) {
            return [];
        }
        $id_acciones = array_map('intval', $id_acciones);
        $ids_string = implode(',', $id_acciones);
        $sql = "SELECT * FROM ".db_conect."tipo_operaciones WHERE tipo = 'OUT' AND id IN ($ids_string)";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoOperacionesVentaGen()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_operaciones WHERE tipo = 'OUT'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getAlmacen()
    {
        $sql = "SELECT * FROM ".db_conect."almacen";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarDetalle(string $table, int $id_producto, int $id_usuario, float $precio, int $cantidad, float $sub_total)
    {
        $sql = "EXEC ".db_conect."registrarDetalle @p_table = ?, @p_id_producto = ?, @p_id_usuario = ?, @p_precio = ?, @p_cantidad = ?, @p_sub_total = ?";
        $datos = array($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
        array_unshift($datos, $table);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function editarCompra(int $id_usuario, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, string $total, int $id_compra_edit)
    {
        $sql = "UPDATE ".db_conect."compra SET id_usuario = ?, id_proveedor = ?, id_tipo_operacion = ?, fecha_ingreso = ?, tipo_cambio = ?, id_tipo_doc = ?, id_almacen_ini = ?, id_almacen_fin = ?, serie = ?, correlativo = ?, total = ? WHERE id = ?";
        $datos = array($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $id_compra_edit);
        try {
            $data = $this->save_trans($sql, $datos);
            $res['res'] = "modificado";
            $res['sql'] = "UPDATE ".db_conect."compra SET id_usuario = $id_usuario, id_proveedor = $id_proveedor, id_tipo_operacion = $tipo_operacion, fecha_ingreso = '$fecha_ingreso', tipo_cambio = $tipo_cambio, id_tipo_doc = $tipo_documento, id_almacen_ini = $id_almacen_ini, id_almacen_fin = $id_almacen_fin, serie = '$serie', correlativo = $correlativo, total = '$total' WHERE id = $id_compra_edit;";
            return $res;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function editarCompraTransf(int $id_usuario, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, string $total, int $id_compra_edit)
    {
        $sql = "UPDATE ".db_conect."compra SET id_usuario = ?, id_proveedor = ?, id_tipo_operacion = ?, fecha_ingreso = ?, tipo_cambio = ?, id_tipo_doc = ?, id_almacen_ini = ?, id_almacen_fin = ?, serie = ?, correlativo = ?, total = ? WHERE id_venta = ?";
        $datos = array($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $id_compra_edit);
        try {
            $data = $this->save_trans($sql, $datos);
            $res['res'] = "modificado";
            $res['sql'] = "UPDATE ".db_conect."compra SET id_usuario = $id_usuario, id_proveedor = $id_proveedor, id_tipo_operacion = $tipo_operacion, fecha_ingreso = '$fecha_ingreso', tipo_cambio = $tipo_cambio, id_tipo_doc = $tipo_documento, id_almacen_ini = $id_almacen_ini, id_almacen_fin = $id_almacen_fin, serie = '$serie', correlativo = $correlativo, total = '$total' WHERE id_venta = $id_compra_edit;";
            return $res;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function editarVenta(int $id_usuario, int $id_proveedor, int $tipo_operacion, string $fecha_ingreso, float $tipo_cambio, int $tipo_documento, ?int $id_almacen_ini = null, ?string $id_almacen_fin = null, string $serie, int $correlativo, string $total, int $id_venta_edit)
    {
        $sql = "UPDATE ".db_conect."venta SET id_usuario = ?, id_proveedor = ?, id_tipo_operacion = ?, fecha_ingreso = ?, tipo_cambio = ?, id_tipo_doc = ?, id_almacen_ini = ?, id_almacen_fin = ?, serie = ?, correlativo = ?, total = ? WHERE id = ?";
        $datos = array($id_usuario, $id_proveedor, $tipo_operacion, $fecha_ingreso, $tipo_cambio, $tipo_documento, $id_almacen_ini, $id_almacen_fin, $serie, $correlativo, $total, $id_venta_edit);
        try {
            $data = $this->save_trans($sql, $datos);
            $res['res'] = "modificado";
            $res['sql'] = "UPDATE ".db_conect."venta SET id_usuario = $id_usuario, id_proveedor = $id_proveedor, id_tipo_operacion = $tipo_operacion, fecha_ingreso = '$fecha_ingreso', tipo_cambio = $tipo_cambio, id_tipo_doc = $tipo_documento, id_almacen_ini = $id_almacen_ini, id_almacen_fin = $id_almacen_fin, serie = '$serie', correlativo = $correlativo, total = '$total' WHERE id = $id_venta_edit;";
            return $res;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function eliminarDetalleCompra(int $id_compra)
    {
        $sql = "DELETE FROM ".db_conect."detalle_compras WHERE id_compra = ?";
        $datos = array($id_compra);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res['res'] = "eliminado";
            $res['sql'] = "DELETE FROM ".db_conect."detalle_compras WHERE id_compra = $id_compra;";
        }else{
            $res['res'] = "error";
        }
        return $res;
    }

    public function eliminarDetalleCompraTransf(int $id_venta)
    {
        $sql = "DELETE FROM ".db_conect."detalle_compras WHERE id_venta = ?";
        $datos = array($id_venta);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res['res'] = "eliminado";
            $res['sql'] = "DELETE FROM ".db_conect."detalle_compras WHERE id_venta = $id_venta;";
        }else{
            $res['res'] = "error";
        }
        return $res;
    }

    public function eliminarDetalleVenta(int $id_venta)
    {
        $sql = "DELETE FROM ".db_conect."detalle_ventas WHERE id_venta = ?";
        $datos = array($id_venta);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res['res'] = "eliminado";
            $res['sql'] = "DELETE FROM ".db_conect."detalle_ventas WHERE id_venta = $id_venta;";
        }else{
            $res['res'] = "error";
        }
        return $res;
    }

    public function getDetalle(string $table, int $id)
    {
        $sql = "SELECT d.*, p.id AS id_pro, p.descripcion FROM ".db_conect."$table d
        INNER JOIN ".db_conect."productos p ON d.id_producto = p.id WHERE d.id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function calcularCompra(string $table, int $id_usuario)
    {
        $sql = "SELECT SUM(sub_total) AS total FROM ".db_conect."$table WHERE id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }
    public function deleteDetalle(string $table, int $id)
    {
        $sql = "DELETE FROM ".db_conect."$table WHERE id = ?";
        $datos = array($id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }
    public function consultarDetalle(string $table,int $id_producto, int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."$table WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarDetalle(string $table, float $precio, int $cantidad, float $sub_total, int $id_producto, int $id_usuario)
    {
        $sql = "EXEC ".db_conect."actualizarDetalle @p_table = ?, @p_precio = ?, @p_cantidad = ?, @p_sub_total = ?, @p_id_producto = ?, @p_id_usuario = ?";
        $datos = array($precio, $cantidad, $sub_total, $id_producto, $id_usuario);
        array_unshift($datos, $table);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarCompra(int $id_usuario, ?int $id_venta = NULL, int $id_proveedor, int $tipo_operacion, ?string $fecha_ingreso = NULL, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, float $total, string $fecha_actual)
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
    
    public function registrarVenta(int $id_usuario, int $id_proveedor, int $tipo_operacion, ?string $fecha_ingreso = NULL, float $tipo_cambio, int $tipo_documento, int $id_almacen_ini, ?string $id_almacen_fin = null, string $serie, int $correlativo, float $total, string $fecha_actual)
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

    public function getId(string $table)
    {
        $sql = "SELECT MAX(id) AS id FROM ".db_conect."$table";
        $data = $this->select($sql);
        return $data;
    }

    public function getDetalleTransf(int $id_venta)
    {
        $sql = "SELECT * FROM ".db_conect."compra WHERE id_venta = $id_venta";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarDetalleCompra(int $id_compra, ?int $id_venta = NULL, int $id_producto, int $id_almacen, float $cantidad, float $precio, string $sub_total, ?string $obsercaion = NULL)
    {
        $sql = "EXEC ".db_conect." registrarDetalleCompra @p_id_compra = ?, @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?, @p_observacion = ?";
        $datos = array($id_compra, $id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total, $obsercaion);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleCompra('$id_compra', '$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total', '$obsercaion')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
    }

    public function registrarDetalleVenta(int $id_venta, int $id_producto, int $id_almacen, float $cantidad, float $precio, string $sub_total, ?string $obsercaion = NULL)
    {
        $sql = "EXEC ".db_conect." registrarDetalleVenta @p_id_venta = ?, @p_id_producto = ?, @p_id_almacen = ?, @p_cantidad = ?, @p_precio = ?, @p_sub_total = ?, @p_observacion = ?";
        $datos = array($id_venta, $id_producto, $id_almacen, $cantidad, $precio, $sub_total, $obsercaion);
        try {
            $data = $this->save_trans($sql, $datos);
            $datos = "CALL registrarDetalleVenta('$id_venta', '$id_producto', '$id_almacen', '$cantidad', '$precio', '$sub_total', '$obsercaion')";
            return $datos;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            throw $e;
        }
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
    public function vaciarDetalle(string $table, int $id_usuario)
    {
        $sql = "DELETE FROM ".db_conect."$table WHERE id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }
    public function getProCompra(int $id_compra)
    {
        $sql = "SELECT c.*, d.*, p.id, p.descripcion, p.codigo FROM ".db_conect."compra c
        INNER JOIN ".db_conect."detalle_compras d ON c.id = d.id_compra
        INNER JOIN ".db_conect."productos p ON p.id = d.id_producto
        WHERE c.id = $id_compra";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProVenta(int $id_venta)
    {
        $sql = "SELECT v.*, d.*, p.id, p.descripcion, p.codigo FROM ".db_conect."venta v
        INNER JOIN ".db_conect."detalle_ventas d ON v.id = d.id_venta
        INNER JOIN ".db_conect."productos p ON p.id = d.id_producto WHERE v.id = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getHistorialCompras(string $desde, string $hasta, int $id_almacen)
    {
        $sql = "SELECT c.id_proveedor, c.id_tipo_operacion, c.fecha_ingreso, c.id, tp.nombre AS operacion , c.total, c.fecha, c.fecha_ingreso, c.estado,
        CASE 
            WHEN cont.dni IS NOT NULL AND cont.dni <> '' THEN CONCAT(cont.nombres, ' ', cont.apellidoPaterno, ' ', cont.apellidoMaterno)
            WHEN cont.ruc IS NOT NULL AND cont.ruc <> '' THEN cont.razon_social
            ELSE 'PROVEEDOR DESCONOCIDO'
        END AS proveedor
        FROM ".db_conect."compra c
        JOIN ".db_conect."tipo_operaciones tp ON tp.id = c.id_tipo_operacion
        JOIN ".db_conect."contactos cont ON cont.id = c.id_proveedor 
        WHERE c.id_almacen_ini = $id_almacen AND c.fecha_ingreso BETWEEN '$desde' AND '$hasta';
        ";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function getHistorialVentas(string $desde, string $hasta, int $id_almacen)
    {
        $sql = "SELECT v.id, tp.nombre AS operacion , v.id_tipo_operacion, v.total, v.fecha, v.fecha_ingreso, v.estado FROM ".db_conect."venta v
        JOIN ".db_conect."tipo_operaciones tp ON tp.id = v.id_tipo_operacion
        WHERE v.id_almacen_ini = $id_almacen AND v.fecha_ingreso BETWEEN '$desde' AND '$hasta'";
        $data = $this->selectAll($sql);
        return $data;
    }
    public function actualizarStock(int $cantidad, int $id_producto)
    {
        $sql = "UPDATE ".db_conect."productos SET cantidad = ? WHERE id = ?";
        $datos = array($cantidad, $id_producto);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function anularCompra(int $id_compra)
    {
        $sql = "EXEC ".db_conect." anular_compra @p_id = ?";
        $datos = array($id_compra);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function getCompra(int $id_compra)
    {
        $sql = "SELECT c.*, a.nombre AS nombre_almacen, ct.nombres, ct.razon_social,td.nombre AS documento FROM ".db_conect."compra c 
        JOIN ".db_conect."almacen a ON a.id = c.id_almacen_ini 
        JOIN ".db_conect."contactos ct ON ct.id = c.id_proveedor 
        JOIN ".db_conect."tipo_documento td ON td.id = c.id_tipo_doc
        WHERE c.id = $id_compra";
        $data = $this->select($sql);
        return $data;
    }

    public function getVenta(int $id_venta)
    {
        $sql = "SELECT v.*, a.nombre AS nombre_almacen, td.nombre AS documento FROM ".db_conect."venta v
        JOIN ".db_conect."almacen a ON a.id = v.id_almacen_ini 
        JOIN ".db_conect."tipo_documento td ON td.id = v.id_tipo_doc
        WHERE v.id = $id_venta";
        $data = $this->select($sql);
        return $data;
    }

    public function getDetalleCompras(int $id_compra)
    {
        $sql = "SELECT dp.*, p.descripcion AS nombre_producto, p.codigo FROM ".db_conect."detalle_compras dp
        JOIN ".db_conect."productos p ON p.id = dp.id_producto
        WHERE dp.id_compra = $id_compra";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getDetalleVentas(int $id_venta)
    {
        $sql = "SELECT dv.*, p.descripcion AS nombre_producto, p.codigo FROM ".db_conect."detalle_ventas dv
        JOIN ".db_conect."productos p ON p.id = dv.id_producto
        WHERE dv.id_venta = $id_venta";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function actualizarSerie(string $correlativo)
    {
        $sql = "UPDATE ".db_conect."series SET correlativo = ? WHERE cod = 'CMP'";
        $datos = array($correlativo);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function actualizarSerieVnt(string $correlativo)
    {
        $sql = "UPDATE ".db_conect."series SET correlativo = ? WHERE cod = 'VNT'";
        $datos = array($correlativo);
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function anularVenta(int $id_venta)
    {
        $sql = "EXEC ".db_conect." anular_venta @p_id = ?";
        $datos = array($id_venta);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarCaja(int $id){
        $sql = "SELECT * FROM ".db_conect."arqueo_caja WHERE id_usuario = $id AND estado = 1";
        $data = $this->select($sql);
        return $data; 
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre, int $permiso_hijo)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_permiso_hijo = $permiso_hijo AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
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