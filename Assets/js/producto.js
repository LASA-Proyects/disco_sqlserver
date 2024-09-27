let tblProductos, tblProductosStock;
document.addEventListener("DOMContentLoaded", function(){
    tblProductos = $('#tblProductos').DataTable({
                ajax: {
                    url: base_url + "Productos/listar",
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
                    'data' : 'codigo'
                },
                {
                    'data' : 'descripcion'
                },
                {
                    'data' : 'familia'
                },
                {
                    'data' : 'estado'
                },
                {
                    'data' : "acciones"
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
})

document.addEventListener("DOMContentLoaded", function () {
    tblStock = $("#tblStock").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
  });

document.addEventListener("DOMContentLoaded",function(){
    $('.buscador').select2({
        theme: 'bootstrap4'
    });
})

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblStock').DataTable();
        var cabeceras = [];
        $('#tblStock thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblStock tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('STOCK PRODUCTOS');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblProductos').DataTable();
        var cabeceras = [];
        $('#tblProductos thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblProductos tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('PRODUCTOS');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});


function buscarStockPorAlmacen() {
    let id = document.getElementById("almacen").value;
    const url = base_url + "Productos/listarStock/" + id;
    const frm = document.getElementById("frmbuscarStockPorAlmacen");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            res.forEach(row => {
                html += `<tr>
                    <td>${row.id_producto}</td>
                    <td>${row.codigo}</td>
                    <td>${row.foto}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.nombre_familia}</td>
                    <td>${row.almacen}</td>
                    <td>${row.stock}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="verKardex(${row.id_producto}, event, ${row.id_almacen})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>`;
            });
            if ($.fn.DataTable.isDataTable('#tblStock')) {
                $('#tblStock').DataTable().destroy();
            }
            document.querySelector('#tblProductosStock').innerHTML = html;
            $('#tblStock').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });
        }
    }
}

function verKardex(id_producto, event, id_almacen) {
    event.preventDefault();
    const url = base_url + "Kardex/buscarKardexId/" + id_producto + "/" + id_almacen;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            let stock = 0;
            let totalIngresos = 0;
            let totalSalidas = 0;
            let totalSaldo = 0;
            res.forEach(row => {
                let saldo;
                if (stock == 0) {
                    saldo = parseFloat(row.ingresos) - parseFloat(row.salidas);
                } else {
                    saldo = parseFloat(stock) + (parseFloat(row.ingresos) - parseFloat(row.salidas));
                }
                html += `<tr>
                    <td>${row.fecha_ingreso}</td>
                    <td>${row.operacion}</td>
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
                totalIngresos += parseFloat(row.ingresos);
                totalSalidas += parseFloat(row.salidas);
                stock = saldo;
                totalSaldo += parseFloat(saldo);
            });
            if ($.fn.DataTable.isDataTable('#tblKardexProductos')) {
                $('#tblKardexProductos').DataTable().destroy();
            }
            document.getElementById('ingresos').value = totalIngresos;
            document.getElementById('salidas').value = totalSalidas;
            document.getElementById('saldo').value = totalSaldo;
            document.querySelector('#tblKardexProductosJS').innerHTML = html;
            $('#tblKardexProductos').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });
            $('#verKardexProducto').modal("show");
        }
    }
}

function buscarFamilia(event){
    var selectedProductId = event.target.value;
    if(selectedProductId != -1){
        const url = base_url + "Productos/buscarFamilia/"+selectedProductId;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                var selectFamilia = document.getElementById("familia");
                for (var i = 0; i < selectFamilia.options.length; i++) {
                    if (selectFamilia.options[i].value == res.id_familia) {
                        selectFamilia.selectedIndex = i;
                        $('#familia option[value="' + res.id_familia + '"]').prependTo('#familia');
                        break;
                    }
                }
            }
        }
    }
}
function frmProducto(e){
    document.getElementById("title").innerHTML = "Nuevo Producto";
    document.getElementById("btnAccion").innerHTML = "Registrar";
    document.getElementById("frmProducto").reset();
    $("#nuevo_producto").modal("show");
    document.getElementById("id").value = "";
    deleteImg();    
}
function registrarProduct(e){
    e.preventDefault();
    const codigo = document.getElementById("codigo");
    const descripcion = document.getElementById("descripcion");
    const precio_compra = document.getElementById("precio_compra");
    const precio_venta = document.getElementById("precio_venta");
    if(codigo.value == "" || descripcion.value == "" || precio_compra.value == "" || precio_venta.value == ""){
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Todos los campos son obligatorios',
            showConfirmButton: false,
            timer: 1500
          })
    }else{
        const url = base_url + "Productos/registrar";
        const frm = document.getElementById("frmProducto");
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                $("#nuevo_producto").modal("hide");
                alertas(res.msg, res.icono);
                tblProductos.ajax.reload();
            }
        }
    }
}
function btnEditarProduct(id){
    document.getElementById("title").innerHTML = "";
    document.getElementById("btnAccion").innerHTML = "";
    document.getElementById("id").value = "";
    document.getElementById("codigo").value = "";
    document.getElementById("t_articulo").value = "";
    document.getElementById("afecta_compra").checked = false;
    document.getElementById("afecta_venta").checked = false;
    document.getElementById("afecta_igv").checked = false;
    document.getElementById("afecta_iss").checked = false;
    document.getElementById("descripcion").value = "";
    document.getElementById("linea").value = "";
    document.getElementById("ubicacion").value = "";
    document.getElementById("origen").value = "";
    document.getElementById("precio_compra").value = "";
    document.getElementById("precio_venta").value = "";
    document.getElementById("familia").value = "";
    document.getElementById("unidad_medida").value = "";
    document.getElementById("cantidad").value = "";
    document.getElementById("stock_minimo").value = "";
    document.getElementById("img-preview").src = "";
    document.getElementById("icon-cerrar").innerHTML = "";
    document.getElementById("icon-image").classList.remove("d-none");
    document.getElementById("foto_actual").value = "";
    document.getElementById("title").innerHTML = "Editar Producto";
    document.getElementById("btnAccion").innerHTML = "Modificar";
    const url = base_url + "Productos/editar/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("id").value = res.id;
            document.getElementById("codigo").value = res.codigo;
            document.getElementById("t_articulo").value = res.id_tarticulo;
            document.getElementById("afecta_compra").value = res.afecta_compra;
            document.getElementById("afecta_venta").value = res.afecta_venta;
            document.getElementById("afecta_igv").value = res.afecta_igv;
            document.getElementById("afecta_iss").value = res.afecta_iss;
            document.getElementById("descripcion").value = res.descripcion;
            document.getElementById("linea").value = res.linea;
            document.getElementById("ubicacion").value = res.ubicacion;
            document.getElementById("origen").value = res.origen;
            document.getElementById("precio_compra").value = res.precio_compra;
            document.getElementById("precio_venta").value = res.precio_venta;
            document.getElementById("familia").value = res.id_familia;
            document.getElementById("unidad_medida").value = res.unidad_med;
            document.getElementById("cantidad").value = res.cantidad_med;
            document.getElementById("stock_minimo").value = res.stock_min;
            document.getElementById("img-preview").src = base_url + 'Assets/img/'+ res.foto;
            document.getElementById("icon-cerrar").innerHTML = `<button class="btn btn-danger" onclick="deleteImg()"><i class="fas fa-times"></i></button>`;
            document.getElementById("icon-image").classList.add("d-none");
            document.getElementById("foto_actual").value = res.foto;
            if (res.afecta_compra === 1) {
            document.getElementById("afecta_compra").checked = true;
            }
            if (res.afecta_venta === 1) {
            document.getElementById("afecta_venta").checked = true;
            }
            if (res.afecta_igv === 1) {
            document.getElementById("afecta_igv").checked = true;
            }
            if (res.afecta_iss === 1) {
                document.getElementById("afecta_iss").checked = true;
                }
            $("#nuevo_producto").modal("show");
        }
    }
}
function btnEliminarProduct(id){
    Swal.fire({
        title: 'Esta seguro',
        text: "Se eliminara el producto de forma permanente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/eliminar/"+id;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.readyState == 4 && this.status == 200){
                        const res = JSON.parse(this.responseText);
                        alertas(res.msg, res.icono);
                        tblProductos.ajax.reload();
                    }
                }
            }
        }
      })
}

function btnEstadoProducto(id) {
    Swal.fire({
        title: 'Esta seguro?',
        text: "Se cambiará el estado del Producto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Desactivar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/desactivar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProductos.ajax.reload();
                }
            }
        }
      })
}
function btnActivarProducto(id) {
    Swal.fire({
        title: 'Esta seguro?',
        text: "Se cambiará el estado del Producto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Activar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Productos/activar/"+id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    tblProductos.ajax.reload();
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

function btnVerDocStock(id_producto, id_detalle, IdOperacion) {
    const id_tipo_enlace = document.getElementById("tipo_enlace").value;
    if(id_tipo_enlace == 1){
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
    }else if (id_tipo_enlace == 2){
        event.preventDefault();
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
}

$("#filtro_stock").change(function() {
    const selectedIndex = this.selectedIndex;
    let searchValue = '';
    let table = $('#tblStock').DataTable();

    switch (selectedIndex) {
        case 1:
            searchValue = '^[1-9]\\d*|[1-9]\\d*[0-9]';
            break;
        case 2:
            searchValue = '^\\-[1-9]\\d*';
            break;
        case 3:
            searchValue = '^0.00$';
            break;
        default:
            searchValue = '';
            break;
    }

    table.column(6).search(searchValue, true, false).draw();
});