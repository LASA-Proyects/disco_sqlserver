<?php
class Home extends Controller{

    public function __construct() {
        session_start();
        if(!empty($_SESSION['activo'])){
            header("location: ".base_url."Usuarios");
        }
        parent::__construct();
    }
    public function index()
    {
        $data['usuarios'] = $this->model->getUsuarios();
        $data['almacenes'] = $this->model->getMesas();
        $this->views->getView($this,"index", $data);
    }
}
?>