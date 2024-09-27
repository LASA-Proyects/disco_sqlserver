let tblFamilia;
document.addEventListener("DOMContentLoaded", function(){
    tblFamilia = $('#tblFamilia').DataTable({
                ajax: {
                    url: base_url + "Familia/listar",
                    dataSrc: '',
                    contentType: "application/json; charset=utf-8"
                },
                columns: [
                {
                    'data' : 'id'
                },
                {
                    'data' : 'imagen'
                },
                {
                    'data' : 'nombre'
                },
                {
                    'data' : "acciones"
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false
    });
    
})

function frmFamilia(e) {
    document.getElementById("title").innerHTML = "Nueva Familia";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("frmFamilia").reset();
    $("#nueva_familia").modal("show");
    document.getElementById("id").value = "";    
}
function registrarFamilia(e){
    e.preventDefault();
    const nombre = document.getElementById("nombre");
    if(nombre.value == ""){
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 1500
          })
    }else{
        const url = base_url + "Familia/registrar";
        const frm = document.getElementById("frmFamilia");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res == "si"){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Familia registrada con éxito',
                        showConfirmButton: false,
                        timer: 1500
                      })
                      frm.reset();
                      $("#nueva_familia").modal("hide");
                      tblFamilia.ajax.reload();
                }else if(res == "modificado"){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Familia modificada con éxito',
                        showConfirmButton: false,
                        timer: 1500
                      })
                      $("#nueva_familia").modal("hide");
                      tblFamilia.ajax.reload();
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: res,
                        showConfirmButton: false,
                        timer: 1500
                      })
                }
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblFamilia').DataTable();
        var cabeceras = [];
        $('#tblFamilia thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblFamilia tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('FAMILIAS');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});

function btnEditarFamilia(id){
    document.getElementById("title").innerHTML = "Editar Familia";
    document.getElementById("btnAccion").innerHTML = "Modificar";
    const url = base_url + "Familia/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("nombre").value = res.nombre;
            document.getElementById("linea").value = res.id_linea;
            document.getElementById("img-preview").src = base_url + 'Assets/img/'+ res.foto;
            document.getElementById("icon-cerrar").innerHTML = `<button class="btn btn-danger" onclick="deleteImg()"><i class="fas fa-times"></i></button>`;
            document.getElementById("icon-image").classList.add("d-none");
            document.getElementById("foto_actual").value = res.foto;
            $("#nueva_familia").modal("show");
        }
    }
}

function btnEliminarFamilias(id){
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
            const url = base_url + "Familia/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.readyState == 4 && this.status == 200){
                        const res = JSON.parse(this.responseText);
                        alertas(res.msg, res.icono);
                        tblFamilia.ajax.reload();
                    }
                }
            }
        }
      })
}

function preview(e){
    const url = e.target.files[0];
    const urlTpm = URL.createObjectURL(url);
    document.getElementById("img-preview").src = urlTpm;
    document.getElementById("icon-image").classList.add("d-none");
    document.getElementById("icon-cerrar").innerHTML = `<button class="btn btn-danger" onclick="deleteImg()"><i class="fas fa-times"></i></button>${url['name']}`;
}
function deleteImg(){
    document.getElementById("icon-cerrar").innerHTML = '';
    document.getElementById("icon-image").classList.remove("d-none");
    document.getElementById("img-preview").src = '';
    document.getElementById("imagen").value = '';
    document.getElementById("foto_actual").value = '';
}