const btnAddVentas = document.querySelector(".btnAddVentas");
let listaVentas = [];

document.addEventListener('DOMContentLoaded', function(){
    cargarDetalleVenta();
    if(localStorage.getItem('listaVentas') != null){
        listaVentas = JSON.parse(localStorage.getItem('listaVentas'));
    }

    btnAddVentas.addEventListener('click', function(){
        let cod_producto = document.getElementById('codigo').value;
        let cantidad = document.getElementById('cantidad').value;
        agregarCarritoVenta(cod_producto, cantidad);
    });
});

function agregarCarritoVenta(cod_producto, cantidad){
    let productoExistente = listaVentas.find(producto => producto.cod_producto === cod_producto);
    if(productoExistente) {
        productoExistente.cantidad = parseInt(productoExistente.cantidad) + parseInt(cantidad);
    } else {
        listaVentas.push({
            "cod_producto": cod_producto,
            "cantidad": cantidad
        });
    }
    localStorage.setItem('listaVentas', JSON.stringify(listaVentas));
    cargarDetalleVenta();
}

function cargarDetalleVenta() {
    const url = base_url + "Compras/listarVentas";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(JSON.stringify(listaVentas));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let html = '';
            let cant_fil = 0;
            res.productos.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['nombre']}</td>
                <td>${row['unidad']}</td>
                <td>${row['cantidad']}</td>
                <td>${row['precio']}</td>
                <td>${row['sub_total']}</td>
                <td><button class="btn btn-danger btnEliminarVenta" type="button" prod_vent="${row['codigo']}"><i class="fas fa-trash-alt"></button></td>
                </tr>`;
                cant_fil +=1;
            });
            if(cant_fil == 0){
                document.getElementById("registrar_venta").setAttribute('disabled','disabled');
            }else{
                document.getElementById("registrar_venta").removeAttribute('disabled');
            }
            document.getElementById("tblDetalleVenta").innerHTML = html;
            document.getElementById("titulo_productos_vent").textContent = (res.total) ? 'S/ ' + res.total : 'S/ 0.00';
            let totalSinComa = parseFloat(res.total.replace(/,/g, ''));
            document.getElementById('total_venta').value = totalSinComa;
            limpiarFormVenta();
            btnEliminarVenta();
        }
    }
}

function btnEliminarVenta()
{
    let listaVentas = document.querySelectorAll('.btnEliminarVenta');
    for (let i = 0; i < listaVentas.length; i++){
        listaVentas[i].addEventListener('click', function(){
            let cod_prod = listaVentas[i].getAttribute('prod_vent');
            eliminarlistaVentas(cod_prod);
        })
    }
}

function eliminarlistaVentas(cod_prod)
{
    for (let i = 0; i < listaVentas.length; i++) {
        if (listaVentas[i]['cod_producto'] == cod_prod) {
            listaVentas.splice(i, 1);
        }
    }
    localStorage.setItem('listaVentas', JSON.stringify(listaVentas));
    cargarDetalleVenta();
}