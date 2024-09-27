let tblTomaInv;
document.addEventListener("DOMContentLoaded",function(){
    tblTomaInv = $("#tblTomaInv").DataTable({
        ajax: {
            url: base_url + "Productos/listarTomaInv",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre_usuario'
            },
            {
                'data' : 'fecha_descarga'
            },
            {
                'data' : 'fecha_subida'
            },
            {
                'data' : 'fecha_proceso'
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
    $('.almacenes').select2({
        theme: 'bootstrap4'
    });
})

document.getElementById('tomaInv').addEventListener('click', function () {
    document.getElementById('tomaInvForm').action = base_url + "Productos/TomaInvExcelExport";
    document.getElementById('tomaInvForm').submit();
    alertas("Toma Generada Correctamente", "success");
    tblTomaInv.ajax.reload();
});

function ImpTomaInvExcelExport(id)
{
    window.location.href = base_url + "Productos/ImpTomaInvExcelExport/" + id;
}

function btnSubirTomaInv(id)
{
    $("#archivoExcel").val('');
    $("#subida").modal("show");
    document.getElementById("id").value = id;
}

function importar() {
    document.getElementById("loader").style.display = "block";
    const id = document.getElementById("id").value;
    const url = base_url + "Productos/importar/"+id;
    const frm = document.getElementById("frmImportar");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("loader").style.display = "none";
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            tblTomaInv.ajax.reload();
            $("#subida").modal("hide");
        }
    }
}

function btnProcesarTomaInv(id){
    document.getElementById("loader").style.display = "block";
    const url = base_url + "Productos/procesarTomaInv/"+id;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            document.getElementById("loader").style.display = "none";
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            tblTomaInv.ajax.reload();
        }
    }
}

function btnVerEstadoTomaInv(id)
{
    const url = base_url + "Productos/verEstadoTomaInv/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let html = '';
            let num = 1;
            res.forEach(row => {
                html += `<tr>
                    <td>${num}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.unidad_med}</td>
                    <td>${row.almacen_nombre}</td>
                    <td>${row.stock_actual_sys}</td>
                    <td>${row.stock_fisico}</td>
                    <td>${row.resta}</td>
                    <td>${row.stock_fisico !== 0 ? (row.serie === "-" || row.correlativo === "-" ? "-" : (row.serie + ' - ' + row.correlativo)) : "-"}</td>
                    <td>${row.stock_fisico !== 0 ? row.tabla : "-"}</td>
                    </tr>`;
                num++;
            });
            if ($.fn.DataTable.isDataTable('#tblTomaInvId')) {
                $('#tblTomaInvId').DataTable().destroy();
            }
            document.querySelector('#tblTomaInvIdJs').innerHTML = html;
            const tblTomaInvId = $('#tblTomaInvId').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });

            $("#diferencia_flt").change(function() {
                const selectedIndex = this.selectedIndex;
                let searchValue = '';
            
                switch (selectedIndex) {
                    case 1:
                        searchValue = '^\\d+(\\.\\d+)?$';
                        break;
                    case 2:
                        searchValue = '-\\d+(\\.\\d+)?$';
                        break;
                    case 3:
                        searchValue = '^0+(\\.0+)?$';
                        break;
                    case 4:
                        searchValue = '';
                        break;
                    default:
                        searchValue = '';
                        break;
                }
            
                tblTomaInvId.column(6).search(searchValue, true, false).draw();
            });

            $("#movimientos_flt").change(function() {
                const selectedIndex = this.selectedIndex;
                let searchValue = '';
            
                switch (selectedIndex) {
                    case 1:
                        searchValue = 'INGRESO';
                        break;
                    case 2:
                        searchValue = 'SALIDA';
                        break;
                    case 3:
                        searchValue = '';
                        break;
                    default:
                        searchValue = '';
                        break;
                }
            
                tblTomaInvId.column(8).search(searchValue).draw();
            });

            document.getElementById('excelDetalladoButton').addEventListener('click', function () {
                var table = $('#tblTomaInvId').DataTable();
                var cabeceras = [];
                $('#tblTomaInvId thead th').each(function() {
                    cabeceras.push($(this).text());
                });
                var contenido = [];
                for (var i = 0; i < table.page.info().pages; i++) {
                    table.page(i).draw('page');
                    $('#tblTomaInvId tbody tr').each(function() {
                        var fila = [];
                        $(this).find('td').each(function() {
                            fila.push($(this).text());
                        });
                        contenido.push(fila);
                    });
                }
        
                $('#titulo').val('REPORTE TOMA INVENTARIO');
                $('#cabeceras').val(JSON.stringify(cabeceras));
                $('#contenido').val(JSON.stringify(contenido));
        
                document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
                document.getElementById('exportForm').submit();
            });

            $("#verEstadoTomaInv").modal("show");
        }
    }
}

function btnAnularTomaInv(id){
    Swal.fire({
        title: 'Esta seguro?',
        text: "Esta acción es irreversible",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Anular'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/anularTomaInv/"+id;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'TOMA DE INVENTARIO ANULADA',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        tblTomaInv.ajax.reload();
                    });
                }
            }
        }
      })
}