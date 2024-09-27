<?php
class TerminalModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function verificarPermiso(int $id_usuario, string $permiso)
    {
        $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM ".db_conect."permisos p 
        INNER JOIN ".db_conect."detalle_permisos d ON p.id = d.id_permiso 
        WHERE d.id_usuario = $id_usuario AND p.permiso = '$permiso'";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function consultar_verificacion(int $id_opcion)
    {
        $sql = "SELECT * FROM ".db_conect."permisos WHERE id = $id_opcion";
        $data = $this->select($sql);
        return $data;
    }

    public function getAutorizadorClave()
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE GETDATE() BETWEEN fecha_ini AND fecha_fin AND tipo_usuario = 8";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuario(string $id_usuario, string $clave)
    {
        $sql = "SELECT * FROM ".db_conect."usuarios WHERE id = $id_usuario AND clave = '$clave'";
        $data = $this->select($sql);
        return $data;
    }

}
?>