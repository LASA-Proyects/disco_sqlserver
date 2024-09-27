let tblCargaInvitados;
document.addEventListener("DOMContentLoaded", function () {
    tblCargaInvitados = $("#tblCargaInvitados").DataTable({
        ajax: {
            url: base_url + "Carga/listar",
            dataSrc: ''
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre_usuario'
            },
            {
                'data' : 'dni'
            },
            {
                'data' : 'apellidoPaterno'
            },
            {
                'data' : 'apellidoMaterno'
            },
            {
                'data' : 'nombres'
            },
            {
                'data' : 'nombre_mesa'
            },
            {
                'data': 'fecha_asist'
            },
            {
                'data': 'hora_asist'
            },
            {
                'data' : 'estado'
            }
    ],
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    document.getElementById('pdfButton').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Carga/pdfPorFechas";
        document.getElementById('exportForm').submit();
    });

  });
  
function frmCarga() {
    document.getElementById("frmCarga").reset();
    $("#nueva_carga").modal("show"); 
}

function frmInvitado(){
    document.getElementById("frmInvitado").reset();
    $("#nuevo_invitado").modal("show"); 
}

function importarDesdeExcel(e)
{
    e.preventDefault();
    const url = base_url + "Carga/registrar";
    const frm = document.getElementById("frmCarga");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            $("#nueva_carga").modal("hide");
            alertas(res.msg, res.icono);
            tblCargaInvitados.ajax.reload();
        }
    }
}

function registrarInvitado(e)
{
    e.preventDefault();
    const apellidoMaterno = document.getElementById("a_materno");
    const apellidoPaterno = document.getElementById("a_paterno");
    const nombres = document.getElementById("nombre_inv");
    if(apellidoMaterno.value == "" || apellidoPaterno.value == "" || nombres.value == ""){
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 1500
          })
    }else{
        const url = base_url + "Carga/registrarInv";
        const frm = document.getElementById("frmInvitado");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res['icono'] === 'success'){
                    $("#nuevo_invitado").modal("hide");
                    alertas(res.msg, res.icono);
                    tblCargaInvitados.ajax.reload();
                }else{
                    alertas(res['msg'], res['icono']);
                }
            } else {
                console.error("Error en la solicitud. Estado:", this.status);
            }
        }
    }
}

function btnAsistencia(id)
{
    const url = base_url + "Carga/marcarAsist/"+id;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            tblCargaInvitados.ajax.reload();
        }
    }
}


function buscarInvitado() {
    const id_promotor = document.getElementById("promotor").value;
    const url = base_url + "Carga/buscarInvitado/" + id_promotor;
    const frm = document.getElementById("frmbuscarInvitado");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if (res.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No hay invitados',
                    text: 'No hay invitados para mostrar de este promotor.',
                });
            } else {
                let html = '';
                res.forEach(row => {
                    html += `<tr>
                        <td>${row.apellidoPaterno}</td>
                        <td>${row.apellidoMaterno}</td>
                        <td>${row.nombres}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" data-id-asistencia="${row.id}">
                        </td>
                    </tr>`;
                });
                document.querySelector('#tblDetalleAsist').innerHTML = html;
            }
        }
    }
}

function capturarIdUsuario(e) {
    e.preventDefault();
    let id_asistencias = [];
    const checkboxes = document.querySelectorAll('.form-check-input:checked');
    checkboxes.forEach(checkbox => {
        const id_asistencia = checkbox.getAttribute('data-id-asistencia');
        id_asistencias.push(id_asistencia);
    });

    if(id_asistencias.length >0){
        const url = base_url + "Carga/marcarAsistencia";
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(JSON.stringify(id_asistencias));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                alertas(res.msg,res.icono);
                setTimeout(function() {
                    location.reload();
                }, 500);
            }
        }
    }else{
        alertas("Seleccione un Invitado", "warning");
    }
}