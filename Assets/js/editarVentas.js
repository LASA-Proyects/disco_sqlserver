document.addEventListener("DOMContentLoaded", function () {
    const url = window.location.href;
    const match = url.match(/\/(\d+)$/);
    const id = match ? match[1] : null;

    if (id) {
        const consultaUrl = base_url + "Compras/consultarVenta/" + id;
        const http = new XMLHttpRequest();
        http.open("GET", consultaUrl, true);
        http.send();
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                let tipo_op = document.getElementById('t_operacion').value = res.venta['id_tipo_operacion'];
                document.getElementById('fecha_ingreso').value = res.venta['fecha_ingreso'];
                document.getElementById('id_almacen_ini').value = res.venta['id_almacen_ini'];
                document.getElementById('t_documento').value = res.venta['id_tipo_doc'];
                document.getElementById('tipo_cambio').value = res.venta['tipo_cambio'];
                document.getElementById('serie_doc_vnt').value = res.venta['serie'];
                document.getElementById('correl_doc_vnt').value = res.venta['correlativo'];
                var proveedorId = res.venta['id_proveedor'];
                $('#proveedor option[value="' + proveedorId + '"]').prependTo('#proveedor');
                $('#proveedor').val(proveedorId);
                document.getElementById('id_venta').value = id;
                if (tipo_op == "2") {
                    document.getElementById('id_almacen_fin').value = res.venta['id_almacen_fin'];
                    document.getElementById("id_almacen_fin").style.display = "block";
                } else {
                    document.getElementById("id_almacen_fin").style.display = "none";
                }

                res.detalle_ventas.forEach(function (detalleVentas) {
                    var cod_producto = detalleVentas.codigo;
                    var cantidad = detalleVentas.cantidad;
                    let productoExistente = listaVentas.find(producto => producto.cod_producto === cod_producto);

                    if (!productoExistente) {
                        listaVentas.push({
                            "cod_producto": cod_producto,
                            "cantidad": cantidad
                        });
                    }
                });
                localStorage.setItem('listaVentas', JSON.stringify(listaVentas));
                cargarDetalleVenta();
            }
        }
    } else {
        console.error("No se encontró un número al final de la URL");
    }
});