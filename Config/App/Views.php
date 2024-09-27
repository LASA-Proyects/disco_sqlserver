<?php
class Views{
    public function getView($controller, $vista, $data="")
    {
        $controller = get_class($controller);
        if ($controller == "Home") {
            $vista = "Views/".$vista.".php";
        }else{
            $vista = "Views/".$controller."/".$vista.".php";
        }
        require $vista;
    }
}
?>