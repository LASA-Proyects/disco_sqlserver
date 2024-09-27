const btnAddCarrito = document.querySelectorAll(".btnAddCarrito");
let listaCarrito = [];

document.addEventListener('DOMContentLoaded', function(){
    cargarDetallePed();
    if(localStorage.getItem('listaCarrito') != null){
        listaCarrito = JSON.parse(localStorage.getItem('listaCarrito'));
    }
    for (let i = 0; i < btnAddCarrito.length; i++) {
        btnAddCarrito[i].addEventListener('click', function(){
            let id_producto = btnAddCarrito[i].getAttribute('prod');
            let formulario = btnAddCarrito[i].closest('form');
            let cantidad = formulario.querySelector(".quantity").value;
            agregarCarrito(id_producto, cantidad);
        });
    }
});

function agregarFuncionesBotones()
{
    const btnAddCarritoBusq = document.querySelectorAll(".btnAddCarritoBusq");
    for (let i = 0; i < btnAddCarritoBusq.length; i++) {
        btnAddCarritoBusq[i].addEventListener('click', function(){
            let id_producto = btnAddCarritoBusq[i].getAttribute('prodBusq');
            let formulario = btnAddCarritoBusq[i].closest('form');
            let cantidad = formulario.querySelector(".quantityProd").value;
            agregarCarrito(id_producto, cantidad);
        });
    }
}

function agregarCarrito(id_producto, cantidad){
    let productoExistente = listaCarrito.find(producto => producto.id_producto === id_producto);

    if(productoExistente) {
        productoExistente.cantidad = parseInt(productoExistente.cantidad) + parseInt(cantidad);
    } else {
        listaCarrito.push({
            "id_producto": id_producto,
            "cantidad": cantidad
        });
    }

    localStorage.setItem('listaCarrito', JSON.stringify(listaCarrito));
    cargarDetallePed();
}

function cargarDetallePed() {
    const url = base_url + "Pedidos/listarPed";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(JSON.stringify(listaCarrito));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let html = '';
            let cant_fil = 0;
            res.productos.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['nombre']}</td>
                <td>${row['cantidad']}</td>
                <td>${row['sub_total']}</td>
                <td><button class="btn btn-danger btnEliminarCarrito" type="button" prod="${row['id']}"><i class="fas fa-trash-alt"></button></td>
                </tr>`;
                cant_fil +=1;
            });
            if(cant_fil == 0){
                document.getElementById("btnProcesarPedido").setAttribute('disabled','disabled');
            }else{
                document.getElementById("btnProcesarPedido").removeAttribute('disabled');
            }
            document.getElementById("tblListaCarrito").innerHTML = html;
            document.getElementById("totalGeneral").textContent = (res.total) ? 'S/ ' + res.total : 'S/ 0.00';
            document.getElementById("total").value = res.total;
            btnEliminarCarrito();
        }
    }
}

function btnEliminarCarrito()
{
    let listaEliminar = document.querySelectorAll('.btnEliminarCarrito');
    for (let i = 0; i < listaEliminar.length; i++){
        listaEliminar[i].addEventListener('click', function(){
            let id_producto = listaEliminar[i].getAttribute('prod');
           eliminarListaCarrito(id_producto);
        })
    }
}

function eliminarListaCarrito(id_producto)
{
    for (let i = 0; i < listaCarrito.length; i++) {
        if (listaCarrito[i]['id_producto'] == id_producto) {
            listaCarrito.splice(i, 1);
        }
    }
    localStorage.setItem('listaCarrito', JSON.stringify(listaCarrito));
    cargarDetallePed();
}