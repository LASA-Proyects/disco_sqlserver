<?php
class Query extends Conexion{
    private $pdo, $con, $sql, $datos;
    public function __construct()
    {
        $this->pdo = new Conexion();
        $this->con = $this->pdo->conect();
    }
    public function select(string $sql, array $params = [])
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params);
        $data = $resul->fetch(PDO::FETCH_ASSOC);
        return $data;
    }
    
    public function selectAll(string $sql, array $params = [])
    {
        $this->sql = $sql;
        $resul = $this->con->prepare($this->sql);
        $resul->execute($params);
        $data = $resul->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function save(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if($data){
            $res = 1;
        }else{
            $res = 0;
        }
        return $res;
    }

    public function save_trans(string $sql, array $datos)
    {
        try {
            if (empty($sql)) {
                throw new Exception("SQL query cannot be empty");
            }
            $insert = $this->con->prepare($sql);
            $insert->execute($datos);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function save_reg(string $sql, array $datos)
    {
        try {
            if (empty($sql)) {
                throw new Exception("SQL query cannot be empty");
            }
    
            $insert = $this->con->prepare($sql);
            $insert->execute($datos);
            $id_pedido = null;
            $insert->bindColumn('id_pedido', $id_pedido);
            $insert->fetch(PDO::FETCH_BOUND);
            
            return $id_pedido;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function save_exist(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        $id = $insert->fetch(PDO::FETCH_ASSOC)['id'];
        return $id;
    }

    public function save_delete(string $sql, array $datos)
    {
        $this->sql = $sql;
        $this->datos = $datos;
        $insert = $this->con->prepare($this->sql);
        $data = $insert->execute($this->datos);
        if($data){
            $res = 1;
        }else{
            $res = 0;
        }
        return $res;
    }
}
?>