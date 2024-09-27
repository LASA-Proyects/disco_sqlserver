function frmLogin(e){
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const clave = document.getElementById("clave");
    if(usuario.value == ""){
        clave.classList.remove("is-invalid");
        usuario.classList.add("is-invalid");
        usuario.focus();
    }else if(clave.value == ""){
        usuario.classList.remove("is-invalid");
        clave.classList.add("is-invalid");
        clave.focus();
    }else{
        const url = base_url + "Login/validar";
        const frm = document.getElementById("frmLogin");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                    if(res == 3){
                        window.location = base_url + "Terminal/index";
                    }else if(res == 1 || res == 2 || res == 5){
                        window.location = base_url + "Configuracion/home";
                    }else if(res == 6 || res == 7){
                        window.location = base_url + "Terminal/mboleteria";
                    }else{
                    document.getElementById("alerta").classList.remove("d-none");
                    document.getElementById("alerta").innerHTML = res;
                }
            }
        }
    }
}

$(document).ready(function() {
    // Eliminar cualquier evento de escucha previo
    $("#usuario_select").off("change").on("change", function () {
        var id = $(this).val();
        const url = base_url + "Login/buscarUsuario/" + id;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res.tipo_usuario == 1 || res.tipo_usuario == 2 || res.tipo_usuario == 5){
                    document.getElementById("usuario").value = res.usuario;
                    document.getElementById("clave").value = '';
                    document.getElementById("inpu_clave").classList.remove("d-none");
                }else{
                    document.getElementById("inpu_clave").classList.add("d-none");
                    document.getElementById("usuario").value = '';
                    document.getElementById("usuario").value = res.usuario;
                    document.getElementById("clave").value = res.clave;
                }
            }
        }
        buscarAlmacenes(id);
    });
});

function buscarAlmacenes(id)
{
    const almacenSelect = document.getElementById("almacen");
    almacenSelect.innerHTML = '';
    const url = base_url + "Login/buscarAlmacenesPorUsuario/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const almacenes = JSON.parse(this.responseText);
            almacenes.forEach(function(almacen) {
                const option = document.createElement("option");
                option.value = almacen.id_almacen;
                option.textContent = almacen.nombre_almacen;
                almacenSelect.appendChild(option);
            });
        }
    }
}