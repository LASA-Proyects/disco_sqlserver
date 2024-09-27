<?php
class LoginModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAlmacenes($id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."almacen WHERE id = $id_almacen";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPermiUsu($id)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_usuario = $id AND id_permiso_accion IS NULL";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPermisoAlmc($id_usuario, $id_almacen)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_permisos_almc WHERE id_usuario = $id_usuario AND id_almacen = $id_almacen";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuario(string $usuario, string $clave)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
        $data = $this->select($sql);
        return $data;
    }

    public function getUsuariosLogin($id)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function getAlmacenesUsuario($id)
    {
        if($id == 1){
            $sql = "SELECT id AS id_almacen, nombre AS nombre_almacen FROM ".db_conect."almacen";
        }else{
            $sql = "SELECT da.*,a.nombre AS nombre_almacen FROM ".db_conect."detalle_permisos_almc da
            JOIN ".db_conect."almacen a ON a.id = da.id_almacen WHERE da.id_usuario = $id";
        }
        $data = $this->selectAll($sql);
        return $data;
    }

    public function asignarAlmac($id_usuario, $id_almacen)
    {
        $this->almacen = $id_almacen;
        $this->id = $id_usuario;
        $sql = "UPDATE ".db_conect."usuarios SET id_almacen = ? WHERE id = ?";
        $datos = array($this->almacen, $this->id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
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