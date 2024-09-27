<?php
class KardexModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAlmacenes()
    {
        $sql = "SELECT * FROM ".db_conect."almacen";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getProductos()
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id_tarticulo != 3";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarProdId(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."productos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarProdCod(string $cod)
    {
        $sql = "SELECT id FROM ".db_conect."productos WHERE codigo = '$cod'";
        $data = $this->select($sql);
        return $data;
    }

    public function buscarKardexProdGen(string $cod, string $fecha_hasta)
    {
        $sql = "SELECT * FROM ".db_conect."v_invdetal WHERE CodigoProd = '$cod' AND fecha_ingreso <= '$fecha_hasta' ORDER BY fecha_ingreso";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarKardexId(int $id_producto, int $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."v_invdetal WHERE id_producto = $id_producto AND id_almacen = $id_almacen ORDER BY fecha_ingreso";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarKardexProd(string $cod, string $fecha_hasta, int $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."v_invdetal WHERE CodigoProd = '$cod' AND id_almacen = $id_almacen AND fecha_ingreso <= '$fecha_hasta' ORDER BY fecha_ingreso";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function rangoCodigo(int $id_inicial, int $id_final, string $fecha_inicial, string $fecha_final){
        $sql = "SELECT * FROM ".db_conect."v_invdetal WHERE id_producto BETWEEN $id_inicial AND $id_final AND fecha_ingreso BETWEEN '$fecha_inicial' AND '$fecha_final' ORDER BY fecha_ingreso";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre, int $permiso_hijo)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_permiso_hijo = $permiso_hijo AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function bucarInvdetal(int $id_detalle, int $id_producto)
    {
        $sql = "SELECT Origen FROM ".db_conect."v_invdetal WHERE id_detalle = $id_detalle AND id_producto = $id_producto";
        $data = $this->select($sql);
        return $data;
    }

    public function getKardexGen(string $fecha_inicial_gen, string $fecha_final_gen)
    {
        $sql = "SELECT * FROM ".db_conect."v_invdetal WHERE fecha BETWEEN '$fecha_inicial_gen' AND '$fecha_final_gen'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPrimeraFecha(int $valor)
    {
        if($valor == 1){
            $sql = "SELECT TOP 1 fecha_ingreso 
            FROM ".db_conect."v_invdetal WHERE fecha_ingreso IS NOT NULL
            ORDER BY fecha_ingreso ASC";
            $data = $this->select($sql);
            return $data;
        }else if($valor == 2){
            $sql = "SELECT TOP 1 fecha_ingreso 
            FROM ".db_conect."v_invdetal WHERE fecha_ingreso IS NOT NULL
            ORDER BY fecha_ingreso DESC";
            $data = $this->select($sql);
            return $data;
        }
    }

    public function getUsuariosAlmc($id_usuario)
    {
        $sql = "SELECT a.id, a.nombre FROM ".db_conect."detalle_permisos_almc da
        INNER JOIN ".db_conect."almacen a ON a.id = da.id_almacen
        WHERE da.id_usuario = $id_usuario";
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
}
?>