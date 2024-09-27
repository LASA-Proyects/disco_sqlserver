<?php
class Login extends Controller{

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
        $this->views->getView($this, "index");
    }

    public function validar(){
        if(empty($_POST['usuario']) || empty($_POST['clave'])){
            $msg = "Los campos estan vacios";
        }else{
            $usuario = $_POST['usuario'];
            $clave = $_POST['clave'];
            $almacen = $_POST['almacen'];
            $datos_al = $this->model->getAlmacenes($almacen);
            $nombre_al = $datos_al[0]['nombre'];
            $id_almacen = $datos_al[0]['id'];
            $verif = $this->model->getUsuariosLogin($_POST['usuario_select']);
            if($verif['tipo_usuario'] == 1 || $verif['tipo_usuario'] == 2 || $verif['tipo_usuario'] == 5){
                $hash = hash("SHA256", $clave);
                $data = $this->model->getUsuario($usuario, $hash);
            }else{
                $data = $this->model->getUsuario($usuario, $clave);
            }
            date_default_timezone_set('America/Lima');
            if ($data) {
                $horarioValido = ($data['id'] != 1) ? ($data['fecha_ini'] . ' ' . $data['hora_ini'] <= date('Y-m-d H:i:s') && date('Y-m-d H:i:s') <= $data['fecha_fin'] . ' ' . $data['hora_fin']) : true;
                if ($horarioValido) {
                    $validarPermisoAlmc = $this->model->getPermisoAlmc($data['id'], $_POST['almacen']);
                    if(!empty($validarPermisoAlmc) || $data['id'] == 1){
                        $_SESSION['id_usuario'] = $data['id'];
                        $_SESSION['usuario'] = $data['usuario'];
                        $_SESSION['nombre'] = $data['nombre'];
                        $permisos = $this->model->getPermiUsu($data['id']);
                        $_SESSION['permisos'] = $permisos;
                        $_SESSION['tipo_usuario'] = $data['tipo_usuario'];
                        $_SESSION['activo'] = true;
                        $_SESSION['id_almacen'] = $id_almacen;
                        $_SESSION['almacen'] = $almacen;
                        $_SESSION['nom_al'] = $nombre_al;
                        $this->model->asignarAlmac($data['id'], $almacen);
                        date_default_timezone_set('America/Lima');
                        $ingresoFecha = date("Y-m-d h:i:s");
                        $this->model->registrarLog($data['id'],'Inicio de Sesión (Almacen: '.$nombre_al.')', $ingresoFecha, $id_almacen, NULL);
                        $msg = $_SESSION['tipo_usuario'];
                    }else{
                        $msg = "Usted no posee permiso para acceder a este almacén";
                    }
                } else {
                    $msg = "El horario de ingreso para este usuario ya caducó";
                }
            } else {
                $msg = "Usuario o contraseña incorrecta";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function buscarUsuario($id)
    {
        $data = $this->model->getUsuariosLogin($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarAlmacenesPorUsuario($id)
    {
        $data = $this->model->getAlmacenesUsuario($id);
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