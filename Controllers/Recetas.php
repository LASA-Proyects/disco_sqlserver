<?php
class Recetas extends Controller{

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
        $verificar = $this->model->verificarPermiso($id_usuario, 'recetas');
        if(!empty($verificar) || $id_usuario == 1){
            $data = $this->model->getProductos();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function listar()
    {
        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            $elementId = 'cantidad_prod_' . $data[$i]['id'];
            $data[$i]['cantidad'] = '<input type="number" name="cantidad_prod[' . $data[$i]['id'] . ']" id="' . $elementId . '" class="form-control">';
            $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnAgregarReceta(' . $data[$i]['id'] . ', \'' . $elementId . '\');"><i class="fas fa-plus"></i></button>
            </div>';
        }
    
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarProdRec($id)
    {
        $data = $this->model->getDetalleRec($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function ingresar($id)
    {
        $data = $this->model->buscarProduct($id);
        $total = $data['precio_venta'];
        $id_receta = $_POST['id_producto'];
        $cantidad_prod = $_POST['cantidad_prod'];
        $comprobar = $this->model->consultarRecPed($id_receta, $id);
        $conslt_product = $this->model->getProductGen($id_receta);
        if(empty($comprobar)){
            $ingresar = $this->model->ingresarProdRect($id,$id_receta,$cantidad_prod,$total);
            if($ingresar == "ok"){
                $msg = "ok";
            }else{
                $msg = "Error al ingresar el producto";
            }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
        }else{
            $total_cantidad = $comprobar['cantidad'] + $cantidad_prod;
            $sub_total = $total_cantidad * $total;
            $data = $this->model->actualizarProdRect($id,$id_receta, $total_cantidad,$sub_total);
            if($data == "modificado"){
                $msg = "modificado";
            }else{
                $msg = "Error al modificado el producto";
            }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function DetRec($id)
    {
        $data = $this->model->deleteDetalleRec($id);
        if($data == 'ok'){
            $msg = 'ok';
        }else{
            $msg = 'error';
        }
        echo json_encode($msg);
        die();
    }

    
    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }
}
?>