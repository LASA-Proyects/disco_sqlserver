<?php
require'C:/xampp/htdocs/disco2023/vendor/autoload.php';
require'C:/xampp/htdocs/disco2023/vendor/phpqrcode/qrlib.php';
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Pedidos extends Controller{

    public function __construct()
    {
        session_start();
        parent::__construct();
    }

    public function index($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 2);
        if(!empty($verificar) || $id_usuario == 1){
            //$data['familia'] = $this->model->getFamilia();
            $data['nuevoProductos'] = $this->model->getNuevosProductos();
            $data['arqueo_caja'] = $this->model->verificarArqueo();
            $data['usuarios'] = $this->model->obtenerUsuarios();
            $this->views->getView($this, "index", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function historial_pedidos_terminal($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'HISTORIAL VENTAS');
        if(!empty($verificar) || $id_usuario == 1){
            //$data['familia'] = $this->model->getFamilia();
            $data['nuevoProductos'] = $this->model->getNuevosProductos();
            $data['arqueo_caja'] = $this->model->verificarArqueo();
            $data['usuarios'] = $this->model->getUsuariosPedidoTerminal($id_usuario);
            $this->views->getView($this, "historial_pedidos_terminal", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos_analitico');
        }
    }

    public function historial_venta_entrada()
    {
        
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'HISTORIAL ENTRADAS');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "historial_venta_entrada");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function generar_Pedidos($id)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'generar_pedidos');
        if(!empty($verificar) || $id_usuario == 1){
            $data['productos'] = $this->model->getProductos();
            $data['id_usuario'] = $id;
            $this->views->getView($this, "generar_Pedidos", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function familias($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BEBIDAS VENTA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(1);
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosFam($id_almacen['id'], $id_familia, 1, 2, 3,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $this->views->getView($this, "familias", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function bebidas_combo($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BEBIDAS COMBO');
        if(!empty($verificar) || $id_usuario == 1){
            $id_linea = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_linea = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosLinea($id_linea);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(1);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getBebidasCombo($id_almacen['id'], 7, 1, 3,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_linea'] = 1;
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $this->views->getView($this, "bebidas_combo", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function bebidas_representante($datos)
    {
        
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BEBIDAS GASTOS REPRE');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 7;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(1);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->bebidas_cortesia($id_almacen['id'], $id_familia, 1, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "bebidas_representante", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function bebidas_cortesia($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BEBIDAS CORTESIA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 7;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(1);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->bebidas_cortesia($id_almacen['id'], $id_familia, 1, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "bebidas_cortesia", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function bebidas_descuento($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'BEBIDAS DESCUENTO');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 7;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(1);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->bebidas_cortesia($id_almacen['id'], $id_familia, 1, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['autorizador'] = $this->model->getUsuariosLogin();
            $data['trab_descs'] = $this->model->getUsuariosLoginDesc();
            $this->views->getView($this, "bebidas_descuento", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cortesia($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'cortesia');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 8;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosFam($id_almacen['id'], $id_familia, $desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $this->views->getView($this, "cortesia", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function repre($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'repre');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 8;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosFam($id_almacen['id'], $id_familia, $desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $this->views->getView($this, "repre", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function trabajador($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'trabajador');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 8;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosFam($id_almacen['id'], $id_familia, $desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/trabajador');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "trabajador", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocteleria_venta($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCTELERIA VENTA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 7;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(2);
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocteleria($id_almacen['id'], $id_familia, 2, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $this->views->getView($this, "cocteleria_venta", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocteleria_cortesia($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCTELERIA CORTESIA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(2);
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocteleria($id_almacen['id'], $id_familia, 2, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "cocteleria_cortesia", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocteleria_descuento($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCTELERIA DESCUENTO');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(2);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocteleria($id_almacen['id'], $id_familia, 2, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['autorizador'] = $this->model->getUsuariosLogin();
            $data['trab_descs'] = $this->model->getUsuariosLoginDesc();
            $this->views->getView($this, "cocteleria_descuento", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocteleria_representante($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCTELERIA GASTOS REPRE');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(2);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocteleria($id_almacen['id'], $id_familia, 2, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "cocteleria_representante", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }
    
    public function cocina_venta($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCINA VENTA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(3);
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocina($id_almacen['id'], $id_familia, 3, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $this->views->getView($this, "cocina_venta", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocina_cortesia($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCINA CORTESIA');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(3);
            $data['tipo_documentos'] = $this->model->getTipoDocumentos();
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocina($id_almacen['id'], $id_familia, 3, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "cocina_cortesia", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocina_descuento($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCINA DESCUENTO');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(3);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocina($id_almacen['id'], $id_familia, 3, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['autorizador'] = $this->model->getUsuariosLogin();
            $data['trab_descs'] = $this->model->getUsuariosLoginDesc();
            $this->views->getView($this, "cocina_descuento", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function cocina_representante($datos)
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermisoTerm($id_usuario, 'COCINA GASTOS REPRE');
        if(!empty($verificar) || $id_usuario == 1){
            $id_familia = 1;
            $page = 1;
            $array = explode(',',$datos);
            if(isset($array[0])){
                if(!empty($array[0])){
                    $id_familia = $array[0];
                }
            }

            if(isset($array[1])){
                if(!empty($array[1])){
                    $page = $array[1];
                }
            }

            $pagina = (empty($page)) ? 1 : $page;
            $porPagina = 6;
            $desde = ($pagina - 1) * $porPagina;
            $data['pagina'] = $pagina;
            $total = $this->model->TotalProductosFam($id_familia);
            $data['total'] = ceil($total['total'] / $porPagina);
            $data['familia'] = $this->model->getFamilia(3);
            $usuario = $this->model->getUsuarios($id_usuario);
            $id_almacen = $this->model->getAlmacen($_SESSION['almacen']);
            if(!empty($id_almacen)){
                $data['productos'] = $this->model->getProductosCocina($id_almacen['id'], $id_familia, 3, 2,$desde, $porPagina);
            }else{
                header('Location: '.base_url. 'Errors/permisos');
            }
            $data['id_familia'] = $id_familia;
            $data['trab_descs'] = $this->model->getUsuariosLogin();
            $this->views->getView($this, "cocina_representante", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function carrito()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'carrito');
        if(!empty($verificar) || $id_usuario == 1){
            $data['productos'] = $this->model->getProductos();
            $this->views->getView($this, "carrito", $data);
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }

    public function token_pedidos()
    {
        if(empty($_SESSION['activo'])){
            header("location: ".base_url);
        }
        $id_usuario = $_SESSION['id_usuario'];
        $verificar = $this->model->verificarPermiso($id_usuario, 'token_pedidos');
        if(!empty($verificar) || $id_usuario == 1){
            $this->views->getView($this, "token_pedidos");
        }else{
            header('Location: '.base_url. 'Errors/permisos');
        }
    }
    public function listarPed()
    {
        $datos = file_get_contents('php://input');
        $json = json_decode($datos, true);
        $array['productos'] = array();
        $total = 0.00;
        foreach ($json as $productos){
            $result = $this->model->getListaCarrito($productos['id_producto']);
            $data['id'] = $result['id'];
            $data['nombre'] = $result['descripcion'];
            $data['precio'] = $result['precio_venta'];
            $data['cantidad'] = $productos['cantidad'];
            $sub_total = $result['precio_venta']*$productos['cantidad'];
            if($result['afecta_igv'] == 0){
                $data['igv'] = '<span class="badge badge-danger">No</span>';
                $data['igv_dato'] = 0;
                $data['sub_total'] = number_format($sub_total, 2);
            }else{
                $sub_total = $sub_total + $sub_total*0.18;
                $data['igv'] = '<span class="badge badge-success">Si</span>';
                $data['igv_dato'] = 1;
                $data['sub_total'] = number_format($sub_total, 2);
            }
            $total += $sub_total;
            array_push($array['productos'],$data);
        }
        $array['total'] = number_format($total, 2);
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarTokensPedidos()
    {
        $data = $this->model->getTokensPedidos();
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge badge-success">Activo</span>';
            }else{
                $data[$i]['estado'] = '<span class="badge badge-warning">Utilizado</span>';
            }
            
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listarHistorialEntradas()
    {
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->listarHistorialEntradas($id_usuario);
        for ($i=0; $i < count($data); $i++){
            if ($data[$i]['estado'] == 0) {
                $data[$i]['estado'] = '<span class="badge badge-success">PROCESADO</span>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die(); 
    }

    public function DetPed($id)
    {
        $data = $this->model->deleteDetallePed($id);
        if($data == 'ok'){
            $msg = 'ok';
        }else{
            $msg = 'error';
        }
        echo json_encode($msg);
        die();
    }

    public function buscarPedidosFecha($value)
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $id_usuario = $_SESSION['id_usuario'];
        if ($value == 1) {
            $valor_pedido = 0;
        } elseif ($value == 2) {
            $valor_pedido = 2;
        }
        if($id_usu == -1){
            $data = $this->model->ObtenerPedidosGen($valor_pedido, $desde, $hasta);
        }else{
            $data = $this->model->ObtenerPedidos($valor_pedido, $id_usu, $desde, $hasta);
        }    

        for ($i = 0; $i < count($data); $i++) {
            if($value == 1){
                if($id_usuario == 1){
                    if($data[$i]['tipo_pedido'] == 1){
                        $data[$i]['estado'] = '<span class="badge badge-info">PROCESADO</span>';
                        /*<button class="btn btn-info btn-sm" type="button" onclick="btnEditarTipoPago(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>*/
                        $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                        <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularPedido(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                        <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <button class="btn btn-primary btn-sm" type="button" onclick="detalleEstadoSunat(' . $data[$i]['Fcfmanumero'] . ');"><i class="fas fa-eye"></i></button>
                        <div/>';
                    }else{
                        $data[$i]['estado'] = '<span class="badge badge-info">PROCESADO</span>';
                        $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                        <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularPedido(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                        <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['Fcfmanumero'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <div/>';
                    }
                }else{
                    if($data[$i]['tipo_pedido'] == 1){
                        $data[$i]['estado'] = '<span class="badge badge-info">PROCESADO</span>';
                        foreach ($_SESSION['permisos'] as $permiso) {
                            if ($permiso['id_permiso_hijo'] != 16 && isset($permiso['id_permiso_hijo'])== 17) {
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
    
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <button class="btn btn-primary btn-sm" type="button" onclick="detalleEstadoSunat(' . $data[$i]['Fcfmanumero'] . ');"><i class="fas fa-e"></i></button>
                                <div/>';
                            }else if($permiso['id_permiso_hijo'] != 17 && isset($permiso['id_permiso_hijo'])== 16){
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularPedido(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <button class="btn btn-primary btn-sm" type="button" onclick="detalleEstadoSunat(' . $data[$i]['Fcfmanumero'] . ');"><i class="fas fa-eye"></i></button>
                                <div/>';
                            }else if($permiso['id_permiso_hijo'] == 17 && $permiso['id_permiso_hijo'] == 16){
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularPedido(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                                <button class="btn btn-info btn-sm" type="button" onclick="btnEditarTipoPago(' . $data[$i]['Fcfmanumero'] . ');"><i class="fas fa-edit"></i></button>
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <button class="btn btn-primary btn-sm" type="button" onclick="detalleEstadoSunat(' . $data[$i]['Fcfmanumero'] . ');"><i class="fas fa-eye"></i></button>
                                <div/>';
                            }else{
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['Fcfmanumero'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <div/>';
                            }
                        }
                    }else{
                        $data[$i]['estado'] = '<span class="badge badge-info">PROCESADO</span>';
                        foreach ($_SESSION['permisos'] as $permiso) {
                            if($permiso['id_permiso_hijo'] == 17 && isset($permiso['id_permiso_hijo'])== 16){
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                <button class="btn btn-warning btn-sm" type="button" onclick="btnAnularPedido(' . $data[$i]['id'] . ');"><i class="fas fa-ban"></i></button>
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <div/>';
                            }else{
                                $data[$i]['acciones'] = '<div class="btn-group" style="display: flex; justify-content: center;">
                                <a class="btn btn-danger btn-sm" type="button" href="'.base_url."Pedidos/generarPdfPedido/".$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <div/>';
                            }
                        }
                    }
                }
            }else{
                $desde = $_POST['desde'];
                $hasta = $_POST['hasta'];
                $id_usu = $_POST['usuario'];
                if($id_usuario == 1){
                    $data = $this->model->ObtenerPedidosAdmin($valor_pedido, $desde, $hasta);
                }else{
                    $data = $this->model->ObtenerPedidos($valor_pedido, $id_usu, $desde, $hasta);
                }
                for ($i = 0; $i < count($data); $i++) {
                    if($data[$i]['estado'] == 2){
                        $data[$i]['estado'] = '<span class="badge badge-warning">ANULADO</span>';
                        $data[$i]['acciones'] = '';
                    }
                }
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function obtenerPedidosAnulados()
    {
        $desde = isset($_GET['desde']) ? $_GET['desde'] : null;
        $hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;
        $id_usuario = $_SESSION['id_usuario'];
        if($id_usuario == 1){
            $data = $this->model->ObtenerPedidosAdmin(2, $desde, $hasta);
        }else{
            $data = $this->model->ObtenerPedidos(2, $id_usuario, $desde, $hasta);
        }
        for ($i = 0; $i < count($data); $i++) {
            if($data[$i]['estado'] == 2){
                $data[$i]['estado'] = '<span class="badge badge-warning">ANULADO</span>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    public function buscarCodigoPedido($codigoPed)
    {
        $data = $this->model->getProdCodPedido($codigoPed);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($idPedido)
    {
        $data = $this->model->editarPedidoFact($idPedido);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultarProductos($idProductos)
    {
        $data['productos'] = $this->model->verPedido($idProductos);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function mostrarStockProducto($idProducto)
    {
        $data['productosStock'] = $this->model->mostrarStockProducto($idProducto);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function cancelarPedido($idPedido)
    {
        $data = $this->model->cancelarPedido(2,$idPedido);
        if($data == "eliminado"){
            $this->model->eliminarPedido($idPedido);
            $msg = array('msg'=> 'Pedido Cancelado', 'icono' => 'success');
        }else{
            $msg = array('msg'=> 'Error al Cancelar Pedido', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarSCB($parametro)
    {
        $data = $this->model->buscarSCB($parametro, $_SESSION['almacen']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    /*function generarArchivo($ruta, $nombreArchivo, $extension, $contenido) {
        $rutaCompleta = $ruta . DIRECTORY_SEPARATOR . $nombreArchivo . $extension;
        file_put_contents($rutaCompleta, $contenido);
    }*/

    function convertirNumeroALetras($numero) {
        $numeros = array(
            'CERO', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'
        );
    
        $decenas = array('', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA');
    
        $centenas = array('', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS');
    
        $centavos = floor(($numero * 100) % 100);
        $enteros = floor($numero);
    
        $cadena = '';
    
        if ($enteros == 0) {
            $cadena = $numeros[0];
        } elseif ($enteros == 1) {
            $cadena = $numeros[1];
        } elseif ($enteros >= 10 && $enteros <= 99) {
            $unidad = $enteros % 10;
            $decena = floor($enteros / 10);
    
            if ($unidad == 0) {
                $cadena = $decenas[$decena];
            } else {
                $cadena = $decenas[$decena] . ' Y ' . $numeros[$unidad];
            }
        } elseif ($enteros == 100) {
            $cadena = 'CIEN';
        } elseif ($enteros > 100 && $enteros <= 999) {
            $unidad = $enteros % 10;
            $decena = floor(($enteros % 100) / 10);
            $centena = floor($enteros / 100);
    
            if ($decena == 0 && $unidad == 0) {
                $cadena = $centenas[$centena];
            } elseif ($decena == 0) {
                $cadena = $centenas[$centena] . ' ' . $numeros[$unidad];
            } else {
                $cadena = $centenas[$centena] . ' ' . $decenas[$decena] . ' Y ' . $numeros[$unidad];
            }
        }
    
        $cadena .= ' Y ' . $centavos . '/100 SOLES';
    
        return $cadena;
    }

    public function registrarPedidoFacturado()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $datos_pedido = $data['datos_pedido'];
        $lista_carrito = $data['listaCarrito'];
        $id_almacen = $_SESSION['almacen'];
        date_default_timezone_set('America/Lima');
        $fecha_actual = date("Y-m-d");
        $id_usuario = $_SESSION['id_usuario'];
        $consultarCodigoVnd = $this->model->consultarCodigoVnd($id_usuario);
        $tipo_pedido = $datos_pedido['tipo_pedido'];
                    if($tipo_pedido == 1){
                        $id = $datos_pedido['id_pedido'];
                        if($id == ""){
                            if($datos_pedido['codigo_vendedor'] != ""){
                                if($datos_pedido['codigo_vendedor'] == $consultarCodigoVnd['codigo_vendedor']){
                                    $efectivo = $datos_pedido['efectivo'];
                                    $visa = $datos_pedido['visa'];
                                    $master = $datos_pedido['master_c'];
                                    $diners = $datos_pedido['diners'];
                                    $a_express = $datos_pedido['a_express'];
                                    $yape = $datos_pedido['yape'];
                                    $plin = $datos_pedido['plin'];
                                    $izipay = $datos_pedido['izipay'];
                                    $niubiz = $datos_pedido['niubiz'];
                                    $pos = $datos_pedido['pos'];
                                    $transferencia = $datos_pedido['transferencia'];
                                    $op_visa = $datos_pedido['op_visa'];
                                    $op_mast = $datos_pedido['op_mast'];
                                    $op_diners = $datos_pedido['op_diners'];
                                    $op_express = $datos_pedido['op_express'];
                                    $op_yape = $datos_pedido['op_yape'];
                                    $op_plin = $datos_pedido['op_plin'];
                                    $op_izipay = $datos_pedido['op_izipay'];
                                    $op_niubiz = $datos_pedido['op_niubiz'];
                                    $op_pos = $datos_pedido['op_pos'];
                                    $op_transf = $datos_pedido['op_transf'];
                                    $efectivo = floatval($efectivo);
                                    $total_pedido = $datos_pedido['total_pedido'];
                                    $igv_pedido = $datos_pedido['total_igv'];
                                    $base_pedido = $datos_pedido['total_base'];
                                    $tipo_documento = !empty($datos_pedido['t_documento']) ? (int)$datos_pedido['t_documento'] : null;
                                    $serie = $datos_pedido['serie'];
                                    $fecha_actual = $datos_pedido['fecha'];
                                    $correlativo = $datos_pedido['correlativo'];
                                    $parametro = $datos_pedido['parametro'];
                                    $dni = !empty($datos_pedido['dni']) ? (int)$datos_pedido['dni'] : null;
                                    $correo = !empty($datos_pedido['correo']) ? $datos_pedido['correo'] : null;
                                    $nombres = $datos_pedido['nombres'];
                                    $apellido_parterno = $datos_pedido['apellido_paterno'];
                                    $apellido_materno = $datos_pedido['apellido_materno'];
                                    $ruc = $datos_pedido['ruc'];
                                    $razon_social = $datos_pedido['razon_social'];
                                    $direccion = $datos_pedido['direccion'];
                                    $propina = $datos_pedido['propina'];
                                    try {
                                        $id_pedido_registrado = $this->model->registrarPedido($id_usuario, $id_almacen, NULL, $base_pedido, $igv_pedido, $total_pedido, $fecha_actual, 1);
                                        if($datos_pedido['efectivo'] != "" || $datos_pedido['transferencia'] != ""){
                                            $id_banco_ini = $this->model->buscarBanco($id_almacen);
                                            $this->model->registrarIngresoCaja($id_usuario, $id_almacen, $id_banco_ini['id'], NULL, 9999, NULL, NULL, NULL, NULL, NULL, $total_pedido, $total_pedido, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $fecha_actual, 1);
                                        }
                                        $ruta = $this->model->getRuta();
                                        $hora_cabecera = date("H:i:s");
                                        $ruc_empresa = $this->model->getRucEmpresa();
                                        $correlativoConCeros = str_pad(intval($correlativo), 7, '0', STR_PAD_LEFT);
                                        $extension = '.cab';
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                    $productosTipo3 = array();
                                    $contenido_productos_boleta = '';
                                            
                                    foreach ($lista_carrito as $producto) {
                                        $temp = $this->model->getListaPedidos($producto['id_producto']);
                                        $validar_igv = $this->model->validarIGV($temp['id']);
                                        
                                        if ($validar_igv['afecta_igv'] == 0) {
                                            $igv_prod = 0.00;
                                            $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                            $base_prod = $total_prod;
                                            try {
                                                $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_prod);
                                            } catch (PDOException $e) {
                                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                                echo json_encode($msg);
                                                die();
                                            }
                                            if ($temp['id_tarticulo'] == 3) {
                                                $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                            }
                                            
                                            $fila_contenido_boleta = 'NIU'.'|'.$producto['cantidad'].'|'.$temp['codigo'].'|'.'-'.'|'.$temp['descripcion'].'|'.$temp['precio_venta'].'|'.'0.00'.'|'.'1003'.'|'.'0.00'.'|'.$temp['precio_venta'].'|'.'IGV'.'|'.'VAT'.'|'.'20'.'|'.'0.00'.'|'.'-'.'|'.'0.00'.'|'.'0.00'.'|'.''.'|'.''.'|'.''.'|'.'0'.'|'.'-'.'|'.'0.00'.'|'.'0.00'.'|'.''.'|'.''.'|'.'0'.'|'.'-'.'|'.'0.00'.'|'.'0'.'|'.''.'|'.''.'|'.'0.00'.'|'.$temp['precio_venta'].'|'.$temp['precio_venta'].'|'.'0';
                                            $contenido_productos_boleta .= $fila_contenido_boleta . PHP_EOL;
                                        } else {
                                            $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                            $igv_prod = $total_prod * 0.18;
                                            $base_prod = $total_prod;
                                            $total_final = $base_prod + $igv_prod;
                                            try {
                                            $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_final);
                                            } catch (PDOException $e) {
                                                $msg = array('msg' => 'Error al registrar el Detalle del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                                echo json_encode($msg);
                                                die();
                                            }
                                            if ($temp['id_tarticulo'] == 3) {
                                                $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                            }
                                            
                                            $fila_contenido_boleta = 'NIU'.'|'.$producto['cantidad'].'|'.$temp['codigo'].'|'.'-'.'|'.$temp['descripcion'].'|'.$temp['precio_venta'].'|'.'0.00'.'|'.'1003'.'|'.'0.00'.'|'.$temp['precio_venta'].'|'.'IGV'.'|'.'VAT'.'|'.'20'.'|'.'0.00'.'|'.'-'.'|'.'0.00'.'|'.'0.00'.'|'.''.'|'.''.'|'.''.'|'.'0'.'|'.'-'.'|'.'0.00'.'|'.'0.00'.'|'.''.'|'.''.'|'.'0'.'|'.'-'.'|'.'0.00'.'|'.'0'.'|'.''.'|'.''.'|'.'0.00'.'|'.$temp['precio_venta'].'|'.$temp['precio_venta'].'|'.'0';
                                            $contenido_productos_boleta .= $fila_contenido_boleta . PHP_EOL;
                                        }
                                    }
                                    
                                    foreach ($productosTipo3 as $combo) {
                                        $cantidad_combo = $combo['cantidad_combo'];
                                        $datos = $this->model->getProdRect($combo['producto']['id']);
                                        
                                        foreach ($datos as $receta) {
                                            $validar_igv = $this->model->validarIGV($receta['id_producto']);
                                            
                                            if ($validar_igv['afecta_igv'] == 0) {
                                                $igv_prod_rec = 0.00;
                                                $cant = $receta['cantidad'] * $cantidad_combo;
                                                $total_prod_rec = $cant * $receta['total'];
                                                $base_prod_rec = $total_prod_rec;
                                                try {
                                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_prod_rec, $combo['producto']['id']);
                                                } catch (PDOException $e) {
                                                    $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                                    echo json_encode($msg);
                                                    die();
                                                }
                                            } else {
                                                $cant = $receta['cantidad'] * $cantidad_combo;
                                                $total_prod_rec = $cant * $receta['total'];
                                                $igv_prod_rec = $total_prod_rec * 0.18;
                                                $base_prod_rec = $total_prod_rec;
                                                $total_final_rec = $base_prod_rec + $igv_prod_rec;
                                                try{
                                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_final_rec, $combo['producto']['id']);
                                                } catch (PDOException $e) {
                                                    $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                                    echo json_encode($msg);
                                                    die();
                                                }
                                            }
                                        }
                                    }
                
                                    if($serie != '' && $correlativo != ''){
                                        $verificar_corr = $this->model->buscarSCB($parametro, $_SESSION['almacen']);
                                        if($verificar_corr['correlativo'] === $correlativo){
                                            $data = $this->model->actualizarEstadoPedido($tipo_documento, $serie, $correlativo, $dni, $nombres, $apellido_parterno, $apellido_materno, $ruc, $razon_social, $direccion, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $pos, $transferencia, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $op_pos, $op_transf, 0, $propina, $id_pedido_registrado);
                                            $correlativoAum = str_pad(intval($correlativo) + 1, strlen($correlativo), '0', STR_PAD_LEFT);
                                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $_SESSION['almacen']);
                                            if ($tipo_documento == 1 && !empty($dni)) {
                                                $verificarDNI = $this->model->verificarDNI($dni);
                                                if (empty($verificarDNI)) {
                                                    date_default_timezone_set('America/Lima');
                                                    $fecha_alta = date("Y-m-d h:i:s");
                                                    $this->model->registrarContactoDNI($dni, $correo, $nombres, $apellido_parterno, $apellido_materno, 1, $fecha_alta);
                                                    //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                    $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                                }
                                            } else if ($tipo_documento == 2 && !empty($ruc)) {
                                                $verificarRUC = $this->model->verificarRUC($ruc);
                                                if (empty($verificarRUC)){
                                                    date_default_timezone_set('America/Lima');
                                                    $fecha_alta = date("Y-m-d h:i:s");
                                                    $this->model->registrarContactoRUC($ruc, $correo, $razon_social, $direccion, 2, $fecha_alta);
                                                    //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                    $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                                }
                                            }else{
                                                //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                            }
                                            //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);     
                                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);       
                                        }else if($verificar_corr['correlativo'] != $correlativo && $serie != ''){
                                            $nuevo_correlativo = $verificar_corr['correlativo'];
                                            $data = $this->model->actualizarEstadoPedido($tipo_documento, $serie, $nuevo_correlativo, $dni, $nombres, $apellido_parterno, $apellido_materno, $ruc, $razon_social, $direccion, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $pos, $transferencia, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $op_pos, $op_transf, 0, $propina, $id_pedido_registrado);
                                            $correlativoAum = str_pad(intval($nuevo_correlativo) + 1, strlen($nuevo_correlativo), '0', STR_PAD_LEFT);
                                            $this->model->actualizarSerieVnt($parametro, $correlativoAum, $_SESSION['almacen']);
                                            if ($tipo_documento == 1 && !empty($dni)){
                                                $verificarDNI = $this->model->verificarDNI($dni);
                                                if (empty($verificarDNI)) {
                                                    date_default_timezone_set('America/Lima');
                                                    $fecha_alta = date("Y-m-d h:i:s");
                                                    $this->model->registrarContactoDNI($dni, $correo, $nombres, $apellido_parterno, $apellido_materno, 1, $fecha_alta);
                                                    //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                    $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                                }
                                            }else if($tipo_documento == 2 && !empty($ruc)){
                                                $verificarRUC = $this->model->verificarRUC($ruc);
                                                if (empty($verificarRUC)){
                                                    date_default_timezone_set('America/Lima');
                                                    $fecha_alta = date("Y-m-d h:i:s");
                                                    $contacto = $this->model->registrarContactoRUC($ruc, $correo, $razon_social, $direccion, 2, $fecha_alta);
                                                    //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                    $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                                }
                                            }else{
                                                //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                                $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                            }
                                            //$msg = array('msg'=> 'ok', 'serie' => $serie, 'correlativo' => $correlativo, 'tipo_documento' => $tipo_documento);
                                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                        }else{
                                            //$msg = array('msg'=> 'Error al registrar el Pedido', 'icono' => 'error');
                                            $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                        }
                                    }else{
                                        $serie_null = null;
                                        $correlativo_null = null;
                                        $data = $this->model->actualizarEstadoPedido($tipo_documento, $serie_null, $correlativo_null, $dni, $nombres, $apellido_parterno, $apellido_materno, $ruc, $razon_social, $direccion, $efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $pos, $transferencia, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $op_pos, $op_transf, 0, $propina, $id_pedido_registrado);
                                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado,'tipo_documento' => $tipo_documento);
                                    }
                                }else{
                                    $msg = array('msg'=> 'El Código de Vendedor no Coincide', 'icon' => 'error');
                                }
                            }else{
                                $msg = array('msg'=> 'Por favor, Ingresar Código de Vendedor', 'icon' => 'error');
                            }
                        }else{
                            $efectivo = $datos_pedido['efectivo'];
                            $visa = $datos_pedido['visa'];
                            $master = $datos_pedido['master_c'];
                            $diners = $datos_pedido['diners'];
                            $a_express = $datos_pedido['a_express'];
                            $yape = $datos_pedido['yape'];
                            $plin = $datos_pedido['plin'];
                            $izipay = $datos_pedido['izipay'];
                            $niubiz = $datos_pedido['niubiz'];
                            $op_visa = $datos_pedido['op_visa'];
                            $op_mast = $datos_pedido['op_mast'];
                            $op_diners = $datos_pedido['op_diners'];
                            $op_express = $datos_pedido['op_express'];
                            $op_yape = $datos_pedido['op_yape'];
                            $op_plin = $datos_pedido['op_plin'];
                            $op_izipay = $datos_pedido['op_izipay'];
                            $op_niubiz = $datos_pedido['op_niubiz'];
                            $efectivo = floatval($efectivo);
                            $data = $this->model->EditarPedido($efectivo, $visa, $master, $diners, $a_express, $yape, $plin, $izipay, $niubiz, $op_visa, $op_mast, $op_diners, $op_express, $op_yape, $op_plin, $op_izipay, $op_niubiz, $id);
                            $msg = 'ok';
                            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                            die();
                        }
                    }else if($tipo_pedido == 2){
                        $igv_pedido = 0.00;
                        $glosa = $datos_pedido["glosa"];
                        $fecha_actual = $datos_pedido['fecha_registro'];
                        $autorizado = $datos_pedido["autorizado"];
                        try {
                            $id_pedido_registrado = $this->model->registrarPedidoT2($id_usuario, $id_usuario, NULL, $datos_pedido['total'], $igv_pedido, $datos_pedido['total'], $fecha_actual,0,$glosa, $autorizado,2,NULL);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Pedido Cortesia: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $productosTipo3 = array();
                        foreach ($lista_carrito as $producto) {
                            $temp = $this->model->getListaPedidos($producto['id_producto']);
                            $validar_igv = $this->model->validarIGV($temp['id']);
                            if($validar_igv['afecta_igv'] == 0){
                                $igv_prod = 0.00;
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $base_prod = $total_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_prod);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Cortesia: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if($temp['id_tarticulo'] == 3){
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }else{
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $igv_prod = $total_prod*0.18;
                                $base_prod = $total_prod;
                                $total_final = $base_prod + $igv_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_final);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Cortesia: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if ($temp['id_tarticulo'] == 3) {
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }
                        }
                        
                        foreach ($productosTipo3 as $combo) {
                            $cantidad_combo = $combo['cantidad_combo'];
                            $datos = $this->model->getProdRect($combo['producto']['id']);
                            
                            foreach ($datos as $receta) {
                                $validar_igv = $this->model->validarIGV($receta['id_producto']);
                                
                                if ($validar_igv['afecta_igv'] == 0) {
                                    $igv_prod_rec = 0.00;
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $base_prod_rec = $total_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_prod_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Cortesia: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                } else {
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $igv_prod_rec = $total_prod_rec * 0.18;
                                    $base_prod_rec = $total_prod_rec;
                                    $total_final_rec = $base_prod_rec + $igv_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_final_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Cortesia: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                }
                            }
                        }
                        $serie_cortesia = $this->model->buscarSCB('O', $_SESSION['almacen']);
                        $data = $this->model->actualizarEstadoPedido(NULL, $serie_cortesia['serie'], $serie_cortesia['correlativo'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, $id_pedido_registrado);
                        $correlativoAum = str_pad(intval($serie_cortesia['correlativo']) + 1, strlen($serie_cortesia['correlativo']), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt('O', $correlativoAum, $_SESSION['almacen']);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado);
        
                    }else if($tipo_pedido == 3){
                        $consumo_artista = isset($datos_pedido['consumo_artista']) ? 1 : 0;
                        $igv_pedido = 0.00;
                        $fecha_actual = $datos_pedido['fecha_registro'];
                        $glosa = $datos_pedido["glosa"];
                        $autorizado = $datos_pedido["autorizado"];
                        try {
                            $id_pedido_registrado = $this->model->registrarPedidoT2($id_usuario, $id_almacen, NULL, $datos_pedido['total'], $igv_pedido, $datos_pedido['total'], $fecha_actual,0,$glosa, $autorizado,3,$consumo_artista);
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Pedido Representante: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $productosTipo3 = array();
                        foreach ($lista_carrito as $producto) {
                            $temp = $this->model->getListaPedidos($producto['id_producto']);
                            $validar_igv = $this->model->validarIGV($temp['id']);
                            if($validar_igv['afecta_igv'] == 0){
                                $igv_prod = 0.00;
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $base_prod = $total_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_prod);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Representante: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if ($temp['id_tarticulo'] == 3) {
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }else{
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $igv_prod = $total_prod*0.18;
                                $base_prod = $total_prod;
                                $total_final = $base_prod + $igv_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_final);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Representante: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if ($temp['id_tarticulo'] == 3) {
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }
                        }
                        
                        foreach ($productosTipo3 as $combo) {
                            $cantidad_combo = $combo['cantidad_combo'];
                            $datos = $this->model->getProdRect($combo['producto']['id']);
                            
                            foreach ($datos as $receta) {
                                $validar_igv = $this->model->validarIGV($receta['id_producto']);
                                
                                if ($validar_igv['afecta_igv'] == 0) {
                                    $igv_prod_rec = 0.00;
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $base_prod_rec = $total_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_prod_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Representante: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                } else {
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $igv_prod_rec = $total_prod_rec * 0.18;
                                    $base_prod_rec = $total_prod_rec;
                                    $total_final_rec = $base_prod_rec + $igv_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_final_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Representante: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                }
                            }
                        }
                        $serie_cortesia = $this->model->buscarSCB('O', $_SESSION['almacen']);
                        $data = $this->model->actualizarEstadoPedido(NULL, $serie_cortesia['serie'], $serie_cortesia['correlativo'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, $id_pedido_registrado);
                        $correlativoAum = str_pad(intval($serie_cortesia['correlativo']) + 1, strlen($serie_cortesia['correlativo']), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt('O', $correlativoAum, $_SESSION['almacen']);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado);
                    }else{
                        $igv_pedido = 0.00;
                        $fecha_actual = $datos_pedido['fecha_registro'];
                        $glosa = $datos_pedido["glosa"];
                        $autorizado = $datos_pedido["autorizado"];
                        $fecha_desc = $datos_pedido['fecha_desc'];
                        $trab_desc = $datos_pedido['trab_desc'];
                        try {
                            $id_pedido_registrado = $this->model->registrarPedidoTipo($id_usuario, $id_almacen, NULL, $datos_pedido['total'], $igv_pedido, $datos_pedido['total'], $fecha_actual,0,$glosa, $autorizado,$fecha_desc ,$trab_desc, 4); 
                        } catch (PDOException $e) {
                            $msg = array('msg' => 'Error al registrar el Pedido Desc. Trabajador: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                            echo json_encode($msg);
                            die();
                        }
                        $productosTipo3 = array();
                        foreach ($lista_carrito as $producto) {
                            $temp = $this->model->getListaPedidos($producto['id_producto']);
                            $validar_igv = $this->model->validarIGV($temp['id']);
                            if($validar_igv['afecta_igv'] == 0){
                                $igv_prod = 0.00;
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $base_prod = $total_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_prod);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Desc. Trabajador: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if ($temp['id_tarticulo'] == 3) {
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }else{
                                $total_prod = $temp['precio_venta'] * $producto['cantidad'];
                                $igv_prod = $total_prod*0.18;
                                $base_prod = $total_prod;
                                $total_final = $base_prod + $igv_prod;
                                try {
                                    $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $temp['id'], $id_almacen, $temp['precio_venta'], $producto['cantidad'], $base_prod, $igv_prod, $total_final);
                                } catch (PDOException $e) {
                                    $msg = array('msg' => 'Error al registrar el Detalle del Pedido Desc. Trabajador: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                    echo json_encode($msg);
                                    die();
                                }
                                if ($temp['id_tarticulo'] == 3) {
                                    $productosTipo3[] = array('producto' => $temp, 'cantidad_combo' => $producto['cantidad']);
                                }
                            }
                        }
                        
                        foreach ($productosTipo3 as $combo) {
                            $cantidad_combo = $combo['cantidad_combo'];
                            $datos = $this->model->getProdRect($combo['producto']['id']);
                            
                            foreach ($datos as $receta) {
                                $validar_igv = $this->model->validarIGV($receta['id_producto']);
                                
                                if ($validar_igv['afecta_igv'] == 0) {
                                    $igv_prod_rec = 0.00;
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $base_prod_rec = $total_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_prod_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Desc. Trabajador: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                } else {
                                    $cant = $receta['cantidad'] * $cantidad_combo;
                                    $total_prod_rec = $cant * $receta['total'];
                                    $igv_prod_rec = $total_prod_rec * 0.18;
                                    $base_prod_rec = $total_prod_rec;
                                    $total_final_rec = $base_prod_rec + $igv_prod_rec;
                                    try {
                                        $this->model->registrarDetallePedido($id_pedido_registrado, $id_usuario, $receta['id_producto'], $id_almacen, $receta['total'], $cant, $base_prod_rec, $igv_prod_rec, $total_final_rec, $combo['producto']['id']);
                                    } catch (PDOException $e) {
                                        $msg = array('msg' => 'Error al registrar el Detalle Receta del Pedido Desc. Trabajador: ' . $e->getMessage(), 'icon' => 'error', 'error'=> 'error');
                                        echo json_encode($msg);
                                        die();
                                    }
                                }
                            }
                        }
                        $serie_cortesia = $this->model->buscarSCB('O', $_SESSION['almacen']);
                        $data = $this->model->actualizarEstadoPedido(NULL, $serie_cortesia['serie'], $serie_cortesia['correlativo'], NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, $id_pedido_registrado);
                        $correlativoAum = str_pad(intval($serie_cortesia['correlativo']) + 1, strlen($serie_cortesia['correlativo']), '0', STR_PAD_LEFT);
                        $this->model->actualizarSerieVnt('O', $correlativoAum, $_SESSION['almacen']);
                        $msg = array('msg'=> 'ok', 'id_pedido' => $id_pedido_registrado);
                    }
            echo json_encode($msg, JSON_UNESCAPED_UNICODE);
            die();
    }

    public function actualizarPedido($idPedido)
    {
        $data = $this->model->getPedidosId($idPedido);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function buscarSolicitante($op_cort)
    {
        $data = $this->model->verificarTokenPedido($op_cort);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function generarTokenPedido()
    {
        $fecha_actual = date("Y-m-d H:i:s");
        if($_POST['fecha_caduca'] == ''){
            $fecha_caduca = date("Y-m-d H:i:s");
        }else{
            $fecha_caduca = $_POST['fecha_caduca'];
        }
        $token_pedido = $this->generarToken(7);
        $id_usuario = $_SESSION['id_usuario'];
        $solicitante = $_POST['nombre_sol'];
        $id_tipo_token = $_POST['tipo_token'];
        if($id_tipo_token == 1){
            $data = $this->model->GenerarTokenPedido($id_usuario, $solicitante, $token_pedido, $id_tipo_token, 1, $fecha_actual, $fecha_caduca);
        }else{
            $cantidad = $_POST['cantidad'];
            $data = $this->model->GenerarTokenPedido($id_usuario, $solicitante, $token_pedido, $id_tipo_token, $cantidad, $fecha_actual, $fecha_caduca);
        }
        if($data){
            $msg = array('msg' => 'Token generado con éxito', 'icon' => 'success');
        }else{
            $msg = array('msg' => 'Error al crear el Token', 'icon' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    
    private function generarToken($longitud)
    {
        $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    
        $token = "";
        $max_index = strlen($caracteres) - 1;
        for ($i = 0; $i < $longitud; $i++) {
            $token .= $caracteres[rand(0, $max_index)];
        }
    
        return $token;
    }

    public function generarPdfPedido($id_pedido/*$datos*/)
    {
        /*list($serie, $correlativo) = explode('-', $datos);*/
        $empresa = $this->model->getEmpresa();
        //$pedido = $this->model->getPedido($serie, $correlativo);
        $pedido = $this->model->getPedido($id_pedido);
        $detalle_pedido = $this->model->getDetallePedido($pedido[0]['id']);
        $usuario_compra = $this->model->getUsuario_Compra($pedido[0]['id_usuario']);
        require('Libraries/Ticket/ticket.php');
        $pdf = new PDF_Code128('P','mm',array(80,258));
        $pdf->SetMargins(4,10,4);
        $x = 10;
        $y = 10;
        $pdf->AddPage();

        $pdf->SetFont('Arial','B',9);
        $pdf->MultiCell(0,4,utf8_decode(strtoupper($empresa['nombre'])),0,'C',false);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(0,3,utf8_decode("RUC:".$empresa['ruc']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode($empresa['direccion']),0,'C',false);
        $pdf->MultiCell(0,3,utf8_decode("TERMINAL:".$pedido[0]['nombre_almacen']),0,'C',false);
        $pdf->Ln(1);
        $fecha_actual = date('d/m/Y', strtotime($pedido[0]['fecha']));
        $pdf->SetFont('Arial','B',12);
        if($pedido[0]['tipo_documento'] == 1){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("PEDIDO N°"))." ".$id_pedido,0,'C',false);
        }else if($pedido[0]['tipo_documento'] == 2){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("PEDIDO N°"))." ".$id_pedido,0,'C',false);
        }else{
            $pdf->MultiCell(0,5,utf8_decode(strtoupper("PEDIDO N°"))." ".$id_pedido,0,'C',false);
        }
        $pdf->SetFont('Arial','B',10.5);
        /*if($pedido[0]['serie'] != NULL && $pedido[0]['correlativo'] != NULL){
            $pdf->MultiCell(0,5,utf8_decode(strtoupper($pedido[0]['serie']."-".$correlativo)),0,'C',false);
        }*/

        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
        $pdf->SetFont('Arial','',9);
        $pdf->Ln(3);
    
        if ($pedido[0]['tipo_documento'] == 1) {
            if (isset($pedido[0]['dni']) && isset($pedido[0]['nombres']) && isset($pedido[0]['apellido_paterno']) && isset($pedido[0]['apellido_materno'])) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 4, utf8_decode("DNI:"), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 4, utf8_decode($pedido[0]['dni']), 0, 'L');
                
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 4, utf8_decode("NOMBRE:"), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 4, utf8_decode($pedido[0]['nombres']. " " . $pedido[0]['apellido_paterno'] . " " . $pedido[0]['apellido_materno']), 0, 'L');
            }
        } else if ($pedido[0]['tipo_documento'] == 2) {
            if (isset($pedido[0]['ruc']) && isset($pedido[0]['razon_social'])) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 4, utf8_decode("RUC:"), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 4, utf8_decode($pedido[0]['ruc']), 0, 'L');
                
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 4, utf8_decode("RAZON SOCIAL:"), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 4, utf8_decode($pedido[0]['razon_social']), 0, 'L');

                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(30, 4, utf8_decode("DIRECCIÓN:"), 0, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(0, 4, utf8_decode($pedido[0]['direccion']), 0, 'L');
            }
        }
        
        $pdf->Ln(1);
        $pdf->Cell(17,5,utf8_decode("CANT."),0,0,'A');
        $pdf->Cell(38.5,5,utf8_decode("PRECIO UNIT."),0,0,'C');
        $pdf->MultiCell(17,5,utf8_decode("TOTAL"),0,'R',false);

        $pdf->Ln(2);
        $total = 0.00;
        foreach ($detalle_pedido as $row) {
            $total += $row['total'];
            $pdf->Cell(70,4,utf8_decode($row['codigo']),0,1,'L');
            $pdf->MultiCell(70, 4, utf8_decode($row['nombre_producto']), 0, 'L');
            $pdf->Cell(12,4,utf8_decode($row['cantidad']),0,0,'A');
            $pdf->Cell(49,4,utf8_decode(number_format($row['precio'],2, '.',',')),0,0,'C');
            $pdf->Cell(11.5,4,utf8_decode(number_format($row['cantidad'] * $row['precio'],2, '.',',')),0,0,'R');
            $pdf->Ln(2);
            $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');
            $pdf->Ln(4);
        }

        $pdf->Ln(1);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("SUB TOTAL"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode(number_format($pedido[0]['base'], 2, '.', ',')), 0, 0, 'R');
        
        $pdf->Ln(4);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("IGV (18%)"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode(number_format($pedido[0]['igv'], 2, '.', ',')), 0, 0, 'R');

        $pdf->Ln(4);
        $pdf->Cell(18, 5, utf8_decode(""), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(48, 5, utf8_decode("TOTAL"), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(6.5, 5, utf8_decode(number_format($total, 2, '.', ',')), 0, 0, 'R');
    
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0,2,utf8_decode("................................................................................"),0,0,'A');

        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(54,5,utf8_decode("FEC. DE EMISION"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($fecha_actual),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(54,5,utf8_decode("FEC. DE VENCIMIENTO"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($fecha_actual),0,0,'L');
        $pdf->Ln(4);
        if ($pedido[0]['visa'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("VISA    " . $pedido[0]['visa']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['master_c'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("MASTER " . $pedido[0]['master_c']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['diners'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("DINERS " . $pedido[0]['diners']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['a_express'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("A.EXPRESS " . $pedido[0]['a_express']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['yape'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("YAPE    " . $pedido[0]['yape']), 0, 'L');
            $pdf->Ln(4);
        }

        if($pedido[0]['plin'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("PLIN    " . $pedido[0]['plin']), 0, 'L');
            $pdf->Ln(4);
        }

        if($pedido[0]['izipay'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("IZIPAY " . $pedido[0]['izipay']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['niubiz'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("NIUBIZ " . $pedido[0]['niubiz']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['pos'] != "0.00" && $pedido[0]['pos'] !== NULL){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("POS " . $pedido[0]['pos']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['transferencia'] != "0.00" && $pedido[0]['transferencia'] !== NULL){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("TRANSF. " . $pedido[0]['transferencia']), 0, 'L');
            $pdf->Ln(4);
        }
        if($pedido[0]['efectivo'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("FORMA DE PAGO"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("EFECTIVO ".$pedido[0]['efectivo']), 0, 'L');
            $pdf->Ln(4);
        }

        if($pedido[0]['propina'] != "0.00"){
            $pdf->SetFont('Arial','B',9);
            $pdf->Cell(54,5,utf8_decode("ADICIONAL"),0,0,'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode("PROPINA ".$pedido[0]['propina']), 0, 'L');
            $pdf->Ln(4);
        }
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(54,5,utf8_decode("VENDEDOR"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($usuario_compra['nombre']),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(54,5,utf8_decode("TIPO PEDIDO"),0,0,'L');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(0,5,utf8_decode($pedido[0]['nombre_pedido']),0,0,'L');

        $numero = $total;
        $totalEnLetras = $this->convertirNumeroALetras($total);

        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(0, 5, utf8_decode("SON " . $totalEnLetras), 0, 'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Output("I","VENTA ".$id_pedido."_".$fecha_actual.".pdf",true);
    }

    public function pdfReporte()
    {
        require('Libraries/fpdf/tabla_ventas.php');
    
        $pdf = new PDF_Reporte_Otro();
        $pdf->AddPage('P', 'A4');
    
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(0, 10, 'RESUMEN POR VENTAS', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 13);
    
        $results = $this->model->obtenerResumenVentas();
        $cellWidth = 50;
        $x = ($pdf->GetPageWidth() - ($cellWidth * 3)) / 2;
        $pdf->SetX($x);
    
        $pdf->Cell($cellWidth, 10, 'Tipo de Pedido', 1);
        $pdf->Cell($cellWidth, 10, 'Cantidad de Ventas', 1);
        $pdf->Cell($cellWidth, 10, 'Total de Ventas (S/.)', 1);
        $pdf->Ln();
        $totalVentas = 0;
    
        foreach ($results as $row) {
            $pdf->SetX($x);
            $pdf->Cell($cellWidth, 10, $row['tipo_pedido_nombre'], 1);
            $pdf->Cell($cellWidth, 10, $row['cantidad_ventas'], 1);
            $pdf->Cell($cellWidth, 10, 'S/.' . number_format($row['total_ventas'], 2), 1);
            $pdf->Ln();
            $totalVentas += $row['total_ventas'];
        }
    
        $pdf->SetX($x);
        $pdf->Cell($cellWidth * 2, 10, 'Total:', 1);
        $pdf->Cell($cellWidth, 10, 'S/.' . number_format($totalVentas, 2), 1);
        $pdf->Ln();
    
        $pdf->Output("I", "Resumen_de_Ventas.pdf", true);
    }

    public function pdfPorFechas()
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarios($id_usu);
    
        if ($id_usu == "-1") {
            $data = $this->model->obtenerResumenVentasGen($desde, $hasta);
        } else {
            $data = $this->model->obtenerResumenVentas($desde, $hasta, $getUsu['id']);
        }
    
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_Reporte_Otro();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $maxHeight = 230;
        $total = 0.00;
        $currentHeight = 0;
        $centrarTablaX = ($pdf->GetPageWidth() - 91) / 2;
        foreach ($data as $row) {
            $cellHeight = 10;
            if ($currentHeight + $cellHeight > $maxHeight) {
                $pdf->AddPage();
                $currentHeight = 0;
            }
            $pdf->SetX($centrarTablaX);
            $pdf->Cell(50, 10, $row['nombre'], 1, 0, 'C');
            $pdf->Cell(20, 10, $row['cantidad_pedidos'], 1, 0, 'C');
            $pdf->Cell(21, 10, $row['total_por_pedido'], 1, 1, 'C');
            $total += $row['total_por_pedido'];
            $currentHeight += $cellHeight;
        }
    
        if ($currentHeight + 60 > $maxHeight) {
            $pdf->AddPage();
            $currentHeight = 0;
        }

        $pdf->Cell(139, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'R');
        $pdf->Cell(101, -10, 'Fecha: ' . $desde . ' / ' . $hasta, 0, 1, 'R');
        if ($id_usu == "-1") {
            $pdf->Cell(85.6, 20, 'RESUMEN GENERAL', 0, 1, 'R');
        } else {
            $pdf->Cell(89, 20, 'Vendedor: ' . $getUsu['nombre'], 0, 1, 'R');
        }
    
        $pdf->Output("I", "Resumen_de_Ventas.pdf", true);
    }

    public function consultaRUC($ruc)
    {
        $verificarRUC = $this->model->consultarCliente(2, $ruc);
        if(empty($verificarRUC)){
            $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ),
            ));

            echo $response = curl_exec($curl);
            curl_close($curl);
            die();
        }else{
            echo json_encode($verificarRUC, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function consultaDNI($dni)
    {
        $verificarDNI = $this->model->consultarCliente(1, $dni);
        if(empty($verificarDNI)){
            $token = 'apis-token-5570.eIUBKO-AFR4W75dbL8Qwr9bFMsJRoTpt';
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
            ),
            ));
    
            echo $response = curl_exec($curl);
            curl_close($curl);
            die();
        }else{
            echo json_encode($verificarDNI, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function exportarExcelPorRangos()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'RESUMEN DE VENTAS');
        $sheet->mergeCells('A1:N1');
    
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
    
        $sheet->getStyle('A1:J1')->applyFromArray($styleHeader);
    
        $sheet->setCellValue('A2', 'N°');
        $sheet->setCellValue('B2', 'Fecha');
        $sheet->setCellValue('C2', 'Tipo Documento');
        $sheet->setCellValue('D2', 'Serie');
        $sheet->setCellValue('E2', 'Correlativo');
        $sheet->setCellValue('F2', 'Dni / Ruc');
        $sheet->setCellValue('G2', 'Nombre / Razon Social');
        $sheet->setCellValue('H2', 'Asiento Cont.');
        $sheet->setCellValue('I2', 'Tipo Doc.');
        $sheet->setCellValue('J2', 'Serie');
        $sheet->setCellValue('K2', 'Numer');
        $sheet->setCellValue('L2', 'Base');
        $sheet->setCellValue('M2', 'Igv');
        $sheet->setCellValue('N2', 'Total');
        
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarios($id_usu);
        
        if ($id_usu == "-1") {
            $data = $this->model->getRangoFechasExcelGen($desde, $hasta);
        } else {
            $data = $this->model->getRangoFechasExcel($desde, $hasta, $getUsu['id']);
        }
        $row = 3;
        foreach ($data as $row_data) {
            $sheet->setCellValue('A' . $row, $row_data['id']);
            $sheet->setCellValue('B' . $row, $row_data['fecha']);
            $sheet->setCellValue('C' . $row, $row_data['codigo_sunat']);
            $sheet->setCellValue('D' . $row, $row_data['serie']);
            $sheet->setCellValue('E' . $row, $row_data['correlativo']);
    
            if ($row_data['dni'] === null && $row_data['ruc'] !== null) {
                $sheet->setCellValue('F' . $row, $row_data['ruc']);
                $sheet->setCellValue('G' . $row, $row_data['razon_social']);
            } else {
                $sheet->setCellValue('F' . $row, $row_data['dni']);
                $sheet->setCellValue('G' . $row, $row_data['nombres']);
            }
            $sheet->setCellValue('H' . $row, $row_data['cconnumero']);
            $sheet->setCellValue('I' . $row, $row_data['Fcfmanivel']);
            $sheet->setCellValue('J' . $row, $row_data['Fcfmaserie']);
            $sheet->setCellValue('K' . $row, $row_data['Fcfmanumero']);
            $sheet->setCellValue('L' . $row, $row_data['base']);
            $sheet->setCellValue('M' . $row, $row_data['igv']);
            $sheet->setCellValue('N' . $row, $row_data['total']);
    
            $row++;
        }
        
        $styleColumnTitle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
    
        $sheet->getStyle('A2:N2')->applyFromArray($styleColumnTitle);
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_de_Ventas.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    
        exit;
    }

    public function exportarExcelPorRangosDetallado()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'RESUMEN DE VENTAS');
        $sheet->mergeCells('A1:Q1');
    
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
    
        $sheet->getStyle('A1:Q1')->applyFromArray($styleHeader);
    
        $sheet->setCellValue('A2', 'N°');
        $sheet->setCellValue('B2', 'N° Pedido');
        $sheet->setCellValue('C2', 'Fecha');
        $sheet->setCellValue('D2', 'Almacen');
        $sheet->setCellValue('E2', 'Tipo Documento');
        $sheet->setCellValue('F2', 'Serie');
        $sheet->setCellValue('G2', 'Correlativo');
        $sheet->setCellValue('H2', 'Dni / Ruc');
        $sheet->setCellValue('I2', 'Nombre / Razon Social');
        $sheet->setCellValue('J2', 'Código');
        $sheet->setCellValue('K2', 'Producto');
        $sheet->setCellValue('L2', 'Cantidad');
        $sheet->setCellValue('M2', 'P.U');
        $sheet->setCellValue('N2', 'Base');
        $sheet->setCellValue('O2', 'Igv');
        $sheet->setCellValue('P2', 'Total');
        $sheet->setCellValue('Q2', 'Propina');
        
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarios($id_usu);
        
        if ($id_usu == "-1") {
            $data = $this->model->getRangoFechasExcelGenDet($desde, $hasta);
        } else {
            $data = $this->model->getRangoFechasExcelDet($desde, $hasta, $getUsu['id']);
        }
        $row = 3;
        foreach ($data as $row_data) {
            $sheet->setCellValue('A' . $row, $row_data['id']);
            $sheet->setCellValue('B' . $row, $row_data['id_pedido']);
            $sheet->setCellValue('C' . $row, $row_data['fecha']);
            $sheet->setCellValue('D' . $row, $row_data['almacen']);
            $sheet->setCellValue('E' . $row, $row_data['codigo_sunat']);
            $sheet->setCellValue('F' . $row, $row_data['serie']);
            $sheet->setCellValue('G' . $row, $row_data['correlativo']);
    
            if ($row_data['dni'] === null && $row_data['ruc'] !== null) {
                $sheet->setCellValue('H' . $row, $row_data['ruc']);
                $sheet->setCellValue('I' . $row, $row_data['razon_social']);
            } else {
                $sheet->setCellValue('H' . $row, $row_data['dni']);
                $sheet->setCellValue('I' . $row, $row_data['nombres']);
            }
            $sheet->setCellValue('J' . $row, $row_data['codigo']);
            $sheet->setCellValue('K' . $row, $row_data['descripcion']);
            $sheet->setCellValue('L' . $row, $row_data['cantidad']);
            $sheet->setCellValue('M' . $row, $row_data['precio']);
            $sheet->setCellValue('N' . $row, $row_data['base']);
            $sheet->setCellValue('O' . $row, $row_data['igv']);
            $sheet->setCellValue('P' . $row, $row_data['total']);
            $sheet->setCellValue('Q' . $row, $row_data['propina']);
    
            $row++;
        }
        
        $styleColumnTitle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
    
        $sheet->getStyle('A2:Q2')->applyFromArray($styleColumnTitle);
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_de_Ventas.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    
        exit;
    }

    public function pdfEntradasPorFecha()
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usuario = $_SESSION['id_usuario'];
        $data = $this->model->getEntradasFecha($desde, $hasta, $id_usuario);
    
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_Reporte_Entradas();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 9);
        $maxHeight = 230;
        $total = 0.00;
        $currentHeight = 0;
        $centrarTablaX = ($pdf->GetPageWidth() - 200) / 2;
        foreach ($data as $row) {
            $cellHeight = 10;
            if ($currentHeight + $cellHeight > $maxHeight) {
                $pdf->AddPage();
                $currentHeight = 0;
            }
            $pdf->SetX($centrarTablaX);
            $pdf->Cell(10, 5, $row['id'], 1, 0, 'C');
            $pdf->Cell(20, 5, $row['fecha'], 1, 0, 'C');
            $pdf->Cell(60, 5, $row['nombre_usuario'], 1, 0, 'C');
            $pdf->Cell(39, 5, $row['nombre_almacen'], 1, 0, 'C');
            $pdf->Cell(25, 5, $row['documento'], 1, 0, 'C');
            $pdf->Cell(21, 5, $row['serie'], 1, 0, 'C');
            $pdf->Cell(25, 5, $row['correlativo'], 1, 1, 'C');
            $total += $row['total'];
            $currentHeight += $cellHeight;
        }
    
        if ($currentHeight + 60 > $maxHeight) {
            $pdf->AddPage();
            $currentHeight = 0;
        }

        $pdf->Cell(0, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'R');
        $pdf->Cell(150, -10, 'Fecha: ' . $desde . ' / ' . $hasta, 0, 1, 'R');
    
        $pdf->Output("I", "Resumen_de_Ventas.pdf", true);
    }

    public function resumenGeneral()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('VENTAS');
        $styleHeader = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID],
        ];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarios($id_usu);
        if ($id_usu == "-1") {
            $totalBebidas = $this->model->totalVentasGen(1,$desde, $hasta);
            $totalCocteleria = $this->model->totalVentasGen(2,$desde, $hasta);
            $totalCocina = $this->model->totalVentasGen(3,$desde, $hasta);
            $totalEntradas = $this->model->totalVentasGen(4,$desde, $hasta);
            $totalCortesia = $this->model->totalNoVentasGen(1,$desde, $hasta);
            $totalGastos = $this->model->totalNoVentasGen(2,$desde, $hasta);
            $totalDescuento = $this->model->totalNoVentasGen(3,$desde, $hasta);

            $pedidos_cocina_cobranza = $this->model->getPedidosResumenGeneral(1,"COCINA",$desde, $hasta);
            $pedidos_cocina_cortesia = $this->model->getPedidosResumenGeneral(2,"COCINA",$desde, $hasta);
            $pedidos_cocina_representante = $this->model->getPedidosResumenGeneral(3,"COCINA",$desde, $hasta);
            $pedidos_cocina_desc_trabajador = $this->model->getPedidosResumenGeneral(4,"COCINA",$desde, $hasta);

            $pedidos_bebidas_cobranza = $this->model->getPedidosResumenGeneral(1,"BEBIDAS",$desde, $hasta);
            $pedidos_bebidas_cortesia = $this->model->getPedidosResumenGeneral(2,"BEBIDAS",$desde, $hasta);
            $pedidos_bebidas_representante = $this->model->getPedidosResumenGeneral(3,"BEBIDAS",$desde, $hasta);
            $pedidos_bebidas_desc_trabajador = $this->model->getPedidosResumenGeneral(4,"BEBIDAS",$desde, $hasta);

            $pedidos_cocteleria_cobranza = $this->model->getPedidosResumenGeneral(1,"COCTELERIA",$desde, $hasta);
            $pedidos_cocteleria_cortesia = $this->model->getPedidosResumenGeneral(2,"COCTELERIA",$desde, $hasta);
            $pedidos_cocteleria_representante = $this->model->getPedidosResumenGeneral(3,"COCTELERIA",$desde, $hasta);
            $pedidos_cocteleria_desc_trabajador = $this->model->getPedidosResumenGeneral(4,"COCTELERIA",$desde, $hasta);

            $pedidos_boleteria = $this->model->getPedidosResumenGeneral(0,"BOLETERIA",$desde, $hasta);
            $totalPropinas = $this->model->getTotalPropinasGeneral($desde, $hasta);
        } else {
            $totalBebidas = $this->model->totalVentas(1,$desde, $hasta, $getUsu['id']);
            $totalCocteleria = $this->model->totalVentas(2,$desde, $hasta, $getUsu['id']);
            $totalCocina = $this->model->totalVentas(3,$desde, $hasta, $getUsu['id']);
            $totalEntradas = $this->model->totalVentas(4,$desde, $hasta, $getUsu['id']);
            $totalCortesia = $this->model->totalNoVentas(1,$desde, $hasta, $getUsu['id']);
            $totalGastos = $this->model->totalNoVentas(2,$desde, $hasta, $getUsu['id']);
            $totalDescuento = $this->model->totalNoVentas(3,$desde, $hasta, $getUsu['id']);

            $pedidos_cocina_cobranza = $this->model->getPedidosResumen(1,"COCINA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocina_cortesia = $this->model->getPedidosResumen(2,"COCINA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocina_representante = $this->model->getPedidosResumen(3,"COCINA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocina_desc_trabajador = $this->model->getPedidosResumen(4,"COCINA",$desde, $hasta, $getUsu['id']);

            $pedidos_bebidas_cobranza = $this->model->getPedidosResumen(1,"BEBIDAS",$desde, $hasta, $getUsu['id']);
            $pedidos_bebidas_cortesia = $this->model->getPedidosResumen(2,"BEBIDAS",$desde, $hasta, $getUsu['id']);
            $pedidos_bebidas_representante = $this->model->getPedidosResumen(3,"BEBIDAS",$desde, $hasta, $getUsu['id']);
            $pedidos_bebidas_desc_trabajador = $this->model->getPedidosResumen(4,"BEBIDAS",$desde, $hasta, $getUsu['id']);

            $pedidos_cocteleria_cobranza = $this->model->getPedidosResumen(1,"COCTELERIA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocteleria_cortesia = $this->model->getPedidosResumen(2,"COCTELERIA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocteleria_representante = $this->model->getPedidosResumen(3,"COCTELERIA",$desde, $hasta, $getUsu['id']);
            $pedidos_cocteleria_desc_trabajador = $this->model->getPedidosResumen(4,"COCTELERIA",$desde, $hasta, $getUsu['id']);

            $pedidos_boleteria = $this->model->getPedidosResumen(0,"BOLETERIA",$desde, $hasta, $getUsu['id']);
            $totalPropinas = $this->model->getTotalPropinas($desde, $hasta, $getUsu['id']);
        }
        if($id_usu != -1){
            $sheet->setCellValue('A1', 'RESUMEN GENERAL '.'('.$getUsu['nombre'].')');
        }else{
            $sheet->setCellValue('A1', 'RESUMEN GENERAL '.'(GENERAL)');
        }
        $sheet->setCellValue('A2', 'DEL ' .date('d', strtotime($desde)). ' DE ' . strtoupper(date('F', strtotime($desde))) . ' AL ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
        $sheet->setCellValue('A4', 'VENTAS');
        $sheet->setCellValue('C4', 'PRECIO DE VENTAS');
        $sheet->setCellValue('A5', 'VENTAS DE BEBIDAS');
        $sheet->setCellValue('C5', isset($totalBebidas['total_ventas']) ? $totalBebidas['total_ventas'] : 0.00);
        $sheet->setCellValue('A6', 'VENTAS DE COCTELERIA');
        $sheet->setCellValue('C6', isset($totalCocteleria['total_ventas']) ? $totalCocteleria['total_ventas'] : 0.00);
        $sheet->setCellValue('A7', 'VENTAS DE COCINA');
        $sheet->setCellValue('C7', isset($totalCocina['total_ventas']) ? $totalCocina['total_ventas'] : 0.00);
        $sheet->setCellValue('A8', 'VENTAS DE ENTRADA');
        $sheet->setCellValue('C8', isset($totalEntradas['total_ventas']) ? $totalEntradas['total_ventas'] : 0.00);
        $sheet->setCellValue('A9', 'VENTAS DE TICKETS PARQUEO');
        $sheet->setCellValue('C9', 0.00);
        $sheet->setCellValue('A10', 'VENTAS DE HALLS, CIGARROS, TRIDENT, GLOBOPOP');
        $sheet->setCellValue('C10', 0.00);
        $sheet->setCellValue('A11', 'TOTAL VENTAS');
        $sheet->setCellValue('C11', $totalBebidas['total_ventas']+$totalCocteleria['total_ventas']+$totalCocina['total_ventas']+$totalEntradas['total_ventas']);
        $sheet->setCellValue('D4', 'EFECTIVO');
        $sheet->setCellValue('D5', isset($totalBebidas['efectivo']) ? $totalBebidas['efectivo'] : 0.00);
        $sheet->setCellValue('E4', 'VISA');
        $sheet->setCellValue('E5', isset($totalBebidas['visa']) ? $totalBebidas['visa'] : 0.00);
        $sheet->setCellValue('F4', 'MASTER CARD');
        $sheet->setCellValue('F5', isset($totalBebidas['master_c']) ? $totalBebidas['master_c'] : 0.00);
        $sheet->setCellValue('G4', 'DINERS');
        $sheet->setCellValue('G5', isset($totalBebidas['diners']) ? $totalBebidas['diners'] : 0.00);
        $sheet->setCellValue('H4', 'AMERICAN EXPRESS');
        $sheet->setCellValue('H5', isset($totalBebidas['a_express']) ? $totalBebidas['a_express'] : 0.00);
        $sheet->setCellValue('I4', 'YAPE');
        $sheet->setCellValue('I5', isset($totalBebidas['yape']) ? $totalBebidas['yape'] : 0.00);
        $sheet->setCellValue('J4', 'PLIN');
        $sheet->setCellValue('J5', isset($totalBebidas['plin']) ? $totalBebidas['plin'] : 0.00);
        $sheet->setCellValue('K4', 'IZIPAY');
        $sheet->setCellValue('K5', isset($totalBebidas['izipay']) ? $totalBebidas['izipay'] : 0.00);
        $sheet->setCellValue('L4', 'NIUBIZ');
        $sheet->setCellValue('L5', isset($totalBebidas['niubiz']) ? $totalBebidas['niubiz'] : 0.00);

        $sheet->setCellValue('D6', isset($totalCocteleria['efectivo']) ? $totalCocteleria['efectivo'] : 0.00);
        $sheet->setCellValue('E6', isset($totalCocteleria['visa']) ? $totalCocteleria['visa'] : 0.00);
        $sheet->setCellValue('F6', isset($totalCocteleria['master_c']) ? $totalCocteleria['master_c'] : 0.00);
        $sheet->setCellValue('G6', isset($totalCocteleria['diners']) ? $totalCocteleria['diners'] : 0.00);
        $sheet->setCellValue('H6', isset($totalCocteleria['a_express']) ? $totalCocteleria['a_express'] : 0.00);
        $sheet->setCellValue('I6', isset($totalCocteleria['yape']) ? $totalCocteleria['yape'] : 0.00);
        $sheet->setCellValue('J6', isset($totalCocteleria['plin']) ? $totalCocteleria['plin'] : 0.00);
        $sheet->setCellValue('K6', isset($totalCocteleria['izipay']) ? $totalCocteleria['izipay'] : 0.00);
        $sheet->setCellValue('L6', isset($totalCocteleria['niubiz']) ? $totalCocteleria['niubiz'] : 0.00);

        $sheet->setCellValue('D7', isset($totalCocina['efectivo']) ? $totalCocina['efectivo'] : 0.00);
        $sheet->setCellValue('E7', isset($totalCocina['visa']) ? $totalCocina['visa'] : 0.00);
        $sheet->setCellValue('F7', isset($totalCocina['master_c']) ? $totalCocina['master_c'] : 0.00);
        $sheet->setCellValue('G7', isset($totalCocina['diners']) ? $totalCocina['diners'] : 0.00);
        $sheet->setCellValue('H7', isset($totalCocina['a_express']) ? $totalCocina['a_express'] : 0.00);
        $sheet->setCellValue('I7', isset($totalCocina['yape']) ? $totalCocina['yape'] : 0.00);
        $sheet->setCellValue('J7', isset($totalCocina['plin']) ? $totalCocina['plin'] : 0.00);
        $sheet->setCellValue('K7', isset($totalCocina['izipay']) ? $totalCocina['izipay'] : 0.00);
        $sheet->setCellValue('L7', isset($totalCocina['niubiz']) ? $totalCocina['niubiz'] : 0.00);

        $sheet->setCellValue('D8', isset($totalEntradas['efectivo']) ? $totalEntradas['efectivo'] : 0.00);
        $sheet->setCellValue('E8', isset($totalEntradas['visa']) ? $totalEntradas['visa'] : 0.00);
        $sheet->setCellValue('F8', isset($totalEntradas['master_c']) ? $totalEntradas['master_c'] : 0.00);
        $sheet->setCellValue('G8', isset($totalEntradas['diners']) ? $totalEntradas['diners'] : 0.00);
        $sheet->setCellValue('H8', isset($totalEntradas['a_express']) ? $totalEntradas['a_express'] : 0.00);
        $sheet->setCellValue('I8', isset($totalEntradas['yape']) ? $totalEntradas['yape'] : 0.00);
        $sheet->setCellValue('J8', isset($totalEntradas['plin']) ? $totalEntradas['plin'] : 0.00);
        $sheet->setCellValue('K8', isset($totalEntradas['izipay']) ? $totalEntradas['izipay'] : 0.00);
        $sheet->setCellValue('L8', isset($totalEntradas['niubiz']) ? $totalEntradas['niubiz'] : 0.00);

        $sheet->setCellValue('A13', 'COSTOS OPERATIVOS');
        $sheet->setCellValue('A14', 'VENTA DE BEBIDAS');
        $sheet->setCellValue('C14', 0.00);
        $sheet->setCellValue('A15', 'CORTESÍAS');
        $sheet->setCellValue('C15', isset($totalCortesia['total_ventas']) ? $totalCortesia['total_ventas'] : 0.00);
        $sheet->setCellValue('A16', 'GASTOS DE REPRESENTACIÓN');
        $sheet->setCellValue('C16', isset($totalGastos['total_ventas']) ? $totalGastos['total_ventas'] : 0.00);
        $sheet->setCellValue('A17', 'DESCUENTO A TRABAJADOR');
        $sheet->setCellValue('C17', isset($totalDescuento['total_ventas']) ? $totalDescuento['total_ventas'] : 0.00);
        $sheet->setCellValue('A18', 'PROPINAS');
        $sheet->setCellValue('C18', isset($totalPropinas['propina']) ? $totalPropinas['propina'] : 0.00);

        $sheet->setCellValue('A19', 'ENTRADAS');
        $sheet->setCellValue('C19', 0.00);
        $sheet->setCellValue('A20', 'PULSERAS');
        $sheet->setCellValue('B20', 0.00);
        $sheet->setCellValue('A21', 'BOLETERÍA');
        $sheet->setCellValue('B21', 0.00);
        $sheet->setCellValue('A22', 'PROMOTORES');
        $sheet->setCellValue('B22', 0.00);

        $sheet->setCellValue('A23', 'PARQUEO');
        $sheet->setCellValue('C23', 0.00);
        $sheet->setCellValue('A24', 'TICKETS IMPRESOS');
        $sheet->setCellValue('B24', 0.00);
        $sheet->setCellValue('A25', 'PARQUEO');
        $sheet->setCellValue('B25', 0.00);

        $sheet->setCellValue('A26', 'COCINA');
        $sheet->setCellValue('C26', 0.00);
        $sheet->setCellValue('A27', 'CHEF Y AYUDANTES');
        $sheet->setCellValue('B27', 0.00);
        $sheet->setCellValue('A28', 'COMPRA DE GAS');
        $sheet->setCellValue('B28', 0.00);
        $sheet->setCellValue('A29', 'COMPRAS DE CARNE');
        $sheet->setCellValue('B29', 0.00);
        $sheet->setCellValue('A30', 'COMPRAS MERCADO');
        $sheet->setCellValue('B30', 0.00);
        $sheet->setCellValue('A31', 'COMPRAS VÍVERES');
        $sheet->setCellValue('B31', 0.00);
        $sheet->setCellValue('A32', 'ALIMENTACIÓN AL PERSONAL');
        $sheet->setCellValue('B32', 0.00);

        $sheet->setCellValue('A33', 'COCTELERÍA');
        $sheet->setCellValue('C33', 0.00);
        $sheet->setCellValue('A34', 'LICORES CONSUMIDOS');
        $sheet->setCellValue('B34', 0.00);
        $sheet->setCellValue('A35', 'PAGOS A BARMAN');
        $sheet->setCellValue('B35', 0.00);
        $sheet->setCellValue('A36', 'COMPRAS DE INSUMOS');
        $sheet->setCellValue('B36', 0.00);

        $sheet->setCellValue('A37', 'HALLS. CIGARRILLOS Y TRIDENT');
        $sheet->setCellValue('C37', 0.00);
        $sheet->setCellValue('A38', 'MOZOS');
        $sheet->setCellValue('B38', 0.00);
        $sheet->setCellValue('A39', 'ANFITRIONAS');
        $sheet->setCellValue('B39', 0.00);
        $sheet->setCellValue('A40', 'ABASTECIMIENTO');
        $sheet->setCellValue('B40', 0.00);
        $sheet->setCellValue('A41', 'LIMPIEZA');
        $sheet->setCellValue('B41', 0.00);
        $sheet->setCellValue('A42', 'SEGURIDAD INTERNA');
        $sheet->setCellValue('B42', 0.00);
        $sheet->setCellValue('A43', 'SEGURIDAD EXTERNA');
        $sheet->setCellValue('B43', 0.00);
        $sheet->setCellValue('A44', 'CAJEROS MOVILES');
        $sheet->setCellValue('B44', 0.00);
        $sheet->setCellValue('A45', 'CAJEROS DE BARRAS');
        $sheet->setCellValue('B45', 0.00);
        $sheet->setCellValue('A46', 'PERSONAL LOGISTICA');
        $sheet->setCellValue('B46', 0.00);
        $sheet->setCellValue('A47', 'PERSONAL ORQUESTA LA CIMA');
        $sheet->setCellValue('B47', 0.00);
        $sheet->setCellValue('A48', 'MARKETING Y PUBLICIDAD (VIDEOS Y BAILARIN)');
        $sheet->setCellValue('B48', 0.00);
        $sheet->setCellValue('A49', 'DJS LOCAL');
        $sheet->setCellValue('B49', 0.00);
        $sheet->setCellValue('A50', 'FOTÓGRAFO');
        $sheet->setCellValue('B50', 0.00);
        $sheet->setCellValue('A51', 'OPERADOR DE SONIDO');
        $sheet->setCellValue('B51', 0.00);
        $sheet->setCellValue('A52', 'ANIMADORES');
        $sheet->setCellValue('B52', 0.00);

        $sheet->setCellValue('A53', 'ARTISTAS INVITADOS');
        $sheet->setCellValue('C53', 0.00);
        $sheet->setCellValue('A54', 'PASAJES ÁEREOS');
        $sheet->setCellValue('B54', 0.00);
        $sheet->setCellValue('A55', 'HOTEL');
        $sheet->setCellValue('B55', 0.00);
        $sheet->setCellValue('A56', 'ANIMADOR SHEPUT');
        $sheet->setCellValue('B56', 0.00);
        $sheet->setCellValue('A57', 'DJ FITT ALEXANDER ZÁRATE');
        $sheet->setCellValue('B57', 0.00);
        $sheet->setCellValue('A58', 'DJ AYTRAN');
        $sheet->setCellValue('B58', 0.00);
        $sheet->setCellValue('A59', 'DJ PAPI JUANCIO');
        $sheet->setCellValue('B59', 0.00);
        $sheet->setCellValue('A60', 'DJ MICHI BEAT');
        $sheet->setCellValue('B60', 0.00);

        $sheet->setCellValue('A61', 'SUMINISTROS OPERATIVOS');
        $sheet->setCellValue('C61', 0.00);
        $sheet->setCellValue('A62', 'UNIFORME PARA ANFITRIONAS Y BAILARINAS');
        $sheet->setCellValue('B62', 0.00);
        $sheet->setCellValue('A63', 'MATERIALES HALLOWEEN');
        $sheet->setCellValue('B63', 0.00);
        $sheet->setCellValue('A64', 'COMPRA DE CAMISAS, PILAS Y OTROS');
        $sheet->setCellValue('B64', 0.00);
        $sheet->setCellValue('A65', 'IMPLEMENTACIÓN ESCENARIO');
        $sheet->setCellValue('B65', 0.00);
        $sheet->setCellValue('A66', 'JUEGO DE BOXES');
        $sheet->setCellValue('B66', 0.00);
        $sheet->setCellValue('A67', 'RECARGA CO2');
        $sheet->setCellValue('B67', 0.00);
        $sheet->setCellValue('A68', 'MAQUILLAJE Y VESTUARIO, MOVILIDAD ORQUESTA');
        $sheet->setCellValue('B68', 0.00);
        $sheet->setCellValue('A69', 'PUESTA DE PASACALLE');
        $sheet->setCellValue('B69', 0.00);
        $sheet->setCellValue('A70', 'LUZ');
        $sheet->setCellValue('B70', 0.00);
        $sheet->setCellValue('A71', 'ALQUILER DE LOCAL BELLAQUEO');
        $sheet->setCellValue('B71', 0.00);
        $sheet->setCellValue('A72', 'HIELO');
        $sheet->setCellValue('B72', 0.00);

        $sheet->setCellValue('A73', 'ALQUILER DE EQUIPOS DIVERSOS');
        $sheet->setCellValue('C73', 0.00);
        $sheet->setCellValue('A74', 'PAGO APDAYC, PNP Y FISCAL');
        $sheet->setCellValue('B74', 0.00);
        $sheet->setCellValue('A75', 'OSO TED');
        $sheet->setCellValue('B75', 0.00);
        $sheet->setCellValue('A76', 'TIBURON');
        $sheet->setCellValue('B76', 0.00);
        $sheet->setCellValue('A77', 'ALQUILER DE SONIDO');
        $sheet->setCellValue('B77', 0.00);
        $sheet->setCellValue('A78', 'GRABACIÓN DE AUDIO Y SONIDO');
        $sheet->setCellValue('B78', 0.00);
        $sheet->setCellValue('A79', 'ALQUILER DE MONITORES');
        $sheet->setCellValue('B79', 0.00);
        $sheet->setCellValue('A80', 'ALQUILER DE LUCES');
        $sheet->setCellValue('B80', 0.00);
        $sheet->setCellValue('A81', 'ALQUILER DE COMPRESORA');
        $sheet->setCellValue('B81', 0.00);
        $sheet->setCellValue('A82', 'DECORACIÓN');
        $sheet->setCellValue('B82', 0.00);

        $sheet->setCellValue('A84', 'TOTAL COSTOS OPERATIVOS');
        $sheet->setCellValue('C84', 0.00);

        $sheet->setCellValue('A86', 'GASTOS ADMINISTRATIVOS');
        $sheet->setCellValue('C86', 0.00);
        $sheet->setCellValue('A87', 'MANTENIMIENTO DEL LOCAL');
        $sheet->setCellValue('B87', 0.00);
        $sheet->setCellValue('A88', 'SUELDOS PERSONAL ADMINISTRATIVOS');
        $sheet->setCellValue('B88', 0.00);
        $sheet->setCellValue('A89', 'MOVILIDAD');
        $sheet->setCellValue('B89', 0.00);
        $sheet->setCellValue('A90', 'SUNAT');
        $sheet->setCellValue('B90', 0.00);
        $sheet->setCellValue('A91', 'CAJA CHICA');
        $sheet->setCellValue('B91', 0.00);
        $sheet->setCellValue('A92', 'CATERING PARRILLERO');
        $sheet->setCellValue('B92', 0.00);
        $sheet->setCellValue('A93', 'MATERIALES DE LIMPIEZA');
        $sheet->setCellValue('B93', 0.00);
        $sheet->setCellValue('A94', 'LIMPIEZA DE TANQUE');
        $sheet->setCellValue('B94', 0.00);
        $sheet->setCellValue('A95', 'INTERNET');
        $sheet->setCellValue('B95', 0.00);
        $sheet->setCellValue('A96', 'ASESORÍA FINANCIERA');
        $sheet->setCellValue('B96', 0.00);

        $sheet->setCellValue('A98', 'GASTOS FINANCIEROS');
        $sheet->setCellValue('C98', 0.00);
        $sheet->setCellValue('A99', 'ITF');
        $sheet->setCellValue('B99', 0.00);
        $sheet->setCellValue('A100', 'GASTOS BANCARIOS');
        $sheet->setCellValue('B100', 0.00);
        $sheet->setCellValue('A101', 'COMISIÓN NIUBIZ');
        $sheet->setCellValue('B101', 0.00);

        $sheet->setCellValue('A103', 'PÉRDIDA');
        $sheet->setCellValue('C103', 0.00);

        $sheet->setCellValue('A105', 'ADELANTO UTILIDAD');
        $sheet->setCellValue('C105', 0.00);

        $sheet->setCellValue('A107', 'SALDO DESPUÉS DE DISTRIBUCIÓN');
        $sheet->setCellValue('C107', 0.00);
        
        $negritaCells = ['A4', 'C4', 'A11', 'C11', 'A13', 'A19', 'A23', 'A26', 'A33', 'A37', 'A53', 'A61', 'A73', 'A84', 'C84', 'A86', 'C86', 'A98', 'C98', 'A103', 'C103', 'A105', 'C105', 'A107', 'C107', 'D4', 'E4', 'F4', 'G4', 'H4', 'I4', 'J4', 'K4', 'L4'];
        foreach ($negritaCells as $cell) {
            $style = $sheet->getStyle($cell);
            $font = $style->getFont();
            $font->setBold(true);
        }

        $lineaSup = ['C11', 'C84', 'C86', 'C98'];
        foreach ($lineaSup as $linea) {
            $style = $sheet->getStyle($linea);
            $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        }

        //COCINA

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('COCINA');
        $sheet2->setCellValue('A1', 'LIQUIDACION COCINA');
        $sheet2->setCellValue('A2', 'DEL ' .date('d', strtotime($desde)). ' DE ' . strtoupper(date('F', strtotime($desde))) . ' AL ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
        $sheet2->mergeCells('A4'.':E4');
        $sheet2->setCellValue('A4', 'COBRANZA');
        $sheet2->getStyle('A4'.':E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->setCellValue('A5', 'PRODUCTO');
        $sheet2->setCellValue('B5', 'VECES');
        $sheet2->setCellValue('C5', 'PRECIO');
        $sheet2->setCellValue('D5', 'CANTIDAD');
        $sheet2->setCellValue('E5', 'IMPORTE');
        
        $row = 6;
        $total_cobranza_cocina = 0;
        foreach ($pedidos_cocina_cobranza as $pedido) {
            $sheet2->setCellValue('A'.$row, $pedido['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido['precio']);
            $sheet2->setCellValue('D'.$row, $pedido['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido['suma_totales']);
            $row++;
            $total_cobranza_cocina+=$pedido['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cobranza_cocina);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'CORTESIA');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_cortesia_cocina = 0;
        foreach ($pedidos_cocina_cortesia as $pedido_cortesia) {
            $sheet2->setCellValue('A'.$row, $pedido_cortesia['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_cortesia['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_cortesia['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_cortesia['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_cortesia['suma_totales']);
            $row++;
            $total_cortesia_cocina+=$pedido_cortesia['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cortesia_cocina);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'REPRESENTANTE');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_representante_cocina = 0;
        foreach ($pedidos_cocina_representante as $pedido_representante) {
            $sheet2->setCellValue('A'.$row, $pedido_representante['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_representante['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_representante['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_representante['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_representante['suma_totales']);
            $row++;
            $total_representante_cocina+=$pedido_representante['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_representante_cocina);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':G'.$row);
        $sheet2->setCellValue('A'.$row, 'DESC. TRABAJADOR');
        $sheet2->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $sheet2->setCellValue('F'.$row, 'TRABAJADOR');
        $sheet2->setCellValue('G'.$row, 'AUTORIZADOR');
        $row++;

        $total_desc_trabajador_cocina = 0;
        foreach ($pedidos_cocina_desc_trabajador as $pedido_desc_trabajador) {
            $sheet2->setCellValue('A'.$row, $pedido_desc_trabajador['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_desc_trabajador['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_desc_trabajador['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_desc_trabajador['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_desc_trabajador['suma_totales']);
            $sheet2->setCellValue('F'.$row, $pedido_desc_trabajador['desc_trab']);
            $sheet2->setCellValue('G'.$row, $pedido_desc_trabajador['autorizador']);
            $row++;
            $total_desc_trabajador_cocina+=$pedido_desc_trabajador['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_desc_trabajador_cocina);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        //BEBIDAS

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('BEBIDAS');
        $sheet2->setCellValue('A1', 'LIQUIDACION BEBIDAS');
        $sheet2->setCellValue('A2', 'DEL ' .date('d', strtotime($desde)). ' DE ' . strtoupper(date('F', strtotime($desde))) . ' AL ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
        $sheet2->mergeCells('A4'.':E4');
        $sheet2->setCellValue('A4', 'COBRANZA');
        $sheet2->getStyle('A4'.':E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->setCellValue('A5', 'PRODUCTO');
        $sheet2->setCellValue('B5', 'VECES');
        $sheet2->setCellValue('C5', 'PRECIO');
        $sheet2->setCellValue('D5', 'CANTIDAD');
        $sheet2->setCellValue('E5', 'IMPORTE');
        
        $row = 6;
        $total_cobranza_bebidas = 0;
        foreach ($pedidos_bebidas_cobranza as $pedido) {
            $sheet2->setCellValue('A'.$row, $pedido['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido['precio']);
            $sheet2->setCellValue('D'.$row, $pedido['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido['suma_totales']);
            $row++;
            $total_cobranza_bebidas+=$pedido['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cobranza_bebidas);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'CORTESIA');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_cortesia_bebidas = 0;
        foreach ($pedidos_bebidas_cortesia as $pedido_cortesia) {
            $sheet2->setCellValue('A'.$row, $pedido_cortesia['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_cortesia['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_cortesia['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_cortesia['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_cortesia['suma_totales']);
            $row++;
            $total_cortesia_bebidas+=$pedido_cortesia['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cortesia_bebidas);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'REPRESENTANTE');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_representante_bebidas = 0;
        foreach ($pedidos_bebidas_representante as $pedido_representante) {
            $sheet2->setCellValue('A'.$row, $pedido_representante['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_representante['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_representante['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_representante['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_representante['suma_totales']);
            $row++;
            $total_representante_bebidas+=$pedido_representante['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_representante_bebidas);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':G'.$row);
        $sheet2->setCellValue('A'.$row, 'DESC. TRABAJADOR');
        $sheet2->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $sheet2->setCellValue('F'.$row, 'TRABAJADOR');
        $sheet2->setCellValue('G'.$row, 'AUTORIZADOR');
        $row++;

        $total_desc_trabajador_bebidas = 0;
        foreach ($pedidos_bebidas_desc_trabajador as $pedido_desc_trabajador) {
            $sheet2->setCellValue('A'.$row, $pedido_desc_trabajador['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_desc_trabajador['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_desc_trabajador['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_desc_trabajador['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_desc_trabajador['suma_totales']);
            $sheet2->setCellValue('F'.$row, $pedido_desc_trabajador['desc_trab']);
            $sheet2->setCellValue('G'.$row, $pedido_desc_trabajador['autorizador']);
            $row++;
            $total_desc_trabajador_bebidas+=$pedido_desc_trabajador['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_desc_trabajador_bebidas);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        //COCTELERIA

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('COCTELERIA');
        $sheet2->setCellValue('A1', 'LIQUIDACION COCTELERIA');
        $sheet2->setCellValue('A2', 'DEL ' .date('d', strtotime($desde)). ' DE ' . strtoupper(date('F', strtotime($desde))) . ' AL ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
        $sheet2->mergeCells('A4'.':E4');
        $sheet2->setCellValue('A4', 'COBRANZA');
        $sheet2->getStyle('A4'.':E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->setCellValue('A5', 'PRODUCTO');
        $sheet2->setCellValue('B5', 'VECES');
        $sheet2->setCellValue('C5', 'PRECIO');
        $sheet2->setCellValue('D5', 'CANTIDAD');
        $sheet2->setCellValue('E5', 'IMPORTE');
        
        $row = 6;
        $total_cocteleria_cobranza = 0;
        foreach ($pedidos_cocteleria_cobranza as $pedido) {
            $sheet2->setCellValue('A'.$row, $pedido['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido['precio']);
            $sheet2->setCellValue('D'.$row, $pedido['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido['suma_totales']);
            $row++;
            $total_cocteleria_cobranza+=$pedido['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cocteleria_cobranza);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'CORTESIA');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_cocteleria_cortesia = 0;
        foreach ($pedidos_cocteleria_cortesia as $pedido_cortesia) {
            $sheet2->setCellValue('A'.$row, $pedido_cortesia['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_cortesia['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_cortesia['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_cortesia['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_cortesia['suma_totales']);
            $row++;
            $total_cocteleria_cortesia+=$pedido_cortesia['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cocteleria_cortesia);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':E'.$row);
        $sheet2->setCellValue('A'.$row, 'REPRESENTANTE');
        $sheet2->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $row++;

        $total_cocteleria_representante = 0;
        foreach ($pedidos_cocteleria_representante as $pedido_representante) {
            $sheet2->setCellValue('A'.$row, $pedido_representante['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_representante['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_representante['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_representante['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_representante['suma_totales']);
            $row++;
            $total_cocteleria_representante+=$pedido_representante['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cocteleria_representante);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        $row++;
        $row++;
        $sheet2->mergeCells('A'.$row.':G'.$row);
        $sheet2->setCellValue('A'.$row, 'DESC. TRABAJADOR');
        $sheet2->getStyle('A'.$row.':G'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $row++;
        $sheet2->setCellValue('A'.$row, 'PRODUCTO');
        $sheet2->setCellValue('B'.$row, 'VECES');
        $sheet2->setCellValue('C'.$row, 'PRECIO');
        $sheet2->setCellValue('D'.$row, 'CANTIDAD');
        $sheet2->setCellValue('E'.$row, 'IMPORTE');
        $sheet2->setCellValue('F'.$row, 'TRABAJADOR');
        $sheet2->setCellValue('G'.$row, 'AUTORIZADOR');
        $row++;

        $total_cocteleria_desc_trabajador = 0;
        foreach ($pedidos_cocteleria_desc_trabajador as $pedido_desc_trabajador) {
            $sheet2->setCellValue('A'.$row, $pedido_desc_trabajador['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido_desc_trabajador['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido_desc_trabajador['precio']);
            $sheet2->setCellValue('D'.$row, $pedido_desc_trabajador['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido_desc_trabajador['suma_totales']);
            $sheet2->setCellValue('F'.$row, $pedido_desc_trabajador['desc_trab']);
            $sheet2->setCellValue('G'.$row, $pedido_desc_trabajador['autorizador']);
            $row++;
            $total_cocteleria_desc_trabajador+=$pedido_desc_trabajador['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_cocteleria_desc_trabajador);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        //BOLETERIA

        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('BOLETERIA');
        $sheet2->setCellValue('A1', 'LIQUIDACION BOLETERIA');
        $sheet2->setCellValue('A2', 'DEL ' .date('d', strtotime($desde)). ' DE ' . strtoupper(date('F', strtotime($desde))) . ' AL ' . date('d', strtotime($hasta)) . ' DE ' . strtoupper(date('F', strtotime($hasta))));
        $sheet2->mergeCells('A4'.':E4');
        $sheet2->setCellValue('A4', 'COBRANZA');
        $sheet2->getStyle('A4'.':E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet2->setCellValue('A5', 'PRODUCTO');
        $sheet2->setCellValue('B5', 'VECES');
        $sheet2->setCellValue('C5', 'PRECIO');
        $sheet2->setCellValue('D5', 'CANTIDAD');
        $sheet2->setCellValue('E5', 'IMPORTE');
        
        $row = 6;
        $total_boleteria = 0;
        foreach ($pedidos_boleteria as $pedido) {
            $sheet2->setCellValue('A'.$row, $pedido['nombre_producto']);
            $sheet2->setCellValue('B'.$row, $pedido['cantidad']);
            $sheet2->setCellValue('C'.$row, $pedido['precio']);
            $sheet2->setCellValue('D'.$row, $pedido['cantidad_ven']);
            $sheet2->setCellValue('E'.$row, $pedido['suma_totales']);
            $row++;
            $total_boleteria+=$pedido['suma_totales'];
        }
        $sheet2->setCellValue('E'.$row, $total_boleteria);
        $style = $sheet2->getStyle('E'.$row);
        $font = $style->getFont();
        $font->setBold(true);
        $style->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);

        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getColumnIterator() as $column) {
                $worksheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte_de_Ventas.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
    
        exit;
    }

    public function pdfVentasDetalladas()
    {
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $id_usu = $_POST['usuario'];
        $getUsu = $this->model->getUsuarios($id_usu);
        if($id_usu != -1){
            $data = $this->model->getRangoFechas($desde, $hasta, $id_usu);
            $pagos_fecha = $this->model->getPagosFecha($desde, $hasta, $id_usu);
        }else{
            $data = $this->model->getRangoFechasGeneral($desde, $hasta);
            $pagos_fecha = $this->model->getPagosGeneral($desde, $hasta);
        }
    
        require('Libraries/fpdf/tabla_ventas.php');
        $pdf = new PDF_Reporte('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 7);
        $maxHeight = 640;
        $currentHeight = 0;
        $total = 0.00;
        $maxRowsPerPage = 20;
    
        foreach ($data as $row) {
            $cellHeight = 10;
    
            if ($currentHeight + $cellHeight > $maxHeight) {
                $pdf->AddPage();
                $currentHeight = 0;
            }

            
            $pdf->Cell(10, 4, $row['id'], 0, 0, 'C');
            $pdf->Cell(14, 4, date('d/m/Y', strtotime($row['fecha'])), 0, 0, 'L');
            if ($row['dni'] === null && $row['ruc'] !== null) {
                $pdf->Cell(15, 4, $row['ruc'], 0, 0, 'C');
                $pdf->Cell(55, 4, utf8_decode($row['razon_social']), 0, 0, 'L');
            } else {
                $pdf->Cell(15, 4, $row['dni'], 0, 0, 'C');
                $pdf->Cell(55, 4, utf8_decode($row['nombres'].' '.$row['apellido_paterno'].' '.$row['apellido_materno']), 0, 0, 'L');
            }
            $pdf->Cell(16, 4, $row['cconnumero'], 0, 0, 'R');
            $pdf->Cell(17, 4, $row['Fcfmanivel'], 0, 0, 'R');
            $pdf->Cell(9, 4, $row['Fcfmaserie'], 0, 0, 'R');
            $pdf->Cell(13, 4, $row['Fcfmanumero'], 0, 0, 'R');
            $sub_total = $row['total'];
            $pdf->Cell(19, 4, 'S/. ' . number_format($sub_total, 2), 0, 1, 'R');
            $currentHeight += $cellHeight;
            $total += $sub_total;
        }

        $pdf->Cell(0, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'R');
        $pdf->Cell(0, -10, 'Fecha: ' . $desde . ' / ' . $hasta, 0, 1, 'L');
        
        if ($id_usu == "-1") {
            $pdf->Cell(0, 20, 'RESUMEN GENERAL', 0, 1, 'L');
        } else {
            $pdf->Cell(0, 20, 'Vendedor: ' . $getUsu['nombre'], 0, 1, 'L');
        }
    
        if ($currentHeight + 100 > $maxHeight) {
            $pdf->AddPage();
            $currentHeight = 0;
        }
    
        $anchoPagina = $pdf->GetPageWidth();
        $tipo_pago_e = $anchoPagina * 0.138;
        $datos_tipop_e = $anchoPagina * 0.1;
        $pdf->SetFont('Arial', 'B', 9);
        $tipo_pago = $this->model->getTipoPagos();
        $pdf->SetFillColor(255, 255, 255, 0);
        $anchoTablaPagos = $tipo_pago_e + $datos_tipop_e * 2;
        $centrarTablaX = ($pdf->GetPageWidth() - $anchoTablaPagos) / 2;
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(71, 4, utf8_decode('RESUMEN DE PAGOS'), 0, 1, 'L');
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(29, 4, utf8_decode('TIPO PAGO'), 0, 0, 'L');
        $pdf->Cell(21, 4, utf8_decode('CANTIDAD'), 0, 0, 'C');
        $pdf->Cell(21, 4, utf8_decode('SUB TOTAL'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 7);
        foreach ($tipo_pago as $row_pago) {
            $pdf->SetX($centrarTablaX);
            $pdf->Cell($tipo_pago_e, 4, utf8_decode($row_pago['nombre']), 0, 0, 'L', true);
            $nombre_suma = 'cant_' . $row_pago['nombre'];
            $valor_suma = $pagos_fecha[0][$nombre_suma];
            $pdf->Cell($datos_tipop_e, 4, $valor_suma, 0, 0, 'C', true);
            $nombre_tipo_pago = $row_pago['nombre'];
            $valor_tipo_pago = $pagos_fecha[0][$nombre_tipo_pago];
            $pdf->Cell($datos_tipop_e, 4, 'S/. ' .$valor_tipo_pago, 0, 1, 'R', true);
        }
        $pdf->SetX($centrarTablaX);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(110, 10, 'Total: S/. ' . number_format($total, 2), 0, 1, 'C');
                    
        $pdf->Output("I", "Liquidacion_Ventas.pdf", true);
    }

    public function salir()
    {
        session_destroy();
        header("location:".base_url);
    }

    public function buscarProdPantalla($valor)
    {
        list($descripcion, $numero, $linea) = explode(',', $valor);
        $id_almacen = $_SESSION['id_almacen'];
        $data = $this->model->getBusquedaProdPantalla($descripcion, $id_almacen, $numero, $linea);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function consultaEstadoDocSunat($id_pedido)
    {

        $codigo_resp = '';
        $descripcion_resp = '';
        $hash = '';
        $ruta_base = $this->model->buscarRutaRPTA(1);
        $ruta_firma = $this->model->buscarRutaRPTA(2);
        if (is_dir($ruta_base['ruta_rpta'])) {
            $archivo_buscar = '-' . str_pad($id_pedido, 8, '0', STR_PAD_LEFT) . '.zip';
            $archivos = scandir($ruta_base['ruta_rpta']);
            foreach ($archivos as $archivo) {
                if (strpos($archivo, $archivo_buscar) !== false) {
                    $nombre_archivo_encontrado = $archivo;
                    break;
                }
            }
            
            if (isset($nombre_archivo_encontrado)) {
                $nombre_archivo_encontrado_xml = substr($nombre_archivo_encontrado, 0, -4) . '.xml';
                $con_guion = substr_replace($nombre_archivo_encontrado_xml, '-', 1, 0);
                $elementos_buscar = [
                    'cbc:ResponseCode',
                    'cbc:Description'
                ];
        
                $zip = new ZipArchive;
                if ($zip->open($ruta_base['ruta_rpta'].$nombre_archivo_encontrado) === TRUE) {
                    $contenido_xml = $zip->getFromName($con_guion);
                    
                    if ($contenido_xml !== false) {
                        $xml = new SimpleXMLElement($contenido_xml);
                        foreach ($elementos_buscar as $elemento) {
                            $resultados = $xml->xpath("//{$elemento}");
                            foreach ($resultados as $resultado) {
                                if($elemento === "cbc:ResponseCode"){
                                    $codigo_resp = (string)$resultado;
                                }else if($elemento === "cbc:Description"){
                                    $descripcion_resp =(string)$resultado;
                                }
                            }
                        }
                        
                    } else {
                        $data['res_post'] = "no";
                    }
                    $data['codigo'] = $codigo_resp;
                    $data['descripcion'] = $descripcion_resp;
                    $data['res_post'] = "si";
                    $zip->close();
                } else {
                    $data['res_post'] = "no";
                }
            } else {
                $data['res_post'] = "no";
            }
        } else {
            $data['res_post'] = "no";
        }

        if (is_dir($ruta_firma['ruta_firma'])) {
            $archivo_buscar = '-' . str_pad($id_pedido, 8, '0', STR_PAD_LEFT) . '.xml';
            $archivos = scandir($ruta_firma['ruta_firma']);
            foreach ($archivos as $archivo) {
                if (strpos($archivo, $archivo_buscar) !== false) {
                    $nombre_archivo_encontrado = $archivo;
                    break;
                }
            }
            
            if (isset($nombre_archivo_encontrado)) {
                $elementos_buscar = [
                    'ds:DigestValue'
                ];
                    $contenido_xml = file_get_contents($ruta_firma['ruta_firma'].$nombre_archivo_encontrado);
                    
                    if ($contenido_xml !== false) {
                        $xml = new SimpleXMLElement($contenido_xml);
                        foreach ($elementos_buscar as $elemento) {
                            $resultados = $xml->xpath("//{$elemento}");
                            foreach ($resultados as $resultado) {
                                $hash = (string)$resultado;
                            }
                        }
                        
                    } else {
                        $data['res_post'] = "no";
                    }
                    $data['hash'] = $hash;
            } else {
                $data['res_post'] = "no";
            }
        } else {
            $data['res_post'] = "no";
        }
        $tamaño = 10;
        $level = 'H';
        $frameSize = 3;
        $datos_pedido = $this->model->buscarPedidoSunat($id_pedido);
        $datos_empresa = $this->model->getRucEmpresa();
        $contenido = $datos_empresa['ruc']."|".$datos_pedido['Fcfmaserie']."|".$datos_pedido['Fcfmanumero']."|".$datos_pedido['igv']."|".$datos_pedido['total']."|".$datos_pedido['fecha'];
        ob_start();
        QRcode::png($contenido, null, $level, $tamaño, $frameSize);
        $image_data = ob_get_clean();
        if ($image_data === false) {
            $data['qr'] = null;
        } else {
            $base64_image = base64_encode($image_data);
            $qr_url = 'data:image/png;base64,' . $base64_image;
            $data['qr'] = $qr_url;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function solicitarToken(){
        $empresa['guias_client_id']                       = 'b8dce258-5ad8-40f8-8212-51e17b55e419';
        $empresa['guias_client_secret']                   = 'WlRMhT9/JDJ1sbHOJMpdrw==';
        $empresa['ruc']                                   = 20108572109;
        $empresa['usu_secundario_produccion_user']        = 'AN9F6THA';
        $empresa['usu_secundario_produccion_password']    = 'BALEno311811';
        $token_access = $this->token($empresa['guias_client_id'], $empresa['guias_client_secret'], $empresa['ruc'].$empresa['usu_secundario_produccion_user'], $empresa['usu_secundario_produccion_password']);
        echo "TOKEN =>".$token_access;
        $path = "guia_electronica/";
        $numero_ticket = $this->envio_xml($path.'FIRMA/','10481211641-09-TV50-11', $token_access);
        echo "NUMERO_TICKET =>".$numero_ticket;
        $respuesta_ticket = $this->envio_ticket($path.'CDR/', $numero_ticket, $token_access, $empresa['ruc'], '10481211641-09-TV50-11');
        echo var_dump($respuesta_ticket);
    }

    public function token($client_id, $client_secret, $usuario_secundario, $usuario_password){
        $url = "https://api-seguridad.sunat.gob.pe/v1/clientessol/".$client_id."/oauth2/token/";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);
    
        $datos = array(
                'grant_type'    =>  'password',     
                'scope'         =>  'https://api-cpe.sunat.gob.pe',
                'client_id'     =>  $client_id,
                'client_secret' =>  $client_secret,
                'username'      =>  $usuario_secundario,
                'password'      =>  $usuario_password
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($datos));
        curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookies.txt');
    
        $headers = array('Content-Type' => 'Application/json');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);
    
        $response = json_decode($result);
        return $response->access_token;  
    }

    function envio_xml($path, $nombre_file, $token_access){
        $curl = curl_init();
        $data = array(
                    'nomArchivo'  =>  $nombre_file.".zip",
                    'arcGreZip'   =>  base64_encode(file_get_contents($path.$nombre_file.'.zip')),
                    'hashZip'     =>  hash_file("sha256", $path.$nombre_file.'.zip')
                );
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/".$nombre_file,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode(array('archivo' => $data)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '. $token_access,
                'Content-Type: application/json'
            ),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $response2 = json_decode($result);
        return $response2->numTicket;
    }

    function envio_ticket($ruta_archivo_cdr, $ticket, $token_access, $ruc, $nombre_file){
        if(($ticket == "") || ($ticket == null)){
            $mensaje['cdr_hash'] = '';
            $mensaje['cdr_msj_sunat'] = 'Ticket vacio';
            $mensaje['cdr_ResponseCode']  = null;
            $mensaje['numerror'] = null;
        }else{
        
            $mensaje['ticket'] = $ticket;
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/'.$ticket,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'numRucEnvia: '.$ruc,
                    'numTicket: '.$ticket,
                    'Authorization: Bearer '. $token_access,
                ),
            ));
    
            $result  = curl_exec($curl);
            curl_close($curl);
            $response3  = json_decode($result);
            $codRespuesta = $response3->codRespuesta;
            $mensaje['ticket_rpta'] = $codRespuesta;
            if($codRespuesta == '99'){
                $error = $response3->error;
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = $error->desError;
                $mensaje['cdr_ResponseCode'] = '99';
                $mensaje['numerror'] = $error->numError;            	            
            }else if($codRespuesta == '98'){
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = 'Envío en proceso';
                $mensaje['cdr_ResponseCode']  = '98';
                $mensaje['numerror'] = '98';                        
            }else if($codRespuesta == '0'){
                $mensaje['arcCdr'] = $response3->arcCdr;
                $mensaje['indCdrGenerado'] = $response3->indCdrGenerado;
                
                file_put_contents($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP', base64_decode($response3->arcCdr));
    
                $zip = new ZipArchive;
                if ($zip->open($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP') === TRUE) {
                    $zip->extractTo($ruta_archivo_cdr);
                    $zip->close();
                }
                //unlink($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP');
    
             //=============hash CDR=================
                $doc_cdr = new DOMDocument();
                $doc_cdr->load($ruta_archivo_cdr . 'R-' . $nombre_file . '.xml');
                
                $mensaje['cdr_hash']            = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $mensaje['cdr_msj_sunat']       = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                $mensaje['cdr_ResponseCode']    = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;        
                $mensaje['numerror']            = '';
            }else{
                $mensaje['cdr_hash']            = '';
                $mensaje['cdr_msj_sunat']       = 'SUNAT FUERA DE SERVICIO';
                $mensaje['cdr_ResponseCode']    = '88';            
                $mensaje['numerror']            = '88';
            }
        }
        return $mensaje;
    }
}
?>