<?php
class Usuarios extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 1, 1);
        if(!empty($verificar) || ($id_usuario == 1)){
            $data['tipo_usuarios'] = $this->model->getTipoUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }
    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
                if($data[$i]['id'] == 1){
                    $data[$i]['acciones'] = '<div>
                    <span class="badge badge-primary">Administrador</span>
                    <div/>';
                }else{
                    $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                    <a class="btn btn-info btn-sm" href="' . base_url . 'Usuarios/almacenes/' . $data[$i]['id'] . '"><i class="fas fa-warehouse"></i></a>
                    <a class="btn btn-dark btn-sm" href="' . base_url . 'Usuarios/permisos/' . $data[$i]['id'] . '"><i class="fas fa-key"></i></a>
                    <button class="btn btn-warning btn-sm" type="button" onclick="btnEditarUser(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary btn-sm" type="button" onclick="btnAsignarIngreso(' . $data[$i]['id'] . ');"><i class="fas fa-calendar"></i></button>
                    <button class="btn btn-success btn-sm" type="button" onclick="btnEstadoUser(' . $data[$i]['id'] . ');"><i class="fas fa-toggle-on"></i></button>
                    <button class="btn btn-danger btn-sm" type="button" onclick="btnEliminarUsuario(' . $data[$i]['id'] . ');"><i class="fas fa-trash"></i></button>
                    </div>';
                }
            }else{
                $data[$i]['estado'] = '<span class="badge badge-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div class="btn-group" style="justify-content: center;">
                <button class="btn btn-secondary btn-sm" type="button" onclick="btnActivarUser('.$data[$i]['id'].');"><i class="fas fa-toggle-off"></i></button>
                <div/>';
            }
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        $num_doc = $_POST['num_doc'];
        $usuario = $_POST['usuario'];
        $nombre = $_POST['nombre'];
        $tipo_usuario = $_POST['tipo_usuario'];
        $codigo_vendedor = $_POST['codigo_vendedor'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        $id = $_POST['id'];
        $hash = hash("SHA256", $clave);
        date_default_timezone_set('America/Lima');
        $ingresoFecha = date("Y-m-d h:i:s");
        $regexNombre = '/^[A-Za-z]+$/';
        if(empty($usuario) || empty($nombre) || empty($num_doc) || empty($codigo_vendedor)){
            $msg = array('msg'=> 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else if(!preg_match($regexNombre, $nombre)){
            $msg = array('msg' => 'El Nombre no puede tener caracteres especiales', 'icono' => 'warning');
        }else{
            if($_POST['id'] == ""){
                if($clave != $confirmar){
                    $msg = array('msg'=> 'Las contraseñas no coinciden', 'icono' => 'warning');
                }else{
                    $data = $this->model->registrarUsuario($num_doc, $usuario, $nombre, $hash, $tipo_usuario, $codigo_vendedor);
                    if($data['res'] == "ok"){
                        $msg = array('msg'=> 'Usuario registrado con éxito', 'icono' => 'success');
                        $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'usuarios');
                    }else if($data['res'] == "existe"){
                        $msg = array('msg'=> 'El Usuario ya se encuentra registrado', 'icono' => 'warning');
                    }else{
                        $msg = array('msg'=> 'Error al registrar el usuario', 'icono' => 'error');
                    }
                }
            }else{
                if($clave != $confirmar){
                    $msg = array('msg'=> 'Las contraseñas no coinciden', 'icono' => 'warning');
                }else{
                    $buscar_usuario = $this->model->editarUser($id);
                    $data = $this->model->modificarUsuario($num_doc, $usuario, $nombre, $hash, $tipo_usuario, $codigo_vendedor, $id);
                    if($data['res'] == "modificado"){
                        $msg = array('msg'=> 'Usuario modificado con éxito', 'icono' => 'success');
                        $this->model->registrarLog($_SESSION['id_usuario'],"num_doc={$buscar_usuario['num_doc']}, id={$buscar_usuario['id']}, usuario={$buscar_usuario['usuario']}, nombre={$buscar_usuario['nombre']}, clave={$buscar_usuario['clave']}, tipo_usuario={$buscar_usuario['tipo_usuario']}, codigo_vendedor={$buscar_usuario['codigo_vendedor']}".' | '.$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'usuarios');
                    }else{
                        $msg = array('msg'=> 'Error al modificar el usuario', 'icono' => 'error');
                    }
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrarFechasUsu()
    {
        $fecha_ini = $_POST['fecha_ini'];
        $hora_ini = $_POST['hora_ini'];
        $fecha_fin = $_POST['fecha_fin'];
        $hora_fin = $_POST['hora_fin'];
        date_default_timezone_set('America/Lima');
        $ingresoFecha = date("Y-m-d h:i:s");
        $id = $_POST['id_usu'];
        if(empty($fecha_ini) || empty($hora_ini) || empty($fecha_fin) || empty($hora_fin)){
            $msg = array('msg'=> 'Todos los campos son obligatorios', 'icono' => 'warning');
        }else{
            $data = $this->model->registrarFechasUsu($fecha_ini, $hora_ini, $fecha_fin, $hora_fin, $id);
            if($data == "ok"){;
                $msg = array('msg'=> 'Fechas registradas con éxito', 'icono' => 'success');
            }else{
                $msg = array('msg'=> 'Error al registrar el usuario', 'icono' => 'error');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarUser($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function desactivar(int $id)
    {
        $data = $this->model->accionUser(0, $id);
        date_default_timezone_set('America/Lima');
        $ingresoFecha = date("Y-m-d h:i:s");
        if($data['res'] == 1){
            $msg = array('msg'=> 'Usuario desactivado', 'icono' => 'success');
            $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'usuarios');
        }else{
            $msg = array('msg'=> 'Error al desactivar el usuario', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function activar(int $id)
    {
        $data = $this->model->accionUser(1, $id);
        date_default_timezone_set('America/Lima');
        $ingresoFecha = date("Y-m-d h:i:s");
        if($data['res'] == 1){
            $msg = array('msg'=> 'Usuario Activado', 'icono' => 'success');
            $this->model->registrarLog($_SESSION['id_usuario'],$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'usuarios');
        }else{
            $msg = array('msg'=> 'Error al activar el usuario', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function eliminar(int $id)
    {
        $buscar_usuario = $this->model->editarUser($id);
        $data = $this->model->eliminarUsuario($id);
        date_default_timezone_set('America/Lima');
        $ingresoFecha = date("Y-m-d h:i:s");
        if($data['res'] == "eliminado"){
            $msg = array('msg'=> 'Usuario eliminado con éxito', 'icono' => 'success');
            $this->model->registrarLog($_SESSION['id_usuario'], "id={$buscar_usuario['id']}, usuario={$buscar_usuario['usuario']}, nombre={$buscar_usuario['nombre']}, clave={$buscar_usuario['clave']}, tipo_usuario={$buscar_usuario['tipo_usuario']}".' | '.$data['sql'], $ingresoFecha, $_SESSION['id_almacen'], 'usuarios');
        }else{
            $msg = array('msg'=> 'Error al eliminar el Usuario', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function permisos($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $data['datos'] = $this->model->getPermisos();
        $permisos = $this->model->getDetallePermisos($id);
        $data['asignados'] = array();
        foreach ($permisos as $permiso) {
            $data['asignados'][$permiso['id_permiso']] = true;
        }
        $data['id_usuario'] = $id;
        $this->views->getView($this, "permisos", $data);
    }

    public function almacenes($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $data['datos'] = $this->model->getAlmacenes();
        $permisos = $this->model->getDetallePermisosAlmc($id);
        $data['asignados'] = array();
        foreach ($permisos as $permiso) {
            $data['asignados'][$permiso['id_almacen']] = true;
        }
        $data['id_usuario'] = $id;
        $this->views->getView($this, "almacenes", $data);
    }

    public function registrarPermisos()
    {
        $msg = '';
        $id_usuario= $_POST['id_usuario'];
        $eliminar = $this->model->eliminarPermisos($id_usuario);
        if($eliminar == "ok"){
            foreach($_POST['permisos'] as $id_permiso){
                $msg = $this->model->registrarPermisos($id_usuario, $id_permiso);
            }
            if($msg == "ok"){
                $msg = array('msg' => 'Permisos asignado con éxito', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Error al asignar los Permisos', 'icono' => 'error');
            }
        }else{
            $msg = array('msg' => 'Error al registrar los Permisos Anteriores', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }

    public function registrarPermisosAlmc()
    {
        $msg = '';
        $id_usuario= $_POST['id_usuario'];
        $eliminar = $this->model->eliminarPermisosAlmc($id_usuario);
        if($eliminar == "ok"){
            foreach($_POST['permisos_almc'] as $id_almacen){
                $msg = $this->model->registrarPermisosAlmc($id_usuario, $id_almacen);
            }
            if($msg == "ok"){
                $msg = array('msg' => 'Permisos asignado con éxito', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Error al asignar los Permisos', 'icono' => 'error');
            }
        }else{
            $msg = array('msg' => 'Error al registrar los Permisos Anteriores', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>