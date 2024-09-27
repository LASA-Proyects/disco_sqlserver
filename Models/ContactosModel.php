<?php
class ContactosModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProveedor()
    {
        $sql = "SELECT c.*, tp.nombre AS tipo_contacto FROM ".db_conect."contactos c
        INNER JOIN ".db_conect."tipo_persona tp ON c.id_tipo_persona = tp.id;";
        $data = $this->selectAll($sql);
        return $data;
    }
    
    public function getTipoPersona()
    {
        $sql = "SELECT * FROM ".db_conect."tipo_persona";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function verificarContactoDNI($dni)
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE dni = $dni";
        $data = $this->select($sql);
        return $data;
    }

    public function verificarContactoRUC($ruc)
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE ruc = $ruc";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarProveedor(?int $dni = NULL, ?string $correo = NULL, ?string $nombres = NULL, ?string $apellidoPaterno = NULL, ?string $apellidoMaterno = NULL, ?string $ruc = NULL, ?string $razon_social = NULL, ?string $direccion = NULL, int $id_tipo_persona, string $fecha_alta, ?int $numero_mtc = NULL, ?string $placa = NULL, ?string $licencia = NULL)
    {
        $sql = "INSERT INTO ".db_conect."contactos(dni, correo, nombres, apellidoPaterno, apellidoMaterno, ruc, razon_social, direccion, id_tipo_persona, fecha_alta, numero_mtc, placa, licencia) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $datos = array($dni, $correo, $nombres, $apellidoPaterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $fecha_alta, $numero_mtc, $placa, $licencia);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "ok";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function modificarProveedor(?int $dni = NULL, ?string $correo = NULL, ?string $nombres = NULL, ?string $apellidoPaterno = NULL, ?string $apellidoMaterno = NULL, ?string $ruc = NULL, ?string $razon_social = NULL, ?string $direccion = NULL, int $id_tipo_persona, int $id)
    {
        $sql = "UPDATE ".db_conect."contactos SET dni = ?, correo = ?, nombres = ?, apellidoPaterno = ?, apellidoMaterno = ?, ruc = ?, razon_social = ?, direccion = ?, id_tipo_persona = ? WHERE id = ?";
        $datos = array($dni, $correo, $nombres, $apellidoPaterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $id);
        $data = $this->save($sql, $datos);
        if($data == 1){
            $res = "modificado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function editarProveedor(int $id)
    {
        $sql = "SELECT * FROM ".db_conect."contactos WHERE id = $id";
        $data = $this->select($sql);
        return $data;
    }
    public function accionProveedor(int $estado, string $fecha_cese, int $id)
    {
        $this->id = $id;
        $this->estado = $estado;
        
        if ($fecha_cese === "0000-00-00 00:00:00") {
            $sql = "UPDATE ".db_conect."contactos SET fecha_cese = NULL, estado = ? WHERE id = ?";
            $datos = array($this->estado, $this->id);
        } else {
            $sql = "UPDATE ".db_conect."contactos SET fecha_cese = ?, estado = ? WHERE id = ?";
            $datos = array($fecha_cese, $this->estado, $this->id);
        }
        
        $data = $this->save($sql, $datos);
        return $data;
    }

    public function eliminarProveedor(int $id)
    {
        $sql = "CALL eliminar_contacto(?)";
        $datos = array($id);
        $data = $this->save_delete($sql, $datos);
        if($data == 1){
            $res = "eliminado";
        }else{
            $res = "error";
        }
        return $res;
    }

    public function verificarPermiso(int $id_usuario, int $permiso_padre)
    {
        $sql = "SELECT * FROM ".db_conect."permiso_usuario WHERE id_permiso_padre = $permiso_padre AND id_usuario = $id_usuario";
        $data = $this->selectAll($sql);
        return $data;
    }
}
?>