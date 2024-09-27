cargarDetalleRec();
let tblBuscProduct;
document.addEventListener("DOMContentLoaded", function(){
    tblBuscProduct = $('#tblBuscProduct').DataTable({
        ajax: {
            url: base_url + "Recetas/listar",
            dataSrc: '',
            contentType: "application/json; charset=utf-8"
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'codigo'
            },
            {
                'data' : 'descripcion'
            },
            {
                'data' : 'precio_venta'
            },
            {
                'data' : 'familia'
            },
            {
                'data' : 'unidad_med'
            },
            {
                'data' : 'cantidad'
            },
            {
                'data' : "acciones"
            }
        ],
		paging: true,
        pageLength: 5,
    });
});

function cargarDetalleRec() {
    const urlParts = window.location.pathname.split('/');
    const id = urlParts[urlParts.length - 1];
    const url = base_url + "Recetas/listarProdRec/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let html = '';
            res.forEach(row => {
                html += `<tr>
                <td>${row['id_producto']}</td>
                <td>${row['descripcion']}</td>
                <td>${row['unidad_med']}</td>
                <td>${row['cantidad']}</td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalleRec(${row['id']})"><i class="fas fa-trash-alt"></button></td>
                </tr>`;
            });
            document.getElementById("tblListaReceta").innerHTML = html;
        }
    }
}


function frmReceta(){
    $("#nueva_receta").modal("show");
}

function btnAgregarReceta(id) {
    const cantidadInput = document.getElementById('cantidad_prod_' + id);
    const cantidad = cantidadInput.value;
    const urlParts = window.location.pathname.split('/');
    const id_product = urlParts[urlParts.length - 1];
    if (cantidad === "") {
        alertas("Por favor, ingrese una cantidad antes de agregar el producto.", "warning");
        return;
    }

    const formData = new FormData();
    formData.append('id_producto', id_product);
    formData.append('cantidad_prod', cantidad);
    const url = base_url + "Recetas/ingresar/" + id;
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(formData);
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            cargarDetalleRec();
        }
    }
}

function deleteDetalleRec(id){
    const url = base_url + "Recetas/DetRec/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            cargarDetalleRec();
        }
    }
}