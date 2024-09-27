document.addEventListener("DOMContentLoaded", function(){
    window.tblHistorialCompras = $('#tblHistorialCompras').DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false
    });
})

function buscarComprasFecha() {
    const url = base_url + "Compras/buscarComprasFecha/";
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
                    <td>${row.proveedor}</td>
                    <td>${row.operacion}</td>
                    <td>${row.total}</td>
                    <td>${row.fecha}</td>
                    <td>${row.fecha_ingreso}</td>
                    <td>${row.estado}</td>
                    <td>${row.acciones}</td>
                </tr>`;
            });
            if ($.fn.DataTable.isDataTable('#tblHistorialCompras')) {
                $('#tblHistorialCompras').DataTable().destroy();
            }
            document.querySelector('#tblHistorialComprasJs').innerHTML = html;
            const tblHistorialCompras = $('#tblHistorialCompras').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });

            $("#iptProveedor").keyup(function(){
                tblHistorialCompras.column($(this).data('index')).search(this.value).draw();
            });
            $("#iptFechaIngreso").change(function(){
                tblHistorialCompras.column($(this).data('index')).search(this.value).draw();
            });
            $("#btnLimpiarFecha").click(function() {
                $("#iptFechaIngreso").val("");
                tblHistorialCompras.column($("#iptFechaIngreso").data('index')).search('').draw();
            });
            $("#btnLimpiarProveedor").click(function() {
                $("#iptProveedor").val("");
                tblHistorialCompras.column($("#iptProveedor").data('index')).search('').draw();
            });

            document.getElementById('excelDetalladoButton').addEventListener('click', function () {
                var table = $('#tblHistorialCompras').DataTable();
                var cabeceras = [];
                $('#tblHistorialCompras thead th').each(function() {
                    cabeceras.push($(this).text());
                });
                var contenido = [];
                for (var i = 0; i < table.page.info().pages; i++) {
                    table.page(i).draw('page');
                    $('#tblHistorialCompras tbody tr').each(function() {
                        var fila = [];
                        $(this).find('td').each(function() {
                            fila.push($(this).text());
                        });
                        contenido.push(fila);
                    });
                }
        
                $('#titulo').val('HISTORIAL COMPRAS');
                $('#cabeceras').val(JSON.stringify(cabeceras));
                $('#contenido').val(JSON.stringify(contenido));
        
                document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
                document.getElementById('exportForm').submit();
            });
        }
    };
}