<?php
class Terminal extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL POS');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "index");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function mbebidas()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL BEBIDAS');
        if(!empty($verificar) || $id_usuario == 1){
            $data['usuarios'] = $this->model->getAutorizadorClave();
            $this->views->getView($this, "mbebidas", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function mboleteria()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL BOLETERIA');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "mboleteria");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function mcocteleria()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL COCTELERIA');
        if(!empty($verificar) || $id_usuario == 1){
            $data['usuarios'] = $this->model->getAutorizadorClave();
            $this->views->getView($this, "mcocteleria", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function mcocina()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL COCINA');
        if(!empty($verificar) || $id_usuario == 1){
            $data['usuarios'] = $this->model->getAutorizadorClave();
            $this->views->getView($this, "mcocina", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function mbancos()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL BANCOS');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "mbancos");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function malmacen()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'TERMINAL ALMACEN');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "malmacen");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function consultar_verificacion(int $id_opcion)
    {
        $data = $this->model->consultar_verificacion($id_opcion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verificarContrasena()
    {
        $hash = hash("SHA256", $_POST['clave']);
        $data = $this->model->getUsuario($_POST['usuario'], $hash);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>