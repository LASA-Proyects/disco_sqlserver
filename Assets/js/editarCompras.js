document.addEventListener("DOMContentLoaded", function () {
    const url = window.location.href;
    const match = url.match(/\/(\d+)$/);
    const id = match ? match[1] : null;

    if (id) {
        const consultaUrl = base_url + "Compras/consultarCompra/" + id;
        const http = new XMLHttpRequest();
        http.open("GET", consultaUrl, true);
        http.send();
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                document.getElementById('t_operacion').value = res.compra['id_tipo_operacion'];
                document.getElementById('fecha_ingreso').value = res.compra['fecha_ingreso'];
                document.getElementById('id_almacen_ini').value = res.compra['id_almacen_ini'];
                document.getElementById('t_documento').value = res.compra['id_tipo_doc'];
                document.getElementById('tipo_cambio').value = res.compra['tipo_cambio'];
                document.getElementById('serie_doc').value = res.compra['serie'];
                document.getElementById('correl_doc').value = res.compra['correlativo'];
                var proveedorId = res.compra['id_proveedor'];
                $('#proveedor option[value="' + proveedorId + '"]').prependTo('#proveedor');
                $('#proveedor').val(proveedorId);
                document.getElementById('id_compra').value = id;
                res.detalle_compra.forEach(function (detalleCompra) {
                    var cod_producto = detalleCompra.codigo;
                    var cantidad = detalleCompra.cantidad;
                    var precio = detalleCompra.precio;
                    let productoExistente = listaCompras.find(producto => producto.cod_producto === cod_producto);

                    if (!productoExistente) {
                        listaCompras.push({
                            "cod_producto": cod_producto,
                            "cantidad": cantidad,
                            "precio": precio
                        });
                    }
                });

                localStorage.setItem('listaCompras', JSON.stringify(listaCompras));
                cargarDetalleCompra();
            }
        }
    } else {
        console.error("No se encontró un número al final de la URL");
    }
});