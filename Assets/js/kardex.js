let tblBuscProduct;
document.addEventListener("DOMContentLoaded", function(){
    tblBuscProduct = $('#tblBuscProduct').DataTable({
        ajax: {
            url: base_url + "Kardex/listar",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'descripcion'
            },
            {
                'data' : "acciones"
            }
        ],
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
});

document.addEventListener("DOMContentLoaded", function () {
    tblStock = $("#tblKardexT").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
  });

function buscarProducto(tipo_documento){
    document.getElementById("tipo_busqueda").value = tipo_documento;
    $("#busqueda_producto").modal("show");
}

function btnCodProd(id)
{
    const url = base_url + "Kardex/buscarProdId/" + id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            const tp_doc = document.getElementById("tipo_busqueda").value;
            if(tp_doc == 1){
                document.getElementById('cod_prod').value = res.codigo;
                $("#busqueda_producto").modal("hide");
            }else if(tp_doc == 2){
                document.getElementById('codigo_inicial').value = res.codigo;
                $("#busqueda_producto").modal("hide");
            }else if(tp_doc == 3){
                document.getElementById('codigo_final').value = res.codigo;
                $("#busqueda_producto").modal("hide");
            }
        }
    }
}
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblKardexT').DataTable();
        var cabeceras = [];
        $('#tblKardexT thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblKardexT tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('KARDEX PRODUCTOS');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});


function buscarKardex()
{
    const url = base_url + "Kardex/buscarKardexProd/";
    const frm = document.getElementById("frmBuscarKardexProd");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            let stock = 0;
            res.forEach(row => {
                let saldo;
                if (stock == 0) {
                    saldo = parseFloat(row.ingresos) - parseFloat(row.salidas);
                } else {
                    saldo = parseFloat(stock) + (parseFloat(row.ingresos) - parseFloat(row.salidas));
                }
                html += `<tr>
                    <td>${row.fecha_ingreso}</td>
                    <td>${row.IdOperacion}</td>
                    <td>${row.operacion}</td>
                    <td>${row.Origen === 'Compra' ? 'ING' : row.Origen === 'Venta' ? 'SAL' : row.Origen === 'Pedidos' ? 'PED' : '-'}</td>
                    <td>${row.serie}</td>
                    <td>${row.correlativo}</td>
                    <td>${row.almacen}</td>
                    <td>${row.ingresos}</td>
                    <td>${row.salidas}</td>
                    <td>${saldo}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="btnVerDocStock('${row.id_producto}','${row.id_detalle}','${row.IdOperacion}')">
                            <i class="fas fa-file-alt"></i>
                        </button>
                    </td>
                </tr>`;
                stock = saldo;
            });
            if ($.fn.DataTable.isDataTable('#tblKardexT')) {
                $('#tblKardexT').DataTable().destroy();
            }
            document.querySelector('#tblKardex').innerHTML = html;
            $('#tblKardexT').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });
        }
    }
}

function modalExportarxCodigo() {
    document.getElementById('codigo_inicial').value = "";
    document.getElementById('codigo_final').value = "";
    document.getElementById('fecha_inicial').value = "";
    document.getElementById('fecha_final').value = "";
    var zIndexModalActivo = Math.max.apply(null, $('.modal').map(function () {
        return parseInt($(this).css('z-index')) || 1;
    }));
    $('#modalExportarxCodigo').css('z-index', zIndexModalActivo - 1);
    $('#modalExportarxCodigo').modal('show');
}

function modalExportarGeneral()
{
    const url = base_url + "Kardex/getPrimeraFecha/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            document.getElementById("fecha_inicial_gen").value = res.desde.fecha_ingreso;
            document.getElementById("fecha_final_gen").value = res.hasta.fecha_ingreso;
            $('#modalExportarGeneral').modal('show');
        }
    }
}

document.getElementById('exportarExcel').addEventListener('click', function () {
    document.getElementById('frmExportarExcel').action = base_url + "Kardex/ExportarRangoCodigo";
    document.getElementById('frmExportarExcel').submit();
});

document.getElementById('exportGeneral').addEventListener('click', function () {
    document.getElementById('frmExportarExcelGen').action = base_url + "Kardex/ExportarKardexGen";
    document.getElementById('frmExportarExcelGen').submit();
});


function btnVerDocStock(id_producto, id_detalle, IdOperacion) {
    const url = base_url + "Kardex/bucarInvdetal/"+id_detalle;
    const params = "id_producto=" + id_producto;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(res['Origen'] === "Compra"){
                const pdfUrl = base_url + "Compras/generarPdf/" + IdOperacion;
                window.open(pdfUrl, '_blank');
            }else if(res['Origen'] === "Venta"){
                const pdfUrl = base_url + "Compras/generarPdfVenta/" + IdOperacion;
                window.open(pdfUrl, '_blank');
            }else if(res['Origen'] === "Pedidos"){
                const pdfUrl = base_url + "Pedidos/generarPdfPedido/" + IdOperacion;
                window.open(pdfUrl, '_blank');
            }
        }
    }
    http.send(params);
}