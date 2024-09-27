document.addEventListener("DOMContentLoaded", function() {
    const url = base_url + "Arqueo/consutlarEstados/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res === false || (typeof res === 'object' && Object.keys(res).length === 0)){
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'VALIDACIONES PENDIENTES',
                    text: 'Por favor, Validar Caja y Kardex para realizar Ventas',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }else{
                document.getElementById("babidas_boton").classList.remove("d-none");
                document.getElementById("cocteleria_boton").classList.remove("d-none");
                document.getElementById("cocina_boton").classList.remove("d-none");
            }
        }
    }
});