let tblBuscProduct;
document.addEventListener("DOMContentLoaded", function(){
    var tOperacion = document.getElementById("t_operacion");
    var idAlmacenFin = document.getElementById("id_almacen_fin");
    tOperacion.addEventListener("change", function() {
        var valorSeleccionado = tOperacion.value;
        if (valorSeleccionado == "1" || valorSeleccionado == "2") {
            idAlmacenFin.style.display = "block";
            document.getElementById("nombre_almacen_fin").style.display = "block"
        } else {
            idAlmacenFin.style.display = "none";
            document.getElementById("nombre_almacen_fin").style.display = "none"
        }
    });
})

document.addEventListener("DOMContentLoaded", function(){
    tblBuscProduct = $('#tblBuscProduct').DataTable({
        ajax: {
            url: base_url + "Compras/listarProductos",
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

function limpiarFormVenta(){
    document.getElementById("nombre").value = '';
    document.getElementById("precio").value = '';
    document.getElementById("cantidad").value = '';
    document.getElementById("codigo").value = '';
    document.getElementById("unidad").value = '';
    document.getElementById("codigo").removeAttribute('disabled');
    document.getElementById("codigo").focus();
    document.getElementById("cantidad").setAttribute('disabled','disabled');
}

function buscarCodigo(e) {
    e.preventDefault();
    const cod = document.getElementById("codigo").value;
    if(cod != ''){
        const cod = document.getElementById("codigo").value;
        const url = base_url + "Compras/buscarCodigo/"+cod;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    if(res){
                        document.getElementById("codigo").setAttribute('disabled','disabled');
                        document.getElementById("nombre").value = res.descripcion;
                        document.getElementById("precio").value = res.precio_venta;
                        document.getElementById("id").value = res.id;
                        document.getElementById("cantidad").removeAttribute('disabled');
                        document.getElementById("cantidad").focus();
                    }else{
                        alertas('El producto no existe', 'warning');
                        document.getElementById("codigo").value = '';
                        document.getElementById("codigo").focus();
                    }
                }
            }
        }
    }else{
        alertas('Ingrese el código', 'warning');
    }
}

function buscarProductoLista(id) {
    const url = base_url + "Compras/buscarProdId/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res){
                    $("#busqueda_producto").modal("hide");
                    document.getElementById("codigo").setAttribute('disabled','disabled');
                    document.getElementById("codigo").value = res.codigo;
                    document.getElementById("nombre").value = res.descripcion;
                    document.getElementById("precio").value = res.precio_venta;
                    document.getElementById("unidad").value = res.unidad_med;
                    document.getElementById("id").value = res.id;
                    document.getElementById("cantidad").removeAttribute('disabled');
                    document.getElementById("cantidad").focus();
                }else{
                    alertas('El producto no existe', 'warning');
                    document.getElementById("codigo").value = '';
                    document.getElementById("codigo").focus();
                }
            }
        }
    }
}

function calcularPrecioVenta(e) {
    e.preventDefault();
    const cant = document.getElementById("cantidad").value;
    const precio = document.getElementById("precio").value;
    if(cant > 0){
        const url = base_url + "Compras/ingresarVenta/";
        const frm = document.getElementById("frmVenta")
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.send(new FormData(frm));
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res != 'negativo'){
                    frm.reset();
                    cargarDetalleVenta();
                    document.getElementById("codigo").removeAttribute('disabled');
                    document.getElementById("cantidad").setAttribute('disabled', 'disabled');
                    document.getElementById("codigo").focus();
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'warning',
                        title: 'No hay más productos en Stock',
                        showConfirmButton: false,
                        timer: 1000
                        })
                }
            }
        }
    }else{
        Swal.fire({
            position: 'top-end',
            icon: 'warning',
            title: 'Ingresar una cantidad',
            showConfirmButton: false,
            timer: 1000
            })
    }
}

function procesarVenta(e) {
    e.preventDefault();
    Swal.fire({
        title: 'GENERAR SALIDA',
        text: "¿ESTÁ SEGURO QUE DESEA GENERAR LA SALIDA?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/registrarVenta/";
            const frm = document.getElementById("frmInfoPedido");
            const formData = new FormData(frm);
            const datosVenta = {
                datos_venta: Object.fromEntries(formData),
                listaVentas: listaVentas
            };
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            http.send(JSON.stringify(datosVenta));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if(res['msg'] === 'ok'){
                        const pdfUrl = base_url + "Compras/generarPdfVenta/" + res['id_venta'];
                        const ventana = window.open(pdfUrl, '_blank', 'width=300,height=800,toolbar=0,scrollbars=1,resizable=1');
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                        if (ventana) {
                            ventana.focus();
                        } else {
                            alert('Su navegador está bloqueando ventanas emergentes. Por favor, habilite las ventanas emergentes para ver el PDF generado.');
                        }
                    }else{
                        alertas(res['msg'], res['icon']);
                    }
                } else {
                    console.error("Error en la solicitud. Estado:", this.status);
                }
            }
        }
    })
}

function btnAnularVenta(id) {
    Swal.fire({
        title: 'ANULAR VENTA?',
        text: "Esta seguro de anular la venta?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/anularVenta/"+ id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    buscarVentasFecha();
                }
            }
        }
      })
}

function consultaTipoCambio(e) {
    e.preventDefault();
    const url = base_url + "Compras/consulta_tipo_cambio/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("tipo_cambio").value = res.precioVenta;
        }
    }
}

function deleteDetalle(id, accion){
    let url;
    if(accion == 1){
        url = base_url + "Compras/delete/"+id;
    }else{
        url = base_url + "Compras/deleteVenta/"+id;
    }
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if(accion == 1){
                cargarDetalle();
            }else{
                cargarDetalleVenta();
            }
        }
    }
}

function buscarProducto(e){
    $("#busqueda_producto").modal("show");
}