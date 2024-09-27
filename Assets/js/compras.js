let tblBuscProduct;
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("codigo").focus();
    $('#proveedor').select2({
        theme: 'bootstrap4'
    });
});

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

function limpiarForm(){
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
                const res = JSON.parse(this.responseText);
                if(res){
                    document.getElementById("codigo").setAttribute('disabled','disabled');
                    document.getElementById("nombre").value = res.descripcion;
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
            const res = JSON.parse(this.responseText);
            if(res){
                $("#busqueda_producto").modal("hide");
                document.getElementById("codigo").setAttribute('disabled','disabled');
                document.getElementById("codigo").value = res.codigo;
                document.getElementById("nombre").value = res.descripcion;
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

function procesarCompra(e) {
    e.preventDefault();
    Swal.fire({
        title: 'GENERAR INGRESO',
        text: "¿ESTÁ SEGURO QUE DESEA GENERAR EL INGRESO?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/registrarCompra/";
            const frm = document.getElementById("frmInfoPedido");
            const formData = new FormData(frm);
            const datosCompra = {
                datos_compra: Object.fromEntries(formData),
                listaCompras: listaCompras
            };
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            http.send(JSON.stringify(datosCompra));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if(res['msg'] === 'ok'){
                        const pdfUrl = base_url + "Compras/generarPdf/" + res['id_compra'];
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

function btnAnularCompra(id) {
    Swal.fire({
        title: 'ANULAR COMPRA?',
        text: "Esta seguro de anular la compra?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Compras/anularCompra/"+ id;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    buscarComprasFecha();
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

function buscarProducto(e){
    $("#busqueda_producto").modal("show");
}