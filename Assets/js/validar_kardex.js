document.addEventListener("DOMContentLoaded", function() {
    const url = base_url + "Productos/verValidacionKardex/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res.msg == "no"){
                document.getElementById("boton_kardex_validado").classList.add("d-none");
                document.getElementById("boton_kardex_sin_validar").classList.remove("d-none");
            }else if(res.msg == "si"){
                document.getElementById("boton_kardex_validado").classList.remove("d-none");
                document.getElementById("boton_kardex_sin_validar").classList.add("d-none");
            }
        }
    }
});

function validarKardex()
{
    const url = base_url + "Productos/validarKardexTerminal/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res.icono == "success"){
                alertas(res.msg, res.icono);
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }else{
                alertas(res.msg, res.icono);
            }
        }
    }
}