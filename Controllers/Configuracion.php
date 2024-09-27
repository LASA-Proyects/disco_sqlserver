<?php
class Configuracion extends Controller{
    public function __construct()
    {
        session_start();
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        parent::__construct();
    }
    public function index()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'configuracion');
        if(!empty($verificar) || $id_usuario == 1){
            $data = $this->model->getNegocio();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function home()
    {
        $data['resumen_ventas_gen'] = $this->model->getResumenVentasGen();
        $data['resumen_ventas_det'] = $this->model->getResumenVentasDet();
        $this->views->getView($this, "home", $data);
    }

    public function getResumenVentasDet()
    {
        $data = $this->model->getResumenVentasDet();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getResumenClientesDet()
    {
        $data = $this->model->getResumenClientesDet();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
