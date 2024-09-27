<?php
class Contactos extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 10);
        if(!empty($verificar) || $id_usuario == 1){
            $data['tipo_personas'] = $this->model->getTipoPersona();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }
    public function listar()
    {
        $data = $this->model->getProveedor();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-warning btn-sm" type="button" onclick="btnEditarProveedor('.$data[$i]['id'].');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-success btn-sm" type="button" onclick="btnEstadoProveedor('.$data[$i]['id'].');"><i class="fas fa-toggle-on"></i></button>
                <button class="btn btn-danger btn-sm" type="button" onclick="btnEliminarProveedor('.$data[$i]['id'].');"><i class="fas fa-trash"></i></button>
                <div/>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-secondary" type="button" onclick="btnActivarProveedor('.$data[$i]['id'].');"><i class="fas fa-toggle-off"></i></button>
                <div/>';
            }
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        date_default_timezone_set('America/Lima');
        $fecha_alta = date("Y-m-d h:i:s");
        $ruc = !empty($_POST['ruc']) ? (int)$_POST['ruc'] : null;
        $dni = !empty($_POST['dni']) ? (int)$_POST['dni'] : null;
        $nombres = $_POST['nombres'];
        $apellidoPeterno = $_POST['apellidoPeterno'];
        $apellidoMaterno = $_POST['apellidoMaterno'];
        $razon_social = $_POST['razon_social'];
        $direccion = $_POST['direccion'];
        $id_tipo_persona = $_POST['tipo_persona'];
        $correo = $_POST['correo'];
        $placa = $_POST['placa'];
        $licencia = $_POST['licencia'];
        $numero_mtc = !empty($_POST['numero_mtc']) ? $_POST['numero_mtc'] : null;
        $id = $_POST['id'];
        if($_POST['id'] == ""){
            if(!empty($dni)){
                $verificar = $this->model->verificarContactoDNI($dni);
                if(empty($verificar)){
                    $data = $this->model->registrarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $fecha_alta, $numero_mtc, $placa, $licencia);
                    if($data == "ok"){
                        $msg = array('msg'=> 'Contacto registrado con éxito', 'icono' => 'success');
                    }else if($data == "existe"){
                        $msg = array('msg'=> 'El Contacto ya existe', 'icono' => 'warning');
                    }else{
                        $msg = array('msg'=> 'Error al registrar el Contacto', 'icono' => 'error');
                    }
                }else{
                    $msg = array('msg'=> 'Ya existe un contacto con ese DNI', 'icono' => 'error');
                }
            }else{
                $verificar = $this->model->verificarContactoRUC($ruc);
                if(empty($verificar)){
                    $data = $this->model->registrarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $fecha_alta, $numero_mtc, $placa, $licencia);
                    if($data == "ok"){
                        $msg = array('msg'=> 'Contacto registrado con éxito', 'icono' => 'success');
                    }else if($data == "existe"){
                        $msg = array('msg'=> 'El Contacto ya existe', 'icono' => 'warning');
                    }else{
                        $msg = array('msg'=> 'Error al registrar el Contacto', 'icono' => 'error');
                    }
                }else{
                    $msg = array('msg'=> 'Ya existe un contacto con ese RUC', 'icono' => 'error');
                }
            }

        }else{
            if(!empty($dni)){
                $dni_mod = $_POST['dni_modificar'];
                $verificar = $this->model->verificarContactoDNI($dni);
                if($dni == $dni_mod){
                    $data = $this->model->modificarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $id);
                    if($data == "modificado"){
                        $msg = array('msg'=> 'Proveedor modificado con éxito', 'icono' => 'success');
                    }else{
                        $msg = array('msg'=> 'Error al Modificar el Proveedor', 'icono' => 'error');
                    }
                }else{
                    if(empty($verificar)){
                        $data = $this->model->modificarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $id);
                        if($data == "modificado"){
                            $msg = array('msg'=> 'Proveedor modificado con éxito', 'icono' => 'success');
                        }else{
                            $msg = array('msg'=> 'Error al Modificar el Proveedor', 'icono' => 'error');
                        }
                    }else{
                        $msg = array('msg'=> 'Ya existe un contacto con ese DNI', 'icono' => 'error');
                    }
                }
            }else{
                $ruc_mod = $_POST['ruc_modificar'];
                $verificar = $this->model->verificarContactoRUC($ruc);
                if($ruc == $ruc_mod){
                    $data = $this->model->modificarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $id);
                    if($data == "modificado"){
                        $msg = array('msg'=> 'Proveedor modificado con éxito', 'icono' => 'success');
                    }else{
                        $msg = array('msg'=> 'Error al Modificar el Proveedor', 'icono' => 'error');
                    }
                }else{
                    if(empty($verificar)){
                        $data = $this->model->modificarProveedor($dni, $correo, $nombres, $apellidoPeterno, $apellidoMaterno, $ruc, $razon_social, $direccion, $id_tipo_persona, $id);
                        if($data == "modificado"){
                            $msg = array('msg'=> 'Proveedor modificado con éxito', 'icono' => 'success');
                        }else{
                            $msg = array('msg'=> 'Error al Modificar el Proveedor', 'icono' => 'error');
                        }
                    }else{
                        $msg = array('msg'=> 'Ya existe un contacto con ese RUC', 'icono' => 'error');
                    }
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id)
    {
        $data = $this->model->editarProveedor($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function desactivar(int $id)
    {
        date_default_timezone_set('America/Lima');
        $fecha_cese = date("Y-m-d h:i:s");
        $data = $this->model->accionProveedor(0, $fecha_cese, $id);
        if($data == 1){
            $msg = array('msg'=> 'Proveedor desactivado', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al desactivar el Proveedor', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function activar(int $id)
    {
        $fecha_cese = "0000-00-00 00:00:00";
        $data = $this->model->accionProveedor(1, $fecha_cese, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Proveedor Activado', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al activar el Proveedor', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function eliminar(int $id)
    {
        $data = $this->model->eliminarProveedor($id);
        if($data == "eliminado"){
            $msg = array('msg'=> 'Proveedor eliminado con éxito', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al eliminar el Proveedor', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>