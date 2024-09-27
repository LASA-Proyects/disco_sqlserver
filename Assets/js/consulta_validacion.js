let redireccionGlobal;
function consultarValidacion(id_opcion, redireccion){
    const url = base_url + "Terminal/consultar_verificacion/"+id_opcion;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res.afecta_clave == 1){
                solicitarClave();
                redireccionGlobal = redireccion;
            }else{
                window.location.href = base_url + redireccion;
            }
        }
    }
}

function solicitarClave()
{
    $("#solicitarClave").modal("show");
}

function verificarContrasena()
{
    const input_clave = document.getElementById("clave").value;
    if(input_clave == ''){
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'POR FAVOR, INGRESE CLAVE',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        return;
    }else{
        const url = base_url + "Terminal/verificarContrasena";
        const frm = document.getElementById("frmVerificarContrasena");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res == false){
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'LA CLAVE INGRESADA NO ES VÁLIDA',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                    return;
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'VERIFICADO',
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.href = base_url + redireccionGlobal;
                    }, 1000);
                }
            }
        }
    }
}