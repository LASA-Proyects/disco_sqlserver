<?php
class FamiliaModel extends Query{
    private $nombre, $data, $id;
    public function __construct()
    {
        parent::__construct();
    }

    public function getFamilia()
    {
        $sql = "SELECT * FROM ".db_conect."familia";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getLineas()
    {
        $sql = "SELECT * FROM ".db_conect."linea_productos";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function registrarFamilia(string $nombre, string $img, int $linea)
    {
        $this->nombre = $nombre;
        $this->img = $img;
        $this->linea = $linea;
        if(empty($existe)){
            $sql = "INSERT INTO ".db_conect."familia(nombre, foto, id_linea) VALUES (?,?,?)";
            $datos = array($this->nombre, $this->img, $this->linea);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
        }else{
            $res = "existe";
        }
        return $res;
    }

    public function modificarFamilia(string $nombre, string $img, int $linea, int $id)
    {
        $this->nombre = $nombre;
        $this->img = $img;
        $this->linea = $linea;
        $this->id = $id;
        $sql = "UPDATE ".db_conect."familia SET nombre = ?, foto = ?, id_linea = ? WHERE id = ?";
        $datos = array($this->nombre, $this->img, $this->linea, $this->id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function editarFamilia(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."familia WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }

    public function eliminarFamilia(int $id)
    {
        $sql = "DELETE FROM ".db_conect."familia WHERE id = $id";
        $data = $this->save_delete($sql);
        if($data == 1){
            $res = "eliminado";
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