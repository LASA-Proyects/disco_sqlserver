let tblObtenerPedidos, tblPedidosPagados, tblTokensPedidos, tblPedidosAnulados;
const busquedaProd = document.querySelector('#buscarProdPant');
const parametroBusq = document.getElementById('parametroBusq').value;
const lineaparamBusq = document.getElementById('lineaparamBusq').value;
document.addEventListener("DOMContentLoaded", function(){
    cargarDetallePed();
    document.getElementById('pdfButton').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/pdfPorFechas";
        document.getElementById('exportForm').submit();
    });
    var tblObtenerPedidos = $('#tblObtenerPedidos').DataTable({
        ajax: {
            url: base_url + "Pedidos/obtenerPedidos",
            dataSrc: ''
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 'total'
            },
            {
                'data' : 'fecha'
            },
            {
                'data' : 'estado'
            },
            {
                'data' : "acciones"
            }
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    $('#fecha_filtro').daterangepicker(
        {
            format: 'YYYY-MM-DD'
        },
        function (start, end) {
            tblObtenerPedidos.destroy();
            tblObtenerPedidos = $('#tblObtenerPedidos').DataTable({
                ajax: {
                    url: base_url + "Pedidos/obtenerPedidos",
                    data: {
                        desde: start.format('YYYY-MM-DD'),
                        hasta: end.format('YYYY-MM-DD')
                    },
                    dataSrc: ''
                },
                columns: [
                                {
                'data' : 'id'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 'total'
            },
            {
                'data' : 'fecha'
            },
            {
                'data' : 'estado'
            },
            {
                'data' : "acciones"
            }
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
        }
    );
});



document.addEventListener("DOMContentLoaded", function(){
    var tblPedidosPagados = $('#tblPedidosPagados').DataTable({
        ajax: {
            url: base_url + "Pedidos/obtenerPedidosPagados",
            dataSrc: ''
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 'total'
            },
            {
                'data' : 'fecha'
            },
            {
                'data' : 'estado'
            },
            {
                'data' : "acciones"
            }
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    $('#fecha_filtro_proc').daterangepicker(
        {
            format: 'YYYY-MM-DD'
        },
        function (start, end) {
            tblPedidosPagados.destroy();
            tblPedidosPagados = $('#tblPedidosPagados').DataTable({
                ajax: {
                    url: base_url + "Pedidos/obtenerPedidosPagados",
                    data: {
                        desde: start.format('YYYY-MM-DD'),
                        hasta: end.format('YYYY-MM-DD')
                    },
                    dataSrc: ''
                },
                columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 'total'
            },
            {
                'data' : 'fecha'
            },
            {
                'data' : 'estado'
            },
            {
                'data' : "acciones"
            }
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
        }
    );
});

document.addEventListener("DOMContentLoaded", function(){
    var tblPedidosAnulados = $('#tblPedidosAnulados').DataTable({
        ajax: {
            url: base_url + "Pedidos/obtenerPedidosAnulados",
            dataSrc: ''
        },
        columns: [
            {
                'data' : 'id'
            },
            {
                'data' : 'nombre'
            },
            {
                'data' : 'total'
            },
            {
                'data' : 'fecha'
            },
            {
                'data' : 'estado'
            }
        ],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    $('#fecha_filtro_proc').daterangepicker(
        {
            format: 'YYYY-MM-DD'
        },
        function (start, end) {
            tblPedidosAnulados.destroy();
            tblPedidosAnulados = $('#tblPedidosAnulados').DataTable({
                ajax: {
                    url: base_url + "Pedidos/obtenerPedidosAnulados",
                    data: {
                        desde: start.format('YYYY-MM-DD'),
                        hasta: end.format('YYYY-MM-DD')
                    },
                    dataSrc: ''
                },
                columns: [
                    {
                        'data' : 'id'
                    },
                    {
                        'data' : 'nombre'
                    },
                    {
                        'data' : 'total'
                    },
                    {
                        'data' : 'fecha'
                    },
                    {
                        'data' : 'estado'
                    }
                ],
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
        }
    );
});

document.addEventListener("DOMContentLoaded", function(){
    tblTokensPedidos = $('#tblTokensPedidos').DataTable({
                ajax: {
                    url: base_url + "Pedidos/listarTokensPedidos",
                    dataSrc: ''
                },
                columns: [
                {
                    'data' : 'token_pedido'
                },
                {
                    'data' : 'solicitante'
                },
                {
                    'data' : 'fecha'
                },
                {
                    'data' : 'fecha_caduca'
                },
                {
                    'data' : 'estado'
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
})

function cancelarOrden()
{
    localStorage.removeItem('listaCarrito');
    setTimeout(() => {
        window.location.reload();
    }, 100);
}

function modalBusqueda(){
    $("#busquedaProductoPantalla").modal("show");
}

document.addEventListener("DOMContentLoaded", function(){
    busquedaProd.addEventListener('keyup', function (e){
        if (e.key === 'Backspace' && e.target.value.length === 0) {
            e.preventDefault();
            return;
        }
        const valorCodificado = encodeURIComponent(e.target.value);
        const url = base_url + "Pedidos/buscarProdPantalla/"+valorCodificado+"/"+parametroBusq+"/"+lineaparamBusq;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                let html = '';
                res.forEach(producto =>{
                    let ribbon = '';
                    if (producto.stock == 0) {
                        ribbon = `<div class="position-absolute ribbon-wrapper">
                                    <div class="ribbon bg-danger">
                                        AGOTADO
                                    </div>
                                  </div>`;
                    }
                    html += `
                        <div class="row">
                            <div class="col-md-10">
                                <div class="position-relative">
                                    ${ribbon}
                                    <div class="card">
                                        <span class="stock-quantity badge badge-info">${producto.stock}</span>
                                        <button class="btn btn-primary rounded-circle btn-sm d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-top: 3px; margin-left: 2px;" onclick="btnVerStockProducto(${producto.id_producto})">
                                            <i class="fas fa-exclamation" style="font-size: 16px;"></i>
                                        </button>
                                        <div class="card-header">
                                            <img src="${base_url + "Assets/img/" + producto.foto}" class="card-img-top" alt="${producto.descripcion}" style="max-height: 300px; width: 100%; object-fit: cover;">
                                        </div>
                                        <div class="card-body d-flex flex-column" style="height: 130px;">
                                            <h5 class="card-title text-center my-auto">${producto.descripcion}</h5>
                                            <h5 class="card-text text-center my-auto">S/. ${producto.precio_venta}</h5>
                                        </div>
                                        <form id="frmPedidoIngr_${producto.id_producto}">
                                            <div class="card-footer">
                                                <input type="hidden" name="id_product" id="id_product" value="${producto.id_producto}">
                                                <input type="hidden" name="t_pedido" id="t_pedido" value="1">
                                                    <div class="d-flex justify-content-between align-items-center ${(producto.stock == 0) ? 'd-none' : ''}">
                                                        <button class="btn btn-danger btn-sm btnDisminuirCantidad"><i class="fas fa-minus"></i></button>
                                                        <input type="text" class="quantityProd" value="1" style="width: 50px; padding: 5px; text-align: center; border: 1px solid #ccc;">
                                                        <button class="btn btn-success btn-sm btnAumentarCantidad"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                <div class="d-flex justify-content-end mt-2">
                                                <a class="btn btn-info flex-fill btnAddCarritoBusq ${(producto.stock == 0) ? 'd-none' : ''}" prodBusq="${producto.id_producto}">
                                                    <i class="fa fa-cart-plus"></i>
                                                </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `
                });
                document.querySelector('#resultBusqueda').innerHTML = html;
                agregarFuncionesBotones();
            }
        }
    })
})

function buscarCodigoPedido(e) {
    e.preventDefault();
    const codigoPed = document.getElementById("codigoPed").value;
    if(e.which == 13){
        if(codigoPed != ''){
            const cod = document.getElementById("codigoPed").value;
            const url = base_url + "Pedidos/buscarCodigoPedido/"+cod;
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.readyState == 4 && this.status == 200){
                        const res = JSON.parse(this.responseText);
                        if(res){
                            document.querySelector('#totalPedido').textContent = res.total;
                        }else{
                            alertas('El Pedido no existe', 'warning');
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
}

function toggleCampoEntrada(checkboxId, inputId, campoOperacionId, op_cort_sol = null, btnToken = null) {
    var checkbox = document.getElementById(checkboxId);
    var campoEntrada = document.getElementById(inputId);
    var campoOperacion = document.getElementById(campoOperacionId);

    if (checkbox.checked) {
        campoEntrada.classList.remove("d-none");
        campoOperacion.classList.remove("d-none");
        if (op_cort_sol !== null) {
            var opCortSol = document.getElementById(op_cort_sol);
            opCortSol.classList.remove("d-none");
        }
        if (btnToken !== null) {
            var botonToken = document.getElementById(btnToken);
            botonToken.classList.remove("d-none");
        }
    } else {
        campoEntrada.classList.add("d-none");
        campoOperacion.classList.add("d-none");
        if (op_cort_sol !== null) {
            var opCortSol = document.getElementById(op_cort_sol);
            opCortSol.classList.add("d-none");
        }
        if (btnToken !== null) {
            var botonToken = document.getElementById(btnToken);
            botonToken.classList.add("d-none");
        }
    }
}
function registrarPedidoFacturado(e) {
    e.preventDefault();
    const url = base_url + "Pedidos/registrarPedidoFacturado";
    const frm = document.getElementById("frmPedidoFacturado");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            if(res == 'ok'){
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'PEDIDO PROCESADO',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        localStorage.removeItem('listaCarrito')
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    });
            }else{
                alertas(res.msg, res.icon);
            }
        }
    }
}

function btnEditarTipoPago(idPedido){
    btnVerDetallePedido(idPedido);
    document.getElementById('btnAccion').innerHTML = "Modificar";
    const url = base_url + "Pedidos/editar/"+idPedido;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let igv = res.total*0.18;
            var igvRedondeado = igv.toFixed(2);
            let subTotal = res.total - igv;
            var subTotalRedondeado = subTotal.toFixed(2);
            document.getElementById("idpedido_text").innerHTML = res.id;
            document.getElementById("id_pedido").value = res.id;
            document.querySelector('#subtotal').textContent = subTotalRedondeado;
            document.querySelector('#igv').textContent = igvRedondeado;
            document.querySelector('#total_venta').textContent = res.total;
            const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz', 'cortesia'];
            const camposop = ['op_efect', 'op_visa', 'op_mast', 'op_diners', 'op_express', 'op_cort_sol', 'op_yape', 'op_plin', 'op_izipay', 'op_niubiz', 'op_cort'];
            const mostrar = ['mostrarEfectivo', 'mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz', 'mostrarCortesia'];

            for (const campo of campos) {
                const mostrarCampo = mostrar[campos.indexOf(campo)];
                document.getElementById(mostrarCampo).checked = false;
                toggleCampoEntrada(mostrarCampo, campo, camposop[campos.indexOf(campo)]);
            }
            
            for (const campo of campos) {
                const mostrarCampo = mostrar[campos.indexOf(campo)];
                const inputCampo = document.getElementById(campo);
                
                if (res[campo] !== "0.00") {
                    document.getElementById(mostrarCampo).checked = true;
                    toggleCampoEntrada(mostrarCampo, campo, camposop[campos.indexOf(campo)]);
                    inputCampo.value = res[campo];
                } else {
                    inputCampo.value = "";
                }
            }
            $("#modalProcesarPedido").modal("show");
        }
    }
}

function actualizarTotal() {
    let totalVentaText = document.querySelector('#totalGeneral').textContent;
    let totalWithoutCommas = totalVentaText.replace(/[^\d.]/g, '');
    let total = parseFloat(totalWithoutCommas);
    document.getElementById('total').value = parseFloat(total);
}

function registrarPedido(e) {
    e.preventDefault();
    actualizarTotal();
    const url = base_url + "Pedidos/registrarPedidoFacturado";
    const frm = document.getElementById("frmInfoPed");
    const formData = new FormData(frm);
    const datosPedido = {
        datos_pedido: Object.fromEntries(formData),
        listaCarrito: listaCarrito
    };
    document.getElementById("loader").style.display = "block";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    http.send(JSON.stringify(datosPedido));
    
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if (res['msg'] === 'ok') {
                document.getElementById("loader").style.display = "none";
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'PEDIDO PROCESADO',
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {
                    localStorage.removeItem('listaCarrito')
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });
            }else{
                alertas(res.msg, res.icon);
            }
        }else{
            document.getElementById("loader").style.display = "none";
            alertas("Error de conexión: " + this.status, "error");
        }
    }
}

document.querySelectorAll('.btnAumentarCantidad').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const quantityInput = this.parentElement.querySelector('.quantity');
        const hiddenInput = this.parentElement.querySelector('input[type="hidden"]');
        let quantity = parseInt(quantityInput.value);
        quantity++;
        quantityInput.value = quantity;
        hiddenInput.value = quantity;
    });
});

document.querySelectorAll('.btnDisminuirCantidad').forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const quantityInput = this.parentElement.querySelector('.quantity');
        const hiddenInput = this.parentElement.querySelector('input[type="hidden"]');
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantity--;
            quantityInput.value = quantity;
            hiddenInput.value = quantity;
        }
    });
});

function btnVerStockProducto(idProducto){
    const modalStock = new bootstrap.Modal(document.getElementById('StockPorProducto'));
    const url = base_url + "Pedidos/mostrarStockProducto/" + idProducto;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let html = '';
            res.productosStock.forEach(row => {
                html += `<tr>
                    <td>${row.almacen}</td>
                    <td>${row.stock}</td>
                </tr>`;
                nombre = row.descripcion;
            });
            document.querySelector('#tblStockPorProductos').innerHTML = html;
            document.querySelector('#ProductName').innerHTML = nombre;
            modalStock.show();
        }
    }
}