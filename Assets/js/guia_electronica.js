let listaProductsGuia = [];
document.addEventListener("DOMContentLoaded",function(){
    if(localStorage.getItem('listaProductsGuia') != null){
        listaProductsGuia = JSON.parse(localStorage.getItem('listaProductsGuia'));
        cargarProductosGuia();
    }
    $('.depart_partidaw').select2({
        theme: 'bootstrap4'
    });

    $('.destinatarios').select2({
        theme: 'bootstrap4'
    });
    var tModalidad = document.getElementById("modalidad");
    var tMotivo = document.getElementById("motivo");
    var checkbox = document.getElementById("vehiculo_menor");

    tModalidad.addEventListener("change", function() {
        var valorSeleccionadoModalidad = tModalidad.value;
    
        if (valorSeleccionadoModalidad == 2) {
            document.getElementById("transporte_razon_social_inputs").classList.add("d-none");
            document.getElementById("vehiculo_inputs").classList.remove("d-none");
            document.getElementById("chofer_inputs").classList.remove("d-none");
            document.getElementById("vehiculo_menor_check").classList.remove("d-none");
            document.getElementById("nombre_modalidad").classList.remove("d-none");
            document.getElementById("carro_apellidos").classList.remove("d-none");
            checkbox.disabled = false;
            document.getElementById("chofer_licencia").readOnly = checkbox.checked;
            document.getElementById("chofer_dni").readOnly = checkbox.checked;
            document.getElementById("carro_placa").readOnly = checkbox.checked;
            document.getElementById("nombre_modalidad").readOnly = checkbox.checked;
            document.getElementById("carro_apellidos").readOnly = checkbox.checked;
            checkbox.addEventListener("change", function() {
                var readOnlyState = checkbox.checked;
                document.getElementById("chofer_licencia").readOnly = checkbox.checked;
                document.getElementById("chofer_dni").readOnly = checkbox.checked;
                document.getElementById("carro_placa").readOnly = checkbox.checked;
                document.getElementById("nombre_modalidad").readOnly = readOnlyState;
                document.getElementById("carro_apellidos").readOnly = readOnlyState;
            });
        } else if (valorSeleccionadoModalidad == 1) {
            document.getElementById("transporte_razon_social_inputs").classList.remove("d-none");
            document.getElementById("vehiculo_inputs").classList.add("d-none");
            document.getElementById("chofer_inputs").classList.add("d-none");
            document.getElementById("vehiculo_menor_check").classList.add("d-none");
            document.getElementById("nombre_modalidad").classList.add("d-none");
            document.getElementById("carro_apellidos").classList.add("d-none");
            checkbox.disabled = false;
            document.getElementById("chofer_licencia").readOnly = false;
            document.getElementById("chofer_dni").readOnly = false;
            document.getElementById("carro_placa").readOnly = false;
            document.getElementById("nombre_modalidad").readOnly = false;
            document.getElementById("carro_apellidos").readOnly = false;
        }
    });

    tMotivo.addEventListener("change", function() {
        var valorSeleccionadoMotivo = tMotivo.value;
        if(valorSeleccionadoMotivo == 7){
            console.log(valorSeleccionadoMotivo);
            document.getElementById("numero_bultos_input").classList.remove("d-none");
        }else if(valorSeleccionadoMotivo != 7){
            document.getElementById("numero_bultos_input").classList.add("d-none");
        }
    });
})

function agregarProductsGuia(idProducto) {
    const cantidadInput = document.getElementById(`cantidad_${idProducto}`);
    const cantidad = cantidadInput.value.trim();
    if (cantidad === '') {
        alertas("Por favor, digite la cantidad para agregar el producto a la Guía.", "warning");
        return;
    }
    
    let cantidadNum = parseInt(cantidad);
    if (isNaN(cantidadNum) || cantidadNum <= 0) {
        alertas("Por favor, ingrese una cantidad válida mayor que cero.", "warning");
        return;
    }
    
    let productoExistente = listaProductsGuia.find(producto => producto.cod_producto === idProducto);
    
    if (productoExistente) {
        productoExistente.cantidad = parseInt(productoExistente.cantidad) + cantidadNum;
        actualizarListaYLocalStorage();
    } else {
        const url = base_url + "Facturacion/buscarProductosCod/" + idProducto;
        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        http.onreadystatechange = function () {
            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                const res = JSON.parse(this.responseText);
                const descripcion = res.descripcion;
                
                listaProductsGuia.push({
                    "cod_producto": idProducto,
                    "cantidad": cantidadNum,
                    "product_name": descripcion
                });

                actualizarListaYLocalStorage();
            }
        };
        http.send();
    }

    function actualizarListaYLocalStorage() {
        localStorage.setItem('listaProductsGuia', JSON.stringify(listaProductsGuia));
        cargarProductosGuia();
        cantidadInput.value = '';
    }
}

function cargarProductosGuia() {
    const url = base_url + "Facturacion/listarProductsGuia";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(JSON.stringify(listaProductsGuia));
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
                <td>${row['precio']}</td>
                <td>${row['cantidad']}</td>
                <td>${row['sub_total']}</td>
                <td><button class="btn btn-danger" onclick="btnEliminarProductsGuia(${row['id']})" type="button"><i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
                cant_fil +=1;
            });
            if(cant_fil == 0){
                document.getElementById("registrar_guia_electronica").setAttribute('disabled','disabled');
            }else{
                document.getElementById("registrar_guia_electronica").removeAttribute('disabled');
            }
            document.getElementById("tblProductosGuiaDetalle").innerHTML = html;
        }
    }
}

function btnEliminarProductsGuia(cod_prod)
{
    for (let i = 0; i < listaProductsGuia.length; i++) {
        if (listaProductsGuia[i]['cod_producto'] == cod_prod) {
            listaProductsGuia.splice(i, 1);
        }
    }
    localStorage.setItem('listaProductsGuia', JSON.stringify(listaProductsGuia));
    cargarProductosGuia();
}

function buscarProvinciaPartida(cod_provincia) {
    const url = base_url + "Facturacion/buscarProvincia/" + cod_provincia;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            var selectProvincia = document.getElementById("provincia_partida");
            selectProvincia.innerHTML = '';
            var optionVacia = document.createElement('option');
            optionVacia.value = "";
            optionVacia.textContent = "";
            optionVacia.disabled = true;
            optionVacia.selected = true;
            selectProvincia.appendChild(optionVacia);
            for (var j = 0; j < res.length; j++) {
                var option = document.createElement('option');
                option.value = res[j].cod;
                option.textContent = res[j].provincia;
                selectProvincia.appendChild(option);
                if (res[j].cod == cod_provincia) {
                    selectProvincia.selectedIndex = j;
                }
            }
        }
    }
}

function buscarDistritoPartida(cod_distrito) {
    const url = base_url + "Facturacion/buscarDistrito/" + cod_distrito;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            var selectDistrito = document.getElementById("distrito_partida");
            selectDistrito.innerHTML = '';
            var optionVacia = document.createElement('option');
            optionVacia.value = "";
            optionVacia.textContent = "";
            optionVacia.disabled = true;
            optionVacia.selected = true;
            selectDistrito.appendChild(optionVacia);
            for (var j = 0; j < res.length; j++) {
                var option = document.createElement('option');
                option.value = res[j].cod;
                option.textContent = res[j].distrito;
                selectDistrito.appendChild(option);
                if (res[j].cod == cod_distrito) {
                    selectDistrito.selectedIndex = j;
                }
            }
        }
    }
}

function buscarProvinciaLlegada(cod_provincia) {
    const url = base_url + "Facturacion/buscarProvincia/" + cod_provincia;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            var selectProvincia = document.getElementById("provincia_llegada");
            selectProvincia.innerHTML = '';
            var optionVacia = document.createElement('option');
            optionVacia.value = "";
            optionVacia.textContent = "";
            optionVacia.disabled = true;
            optionVacia.selected = true;
            selectProvincia.appendChild(optionVacia);
            for (var j = 0; j < res.length; j++) {
                var option = document.createElement('option');
                option.value = res[j].cod;
                option.textContent = res[j].provincia;
                selectProvincia.appendChild(option);
                if (res[j].cod == cod_provincia) {
                    selectProvincia.selectedIndex = j;
                }
            }
        }
    }
}

function buscarDistritoLlegada(cod_distrito) {
    const url = base_url + "Facturacion/buscarDistrito/" + cod_distrito;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            var selectDistrito = document.getElementById("distrito_llegada");
            selectDistrito.innerHTML = '';
            var optionVacia = document.createElement('option');
            optionVacia.value = "";
            optionVacia.textContent = "";
            optionVacia.disabled = true;
            optionVacia.selected = true;
            selectDistrito.appendChild(optionVacia);
            for (var j = 0; j < res.length; j++) {
                var option = document.createElement('option');
                option.value = res[j].cod;
                option.textContent = res[j].distrito;
                selectDistrito.appendChild(option);
                if (res[j].cod == cod_distrito) {
                    selectDistrito.selectedIndex = j;
                }
            }
        }
    }
}

function modalProductosGuia(){
    const url = base_url + "Facturacion/buscarProductos/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            res.forEach(row => {
                html += `<tr>
                    <td style="text-align: center;">${row.id}</td>
                    <td>${row.descripcion}</td>
                    <td style="text-align: center;">${row.codigo}</td>
                    <td style="text-align: center;">${row.unidad_med}</td>
                    <td>${row.precio_compra}</td>
                    <td>${row.precio_venta}</td>
                    <td><input type="number" name="cantidad_prod[${row.id}]" id="cantidad_${row.id}" class="form-control"></td>
                    <td style="text-align: center;"><button class="btn btn-primary btn-sm" type="button" onclick="agregarProductsGuia(${row.id})"><i class="fas fa-plus"></i></button></td>
                </tr>`;
            });
            if ($.fn.DataTable.isDataTable('#ProductosTBL')) {
                $('#ProductosTBL').DataTable().destroy();
            }
            document.querySelector('#ProductosTBLJS').innerHTML = html;
            $('#ProductosTBL').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
            });
            $('#verProductos').modal("show");
        }
    }
}

function generarGuiaElectronica(){
    const url = base_url + "Facturacion/registrarGuia/";
    const frm = document.getElementById("frmGuiaElectronica");
    const formData = new FormData(frm);
    const productosGuia = {
        datos_guia: Object.fromEntries(formData),
        listaProductsGuia: listaProductsGuia
    };
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    http.send(JSON.stringify(productosGuia));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
        } else {
            console.error("Error en la solicitud. Estado:", this.status);
        }
    }
}
