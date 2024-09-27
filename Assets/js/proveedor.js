let tblProveedor;
document.addEventListener("DOMContentLoaded", function () {
    tblProveedor = $("#tblProveedor").DataTable({
        ajax: {
            url: base_url + "Contactos/listar",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'dni'
            },
            {
                'data' : 'ruc'
            },
            {
                'data' : 'apellidoPaterno'
            },
            {
                'data' : 'nombres'
            },
            {
                'data' : "razon_social"
            },
            {
                'data' : "tipo_contacto"
            },
            {
                'data' : "fecha_alta"
            },
            {
                'data' : "fecha_cese"
            },
            {
                'data' : "estado"
            },
            {
                'data' : "acciones"
            }
    ],
    "responsive": true, "lengthChange": false, "autoWidth": false
    });
  });
function frmProveedor(e) {
    document.getElementById("title").innerHTML = "Nuevo Proveedor";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("frmProveedor").reset();

    document.getElementById('tipo_documento').addEventListener("change", function() {
        var valorSeleccionado = this.value; 
    
        if (valorSeleccionado == 1) {
            document.getElementById('campos_ruc').classList.add('d-none');
            document.getElementById('campos_dni').classList.remove('d-none');
            document.getElementById('ruc').value = "";
            document.getElementById('razon_social').value = "";
            document.getElementById('direccion').value = "";

        } else if(valorSeleccionado == 2){
            document.getElementById('campos_dni').classList.add('d-none');
            document.getElementById('campos_ruc').classList.remove('d-none');
            document.getElementById('dni').value = "";
            document.getElementById('nombres').value = "";
            document.getElementById('apellidoPeterno').value = "";
            document.getElementById('apellidoMaterno').value = "";
        }
    });

    document.getElementById('tipo_persona').addEventListener("change", function() {
        var valorSeleccionadoPersona = this.value; 
        if (valorSeleccionadoPersona == 3) {
            document.getElementById('data_chofer').classList.remove('d-none');
            document.getElementById('data_transporte').classList.add('d-none');
            document.getElementById('numero_mtc').value = "";
        }else if(valorSeleccionadoPersona == 4){
            document.getElementById('data_chofer').classList.add('d-none');
            document.getElementById('data_transporte').classList.remove('d-none');
            document.getElementById('licencia').value = "";
            document.getElementById('placa').value = "";
        }else{
            document.getElementById('data_chofer').classList.add('d-none');
            document.getElementById('data_transporte').classList.add('d-none');
            document.getElementById('numero_mtc').value = "";
            document.getElementById('licencia').value = "";
            document.getElementById('placa').value = "";
        }
    });

    $("#nuevo_proveedor").modal("show");
    document.getElementById("id").value = "";
}
function registrarProveedor(e){
    e.preventDefault();
    const correo = document.getElementById("correo").value;
    var correoRegExp = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(correo != ''){
        if(!correoRegExp.test(correo)){
            alertas("Por favor ingrese un correo electrónico válido", "warning");
        }else{
            const url = base_url + "Contactos/registrar";
            const frm = document.getElementById("frmProveedor");
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    $("#nuevo_proveedor").modal("hide");
                    alertas(res.msg, res.icono);
                    tblProveedor.ajax.reload();
                }
            }
        }
    }else{
        const url = base_url + "Contactos/registrar";
        const frm = document.getElementById("frmProveedor");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $("#nuevo_proveedor").modal("hide");
                alertas(res.msg, res.icono);
                tblProveedor.ajax.reload();
            }
        }
    }
}
function btnEditarProveedor(id){
    document.getElementById("title").innerHTML = "Editar Proveedor";
    document.getElementById("btnAccion").innerHTML = "Modificar";
    document.getElementById('tipo_documento').addEventListener("change", function() {
        var valorSeleccionado = this.value; 
    
        if (valorSeleccionado == 1) {
            document.getElementById('campos_ruc').classList.add('d-none');
            document.getElementById('campos_dni').classList.remove('d-none');
            document.getElementById('ruc').value = "";
            document.getElementById('razon_social').value = "";
            document.getElementById('direccion').value = "";

        } else if(valorSeleccionado == 2){
            document.getElementById('campos_dni').classList.add('d-none');
            document.getElementById('campos_ruc').classList.remove('d-none');
            document.getElementById('dni').value = "";
            document.getElementById('nombres').value = "";
            document.getElementById('apellidoPeterno').value = "";
            document.getElementById('apellidoMaterno').value = "";
        }
    });
    const url = base_url + "Contactos/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res.ruc && !res.dni){
                document.getElementById('campos_ruc').classList.remove('d-none');
                document.getElementById('campos_dni').classList.add('d-none');
                document.getElementById("id").value = res.id;
                document.getElementById("ruc").value = res.ruc;
                document.getElementById("razon_social").value = res.razon_social;
                document.getElementById("correo").value = res.correo;
                document.getElementById("direccion").value = res.direccion;
                document.getElementById("tipo_persona").value = res.id_tipo_persona;
                document.getElementById("ruc_modificar").value = res.ruc;
                $("#nuevo_proveedor").modal("show");
            }else if(res.dni && !res.ruc){
                document.getElementById('campos_ruc').classList.add('d-none');
                document.getElementById('campos_dni').classList.remove('d-none');
                document.getElementById("correo").value = res.correo;
                document.getElementById("id").value = res.id;
                document.getElementById("dni").value = res.dni;
                document.getElementById("apellidoPeterno").value = res.apellidoPaterno;
                document.getElementById("apellidoMaterno").value = res.apellidoMaterno;
                document.getElementById("nombres").value = res.nombres;
                document.getElementById("tipo_persona").value = res.id_tipo_persona;
                document.getElementById("dni_modificar").value = res.dni;
                $("#nuevo_proveedor").modal("show");
            }
        }
    }
}

function btnEstadoProveedor(id) {
    Swal.fire({
        title: 'Estas seguro?',
        text: "Se cambiará el estado del Proveedor",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, desactivar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Contactos/desactivar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProveedor.ajax.reload();
                }
            }
        }
      })
}
function btnActivarProveedor(id) {
    Swal.fire({
        title: 'Estas seguro?',
        text: "Se cambiará el estado del Proveedor",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, activar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Contactos/activar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProveedor.ajax.reload();
                }
            }
        }
      })
}

function btnEliminarProveedor(id){
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Contactos/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.readyState == 4 && this.status == 200){
                        const res = JSON.parse(this.responseText);
                        alertas(res.msg, res.icono);
                        tblProveedor.ajax.reload();
                    }
                }
            }
        }
      })
}

function consultaRuc(e)
{
    e.preventDefault();
    const ruc = document.getElementById("ruc").value;
    if(ruc != ''){
        const ruc = document.getElementById("ruc").value;
        const url = base_url + "Pedidos/consultaRUC/"+ruc;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if('razon_social' in res){
                    document.getElementById("razon_social").value = res.razon_social;
                }else if('razonSocial' in res){
                    document.getElementById("razon_social").value = res.razonSocial;
                }
                document.getElementById("direccion").value = res.direccion;
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR RUC',
        });
    }
}

function consultaDni(e) {
    e.preventDefault();
    const dni = document.getElementById("dni").value;
    if(dni != ''){
        const dni = document.getElementById("dni").value;
        const url = base_url + "Pedidos/consultaDNI/"+dni;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                document.getElementById("nombres").value = res.nombres;
                document.getElementById("apellidoPeterno").value = res.apellidoPaterno;
                document.getElementById("apellidoMaterno").value = res.apellidoMaterno;
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR DNI',
        });
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblProveedor').DataTable();
        var cabeceras = [];
        $('#tblProveedor thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblProveedor tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('CONTACTOS');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});