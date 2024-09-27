<?php
class Familia extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 6, 15);
        if(!empty($verificar) || $id_usuario == 1){
            $data['lineas'] = $this->model->getLineas();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }
    public function listar()
    {
        $data = $this->model->getFamilia();
        for ($i=0; $i < count($data); $i++){
                $data[$i]['imagen'] = '<img class="img-thumbnail" src="'.base_url."Assets/img/".$data[$i]['foto'].'" width="56">';
                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                <button class="btn btn-warning btn-sm" type="button" onclick="btnEditarFamilia('.$data[$i]['id'].');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger btn-sm" type="button" onclick="btnEliminarFamilias('.$data[$i]['id'].');"><i class="fas fa-trash"></i></button>
                <div/>';
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $linea = $_POST['linea'];
        $id = $_POST['id'];
        $img = $_FILES['imagen'];
        $name = $img['name'];
        $tmpname = $img['tmp_name'];
        $fecha = date("YmdHis");
        if(empty($nombre)){
            $msg = "Todos los campos son obligatorios";
        }else{
            if(!empty($name)){
                $imgNombre = $fecha . '.jpg';
                $destino = "Assets/img/".$imgNombre;
            }else if(!empty($_POST['foto_actual']) && empty($name)){
                $imgNombre = $_POST['foto_actual'];
            }else{
                $imgNombre = 'default.jpg';
            }
            if($_POST['id'] == ""){
                    $data = $this->model->registrarFamilia($nombre, $imgNombre, $linea);
                    if($data == "ok"){
                        if(!empty($name)){
                            move_uploaded_file($tmpname, $destino);
                        }
                        $msg = "si";
                    }else if($data == "existe"){
                        $msg = "La Familia ya existe";
                    }else{
                        $msg = "Error al registrar la Familia";
                    }
                }else{
                    $imgDelete = $this->model->editarFamilia($id);
                    if($imgDelete['foto'] != 'default.jpg'){
                        if(file_exists("Assets/img/" . $imgDelete['foto'])){
                            unlink("Assets/img/" . $imgDelete['foto']);
                        }
                    }
                $data = $this->model->modificarFamilia($nombre, $imgNombre, $linea, $id);
                if($data == "modificado"){
                    if(!empty($name)){
                        move_uploaded_file($tmpname, $destino);
                    }
                    $msg = "modificado";
                }else{
                    $msg = "Error al modificado de la Familia";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar(int $id)
    {
        $data = $this->model->editarFamilia($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminar(int $id)
    {
        $data = $this->model->eliminarFamilia($id);
        if($data == "eliminado"){
            $msg = array('msg'=> 'Familia eliminada con éxito', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al eliminar la Familia', 'icono' => 'error');
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