document.addEventListener("DOMContentLoaded", function () {
    $('#id_usuario').select2({
        theme: 'bootstrap4'
    });
});

function buscarPermisosUsu(e) {
    e.preventDefault();
    document.getElementById('permisosTree').classList.remove('d-none');
    document.getElementById('btnGuardar').classList.remove('d-none');
    let id = document.getElementById('id_usuario').value;
    const url = base_url + "Permisos/buscarPermisoUsuario/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            $(document).ready(function () {
                $('#permisosTree').jstree('destroy');

                $.getJSON(base_url + 'Permisos/getPermisosPadre/').done(function (data) {
                    var padres = data.permisos_padres;
                    var hijos = data.permisos_hijos;
                    var acciones = data.permisos_accion;

                    var treeData = padres.map(function (padre) {
                        var hijosData = hijos.filter(function (hijo) {
                            return hijo.id_permiso_padre === padre.id;
                        }).map(function (hijo) {
                            var accionesData = acciones.filter(function (accion) {
                                return accion.id_permiso_hijo === hijo.id;
                            }).map(function (accion) {
                                var isSelectedAccion = res.some(item => item.id_permiso_accion === accion.id);
                                return {
                                    text: accion.permiso_accion,
                                    icon: false,
                                    state: { opened: false, selected: isSelectedAccion },
                                    data: { 'id_accion': accion.id }
                                };
                            });

                            var isSelectedHijo = res.some(item => item.id_permiso_hijo === hijo.id);
                            return {
                                text: hijo.permiso_hijo,
                                icon: false,
                                state: { opened: true, selected: isSelectedHijo },
                                children: accionesData.length > 0 ? accionesData : undefined,
                                data: { 'id_hijo': hijo.id }
                            };
                        });

                        var isSelectedPadre = res.some(item => item.id_permiso_padre === padre.id && item.id_permiso_hijo === null);
                        return {
                            text: padre.permiso,
                            icon: false,
                            state: { opened: true, selected: isSelectedPadre },
                            children: hijosData.length > 0 ? hijosData : undefined,
                            data: { 'id_padre': padre.id }
                        };
                    });

                    $('#permisosTree').jstree({
                        'plugins': ['checkbox'],
                        'checkbox': {
                            'three_state': false
                        },
                        'core': {
                            'data': treeData
                        }
                    });
                }).fail(function (xhr, status, error) {
                    console.error('Error al obtener datos:', status, error);
                });
            });
        }
    }
}

function registrarPermiso(e) {
    e.preventDefault();
    var formData = new FormData(document.getElementById("frmUsuarioPermiso"));
    var permisosSeleccionados = $('#permisosTree').jstree(true).get_selected(true);

    var idPadres = [];
    var idHijos = [];
    var idAcciones = [];

    permisosSeleccionados.forEach(function (nodo) {
        if (nodo.data && nodo.data['id_padre'] !== undefined) {
            idPadres.push(nodo.data['id_padre']);
        }

        if (nodo.data && nodo.data['id_hijo'] !== undefined) {
            idHijos.push(nodo.data['id_hijo']);
        }

        if (nodo.data && nodo.data['id_accion'] !== undefined) {
            idAcciones.push(nodo.data['id_accion']);
        }
    });

    var permisosData = [];

    if (idPadres.length > 0) {
        permisosData.push({ id_padre: idPadres });
    }

    if (idHijos.length > 0) {
        permisosData.push({ id_hijo: idHijos });
    }

    if (idAcciones.length > 0) {
        permisosData.push({ id_accion: idAcciones });
    }

    formData.append('permisos', JSON.stringify(permisosData));
    const url = base_url + "Permisos/registrarPermisos";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(formData);
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res == "seleccion"){
                alertas('Por favor, seleccione un permiso antes de guardar', 'warning');
            }else{
                alertas(res.msg, res.icono);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        }
    }
}