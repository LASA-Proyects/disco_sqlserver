<?php
class Permisos extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 1, 3);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['usuarios'] = $this->model->getUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function getPermisosPadre()
    {
        $data['permisos_padres'] = $this->model->getPermisosPadre();
        $data['permisos_hijos'] = $this->model->getPermisosHijos();
        $data['permisos_accion'] = $this->model->getPermisosAccion();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarPermisoUsuario($id)
    {
        $data = $this->model->buscarPermisoUsuario($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarPermisos()
    {
        $id_usuario = $_POST['id_usuario'];
        $permisosData = json_decode($_POST['permisos'], true);
        $eliminar = $this->model->eliminarPermisos($id_usuario);
        $msg = "seleccion";
        if ($eliminar == "ok") {
            foreach ($permisosData as $permiso) {
                if (isset($permiso['id_padre'])) {
                    foreach ($permiso['id_padre'] as $idPadre) {
                        $data = $this->model->registrarPermisoPadre($id_usuario, $idPadre, NULL);
                    }
                    if ($data == "ok") {
                        $msg = array('msg' => 'Permiso registrado con Éxito', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al registrar el Permiso Padre', 'icono' => 'error');
                    }
                } else if (isset($permiso['id_hijo'])) {
                    foreach ($permiso['id_hijo'] as $idHijo) {
                        $consultar_padre = $this->model->consultar_padre($idHijo);
                        $data = $this->model->registrarPermisoHijo($id_usuario, $consultar_padre['id_permiso_padre'], $idHijo);
                    }
                    if ($data == "ok") {
                        $msg = array('msg' => 'Permiso registrado con Éxito', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al registrar el Permiso Hijo', 'icono' => 'error');
                    }
                }else if(isset($permiso['id_accion'])){
                    foreach ($permiso['id_accion'] as $idAccion) {
                        $consultar_hijo = $this->model->consultar_hijo($idAccion);
                        $data = $this->model->registrarPermisoAccion($id_usuario, $consultar_hijo['id_permiso_padre'], $consultar_hijo['id_permiso_hijo'], $idAccion);
                    }
                    if ($data == "ok") {
                        $msg = array('msg' => 'Permiso registrado con Éxito', 'icono' => 'success');
                    } else {
                        $msg = array('msg' => 'Error al registrar el Permiso Hijo', 'icono' => 'error');
                    }
                }
            }
        } else {
            $msg = array('msg' => 'Error al registrar los Permisos Anteriores', 'icono' => 'error');
        }
    
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
?>