<?php
class HomeModel extends Query{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM ".db_conect."usuarios
        WHERE estado = 1 
        AND GETDATE() BETWEEN CAST(fecha_ini AS DATETIME) + CAST(hora_ini AS DATETIME) 
        AND CAST(fecha_fin AS DATETIME) + CAST(hora_fin AS DATETIME)
        AND show_login = 1;";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getMesas()
    {
        $sql = "SELECT * FROM ".db_conect."almacen";
        $data = $this->selectAll($sql);
        return $data;
    }
}
?>