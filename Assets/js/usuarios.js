let tblUsuarios;
document.addEventListener("DOMContentLoaded", function () {
    tblUsuarios = $("#tblUsuarios").DataTable({
        ajax: {
            url: base_url + "Usuarios/listar",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'num_doc'
            },
            {
                'data' : 'usuario'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 't_usu_nom'
            },
            {
                'data' : 'codigo_vendedor'
            },
            {
                'data' : 'estado'
            },
            {
                'data' : "acciones"
            }
        ],
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
  });

function frmUsuario(e) {
    document.getElementById("title").innerHTML = "Nuevo Usuario";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("claves").classList.remove("d-none");
    document.getElementById("frmUsuario").reset();
    $("#nuevo_usuario").modal("show");
    $('#tipo_usuario').select2({
        theme: 'bootstrap4'
    });
    document.getElementById("id").value = "";    
}
function registrarUser(e){
    e.preventDefault();
    const usuario = document.getElementById("usuario");
    const nombre = document.getElementById("nombre");
    const dni = document.getElementById("num_doc").value;
    const regex = /^[A-Za-z]+$/;
    if(usuario.value == "" || nombre.value == ""){
        alertas('Todos los campos son obligatorios', 'warning');
    }else if(dni.length < 8){
        alertas('Ingresar Dni Válido', 'warning');
    }else if(!regex.test(nombre.value)){
        nombre.value = nombre.value.replace(/[^A-Za-z]/g, '');
        alertas('El Nombre no puede tener caracteres especiales', 'warning');
    }else{
        const url = base_url + "Usuarios/registrar";
        const frm = document.getElementById("frmUsuario");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $("#nuevo_usuario").modal("hide");
                alertas(res.msg, res.icono);
                tblUsuarios.ajax.reload();
            }
        }
    }
}
function btnEditarUser(id){
    document.getElementById("title").innerHTML = "Modificar Usuario";
    document.getElementById("btnAccion").innerHTML = "Modificar";
    const url = base_url + "Usuarios/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("num_doc").value = res.num_doc;
            document.getElementById("usuario").value = res.usuario;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("tipo_usuario").value = res.tipo_usuario;
            document.getElementById("codigo_vendedor").value = res.codigo_vendedor;
            $("#nuevo_usuario").modal("show");
        }
    }
}
function btnEstadoUser(id) {
    Swal.fire({
        title: 'Estas seguro?',
        text: "Se cambiará el estado del usuario",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, desactivar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/desactivar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblUsuarios.ajax.reload();
                }
            }
        }
      })
}
function btnActivarUser(id) {
    Swal.fire({
        title: 'Esta seguro?',
        text: "Se cambiará el estado del usuario",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, desactivar!',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/activar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblUsuarios.ajax.reload();
                }
            }
        }
      })
}

function btnEliminarUsuario(id){
    Swal.fire({
        title: 'Esta seguro?',
        text: "Se eliminará el usuario de forma permanente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Usuarios/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.readyState == 4 && this.status == 200){
                        const res = JSON.parse(this.responseText);
                        alertas(res.msg, res.icono);
                        tblUsuarios.ajax.reload();
                    }
                }
            }
        }
      })
}

function registrarPermisos(e){
    e.preventDefault();
    const url = base_url + "Usuarios/registrarPermisos/";
    const frm = document.getElementById('formulario');
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res != ''){
                alertas(res.msg, res.icono);
            }else{
                alertas('Error no identificado', 'error');
            }
        }
    }
}

function registrarPermisosAlmacenes(e){
    e.preventDefault();
    const url = base_url + "Usuarios/registrarPermisosAlmc/";
    const frm = document.getElementById('formularioAlmc');
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res != ''){
                alertas(res.msg, res.icono);
            }else{
                alertas('Error no identificado', 'error');
            }
        }
    }
}

function btnAsignarIngreso(id){
    const url = base_url + "Usuarios/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("id_usu").value = res.id;
            document.getElementById("fecha_ini").value = res.fecha_ini;
            document.getElementById("hora_ini").value = res.hora_ini;
            document.getElementById("fecha_fin").value = res.fecha_fin;
            document.getElementById("hora_fin").value = res.hora_fin;
            $("#asignar_ingreso").modal("show");
        }
    }
}

function registrarFechas(){
    const url = base_url + "Usuarios/registrarFechasUsu";
    const frm = document.getElementById("frmFechaPermi");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            $("#asignar_ingreso").modal("hide");
            alertas(res.msg, res.icono);
            tblUsuarios.ajax.reload();
        }
    }
}