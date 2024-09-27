document.addEventListener("DOMContentLoaded", function(){
    window.tblHistorialVentas = $('#tblHistorialVentas').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false
    });
    
})

function buscarVentasFecha() {
    const url = base_url + "Compras/buscarVentasFecha/";
    const frm = document.getElementById("buscarInfo");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            res.forEach(row => {
                html += `<tr>
                    <td>${row.id}</td>
                    <td>${row.operacion}</td>
                    <td>${row.total}</td>
                    <td>${row.fecha}</td>
                    <td>${row.fecha_ingreso}</td>
                    <td>${row.estado}</td>
                    <td>${row.acciones}</td>
                </tr>`;
            });
            if ($.fn.DataTable.isDataTable('#tblHistorialVentas')) {
                $('#tblHistorialVentas').DataTable().destroy();
            }
            document.querySelector('#tblHistorialVentasJs').innerHTML = html;
            $('#tblHistorialVentas').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });

            document.getElementById('excelDetalladoButton').addEventListener('click', function () {
                var table = $('#tblHistorialVentas').DataTable();
                var cabeceras = [];
                $('#tblHistorialVentas thead th').each(function() {
                    cabeceras.push($(this).text());
                });
                var contenido = [];
                for (var i = 0; i < table.page.info().pages; i++) {
                    table.page(i).draw('page');
                    $('#tblHistorialVentas tbody tr').each(function() {
                        var fila = [];
                        $(this).find('td').each(function() {
                            fila.push($(this).text());
                        });
                        contenido.push(fila);
                    });
                }
        
                $('#titulo').val('HISTORIAL VENTAS');
                $('#cabeceras').val(JSON.stringify(cabeceras));
                $('#contenido').val(JSON.stringify(contenido));
        
                document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
                document.getElementById('exportForm').submit();
            });
        }
    };
}