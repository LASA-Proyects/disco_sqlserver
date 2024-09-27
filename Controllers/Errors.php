<?php
class Errors extends Controller{
    public function index()
    {
        $this->views->getView($this, 'index');
    }
    public function permisos()
    {
        $this->views->getView($this, 'permisos');
    }

    public function permisos_analitico()
    {
        $this->views->getView($this, 'permisos_analitico');
    }
}
?>