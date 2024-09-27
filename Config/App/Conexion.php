<?php
class Conexion {
    private $conect;
    public function __construct() {
        $dsn = "sqlsrv:Server=" . host . "," . port . ";Database=" . db .";TrustServerCertificate=1";
        
        try {
            $this->conect = new PDO($dsn, user, pass);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }

    public function conect() {
        return $this->conect;
    }
}
?>