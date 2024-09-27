<?php
class RecetasModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProductos()
    {
        $sql = "SELECT p.*, f.nombre AS familia FROM ".db_conect."productos p 
        JOIN ".db_conect."familia f ON p.id_familia = f.id
        WHERE p.id_tarticulo != 3";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getDetalleRec($id)
    {
        $sql = "SELECT dr.*, p.descripcion, p.unidad_med FROM ".db_conect."detalle_receta dr 
        JOIN ".db_conect."productos p ON dr.id_producto = p.id 
        WHERE dr.id_receta = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProductGen($id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function consultarRecPed(int $id_receta, int $id_producto)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_receta WHERE id_producto = $id_producto AND id_receta = $id_receta";
        $data = $this->select($sql);
        return $data;
    }

    public function actualizarProdRect($cantidad, $total, $id_producto, $id_receta)
    {
        $sql = "EXEC ".db_conect."actualizar_producto_receta @p_cantidad = ?, @p_total = ?, @p_id_producto = ?, @p_id_receta = ?";
        $datos = array($id_producto, $id_receta, $cantidad, $total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function buscarProduct($id){
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function ingresarProdRect($id_producto, $id_receta, $cantidad, $total)
    {
        $sql = "EXEC ".db_conect."ingresar_producto_receta @p_id_producto = ?, @p_id_receta = ?, @p_cantidad = ?, @p_total = ?";
        $datos = array($id_producto, $id_receta, $cantidad, $total);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function deleteDetalleRec($id)
    {
        $sql = "EXEC ".db_conect."eliminar_detalle_receta @p_id = ?";
        $datos = array($id);
        $data = $this->save_delete($sql, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error";
        }
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