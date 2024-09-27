<?php
class PermisosModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id != 1";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPermisosAccion()
    {
        $sql = "SELECT * FROM ".db_conect."permiso_accion";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPermisosPadre()
    {
        $sql = "SELECT * FROM ".db_conect."permiso_padre";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function buscarPermisoUsuario(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_usuario = $id";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getPermisosHijos()
    {
        $sql = "SELECT * FROM ".db_conect."permiso_hijo";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultar_padre(int $id_hijo)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_hijo WHERE id = $id_hijo";
        $data = $this->select($sql);
        return $data;
    }

    public function consultar_hijo(int $id_accion)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_accion WHERE id = $id_accion";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarPermisoPadre(int $id_usuario, int $id_padre, ?int $id_hijo = NULL)
    {
        $sql = "INSERT INTO ".db_conect."permiso_usuario(id_usuario, id_permiso_padre, id_permiso_hijo) VALUES (?,?,?)";
        $datos = array($id_usuario, $id_padre, $id_hijo);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarPermisoHijo(int $id_usuario, int $id_padre, ?int $id_hijo = NULL)
    {
        $sql = "INSERT INTO ".db_conect."permiso_usuario(id_usuario, id_permiso_padre, id_permiso_hijo) VALUES (?,?,?)";
        $datos = array($id_usuario, $id_padre, $id_hijo);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function registrarPermisoAccion(int $id_usuario, int $id_padre, ?int $id_hijo = NULL, ?int $id_accion = NULL)
    {
        $sql = "INSERT INTO ".db_conect."permiso_usuario(id_usuario, id_permiso_padre, id_permiso_hijo, id_permiso_accion) VALUES (?,?,?, ?)";
        $datos = array($id_usuario, $id_padre, $id_hijo, $id_accion);
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
        $sql = "DELETE FROM ".db_conect."permiso_usuario WHERE id_usuario = ?";
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
}
?>