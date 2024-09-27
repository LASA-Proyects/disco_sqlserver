<?php
class UsuariosModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {
        $sql = "SELECT u.*, tu.nombre AS t_usu_nom FROM ".db_conect."usuarios u
        JOIN ".db_conect."tipo_usuario tu ON u.tipo_usuario = tu.id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getTipoUsuarios()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_usuario WHERE id != 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarUsuario(int $num_doc, string $usuario, string $nombre, string $clave, int $tipo_usuario, int $codigo_vendedor)
    {
        $sql = "EXEC ".db_conect."registrar_usuario @p_num_doc = ?, @p_usuario = ?, @p_nombre = ?, @p_clave = ?, @p_tipo_usuario = ?, @p_codigo_vendedor = ?";
        $datos = array($num_doc, $usuario, $nombre, $clave, $tipo_usuario, $codigo_vendedor);
        $data = $this->save_exist($sql, $datos);
        if($data != 0){
            $res['res'] = "ok";
            $res['sql'] = "INSERT INTO ".db_conect."usuarios(num_doc, usuario, nombre, clave, tipo_usuario, codigo_vendedor) VALUES ('$num_doc','$usuario', '$nombre', '$clave', $tipo_usuario, $codigo_vendedor);";
        }else{
            $res['res'] = "existe";
        }
        return $res;
    }

    public function registrarFechasUsu(string $fecha_ini, string $hora_ini, string $fecha_fin, string $hora_fin, int $id)
    {
        $sql = "EXEC ".db_conect."registrar_fecha_usuario @p_fecha_ini = ?, @p_hora_ini = ?, @p_fecha_fin = ?, @p_hora_fin = ?, @p_id = ?";
        $datos = array($fecha_ini, $hora_ini, $fecha_fin, $hora_fin, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function modificarUsuario(int $num_doc, string $usuario, string $nombre, string $clave, int $tipo_usuario, int $codigo_vendedor, int $id)
    {
        $sql = "EXEC ".db_conect."modificar_usuario @p_num_doc = ?, @p_usuario = ?, @p_nombre = ?, @p_clave = ?, @p_tipo_usuario = ?, @p_codigo_vendedor = ?, @p_id = ?";
        $datos = array($num_doc, $usuario, $nombre, $clave, $tipo_usuario, $codigo_vendedor, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res['res'] = "modificado";
            $res['sql'] = "UPDATE ".db_conect."usuarios SET num_doc = '$num_doc', usuario = '$usuario', nombre = '$nombre', clave = '$clave', tipo_usuario = $tipo_usuario, codigo_vendedor = $codigo_vendedor WHERE id = $id;";
        }else{
            $res['res'] = "error";
        }
        return $res;
    }

    public function editarUser(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function accionUser(int $estado, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        $sql = "UPDATE ".db_conect."usuarios SET estado = ? WHERE id = ?";
        $datos = array($this->estado, $this->id);
        $res['res'] = $this->save($sql, $datos);
        $res['sql'] = "UPDATE ".db_conect."usuarios SET estado = '$estado' WHERE id = '$id'";
        return $res;
    }

    public function eliminarUsuario(int $id)
    {
        $sql = "EXEC ".db_conect."eliminar_usuario @p_id = ?";
        $datos = array($id);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res['res'] = "eliminado";
            $res['sql'] = "DELETE FROM ".db_conect."usuarios WHERE id = $id;";
        }else{
            $res['res'] = "error";
        }
        return $res;
    }

    public function getPermisos()
    {
        $sql = "SELECT * FROM ".db_conect."permisos";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getAlmacenes()
    {
        $sql = "SELECT * FROM ".db_conect."almacen";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getDetallePermisos(int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_permisos WHERE id_usuario = $id_usuario";
        $data = $this->selectall($sql);
        return $data;
    }

    public function getDetallePermisosAlmc(int $id_usuario)
    {
        $sql = "SELECT * FROM ".db_conect."detalle_permisos_almc WHERE id_usuario = $id_usuario";
        $data = $this->selectall($sql);
        return $data;
    }

    public function registrarPermisos(int $id_usuario, int $id_permiso)
    {
        $sql = "INSERT INTO ".db_conect."detalle_permisos (id_usuario, id_permiso) VALUES (?,?)";
        $datos = array($id_usuario, $id_permiso);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarPermisosAlmc(int $id_usuario, int $id_almacen)
    {
        $sql = "INSERT INTO ".db_conect."detalle_permisos_almc (id_usuario, id_almacen) VALUES (?,?)";
        $datos = array($id_usuario, $id_almacen);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
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
    
    public function eliminarPermisosAlmc(int $id_usuario)
    {
        $sql = "DELETE FROM ".db_conect."detalle_permisos_almc WHERE id_usuario = ?";
        $datos = array($id_usuario);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
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