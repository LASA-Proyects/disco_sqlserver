const btnAddCompras = document.querySelector(".btnAddCompras");
let listaCompras = [];

document.addEventListener('DOMContentLoaded', function(){
    cargarDetalleCompra();
    if(localStorage.getItem('listaCompras') != null){
        listaCompras = JSON.parse(localStorage.getItem('listaCompras'));
    }

    btnAddCompras.addEventListener('click', function(){
        let cod_producto = document.getElementById('codigo').value;
        let cantidad = document.getElementById('cantidad').value;
        let precio = document.getElementById('precio').value;
        if(precio == ''){
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'Todos los campos son obligatorios',
                showConfirmButton: false,
                timer: 1500
            });
        }else{
            agregarCarrito(cod_producto, cantidad, precio);
        }
    });
});

function agregarCarrito(cod_producto, cantidad, precio){
    let productoExistente = listaCompras.find(producto => producto.cod_producto === cod_producto);
    if(productoExistente) {
        productoExistente.cantidad = parseInt(productoExistente.cantidad) + parseInt(cantidad);
    } else {
        listaCompras.push({
            "cod_producto": cod_producto,
            "cantidad": cantidad,
            "precio" : precio
        });
    }
    localStorage.setItem('listaCompras', JSON.stringify(listaCompras));
    cargarDetalleCompra();
}

function cargarDetalleCompra() {
    const url = base_url + "Compras/listarCompras";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(JSON.stringify(listaCompras));
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
                <td><button class="btn btn-danger btnEliminarCompra" type="button" prod_comp="${row['codigo']}"><i class="fas fa-trash-alt"></button></td>
                </tr>`;
                cant_fil +=1;
            });
            if(cant_fil == 0){
                document.getElementById("registrar_compra").setAttribute('disabled','disabled');
            }else{
                document.getElementById("registrar_compra").removeAttribute('disabled');
            }
            document.getElementById("tblDetalle").innerHTML = html;
            document.getElementById("titulo_productos").textContent = (res.total) ? 'S/ ' + res.total : 'S/ 0.00';
            let totalSinComa = parseFloat(res.total.replace(/,/g, ''));
            document.getElementById('total_compra').value = totalSinComa;
            limpiarForm();
            btnEliminarCompra();
        }
    }
}

function btnEliminarCompra()
{
    let listaCompras = document.querySelectorAll('.btnEliminarCompra');
    for (let i = 0; i < listaCompras.length; i++){
        listaCompras[i].addEventListener('click', function(){
            let cod_prod = listaCompras[i].getAttribute('prod_comp');
            eliminarListaCompras(cod_prod);
        })
    }
}

function eliminarListaCompras(cod_prod)
{
    for (let i = 0; i < listaCompras.length; i++) {
        if (listaCompras[i]['cod_producto'] == cod_prod) {
            listaCompras.splice(i, 1);
        }
    }
    localStorage.setItem('listaCompras', JSON.stringify(listaCompras));
    cargarDetalleCompra();
}