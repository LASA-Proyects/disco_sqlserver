function consultaGuiasTxt() {
    const url = base_url + "Facturacion/registrarPorTxt";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if (this.responseText.trim() !== '') {
                try {
                    const res = JSON.parse(this.responseText);
                    if (res.msg == "ok") {
                        tblMonitorGuiasElect.ajax.reload();
                    }
                } catch (e) {
                    console.error("Error al parsear JSON:", e);
                }
            }
        }
    }
}

let tblMonitorGuiasElect;
document.addEventListener("DOMContentLoaded", function () {
    tblMonitorGuiasElect = $("#tblMonitorGuiasElect").DataTable({
        ajax: {
            url: base_url + "Facturacion/listarGuiasElectronicas",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'destinatario'
            },
            {
                'data' : 'serie'
            },
            {
                'data' : 'numero'
            },
            {
                'data' : 'ticket'
            },
            {
                'data' : 'xml'
            },
            {
                'data' : 'acciones'
            },
            {
                'data' : 'respuesta_sunat_codigo'
            },
            {
                'data' : 'acciones'
            }
        ],
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
    setInterval(consultaGuiasTxt, 7000);
  });

  function btnVerTicketGuia(id)
  {
    const url = base_url + "Facturacion/consultarTicketGuia/"+id;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res.ticket_guia !== undefined && res.ticket_guia !== null && res.ticket_guia !== ""){
                document.getElementById('numero_ticket').value = res.ticket_guia;
                $("#ver_ticket_guia").modal("show");
            }else{
                alertas('La Gúia no tiene un TICKET para mostrar', 'warning');
            }
        }
    }
  }

  function btnProcesarSunat(nombre_archivo)
  {
    const url = base_url + "Facturacion/procesarSunat/"+nombre_archivo;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if (this.responseText.trim() !== '') {
                try {
                    const res = JSON.parse(this.responseText);
                    if (res.msg == "ok") {
                        tblMonitorGuiasElect.ajax.reload();
                    }
                } catch (e) {
                    console.error("Error al parsear JSON:", e);
                }
            }
        }
    }
  }

  function btnVerXml(nombre_archivo)
  {
    
  }
