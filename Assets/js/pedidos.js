document.addEventListener("DOMContentLoaded", function(){
    cargarDetallePed();
});

const busquedaProd = document.querySelector('#buscarProdPant');
const parametroBusq = document.getElementById('parametroBusq').value;
const lineaparamBusq = document.getElementById('lineaparamBusq').value;

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

function cancelarOrden()
{
    localStorage.removeItem('listaCarrito');
    setTimeout(() => {
        window.location.reload();
    }, 100);
}

function toggleCampoEntrada(checkboxId, inputId, campoOperacionId) {
    var checkbox = document.getElementById(checkboxId);
    var campoEntrada = document.getElementById(inputId);
    var campoOperacion = document.getElementById(campoOperacionId);

    if (checkbox.checked) {
        campoEntrada.classList.remove("d-none");
        campoOperacion.classList.remove("d-none");
    } else {
        campoEntrada.classList.add("d-none");
        campoOperacion.classList.add("d-none");
    }
}
function registrarPedidoFacturado(e) {
    e.preventDefault();
    id_pedido_edit = document.getElementById("id_pedido").value;
    if(id_pedido_edit == ""){
        const correo = document.getElementById("correo").value;
        var correoRegExp = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (correo !== '') {
            if (!correoRegExp.test(correo)) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Correo Electrónico Inválido',
                    text: 'Por favor ingrese un correo electrónico válido',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
        }
        t_documento = document.getElementById("t_documento").value;
        if (t_documento !== "") {
            let efectivo = parseFloat(document.querySelector('#efectivo').value) || 0;
            let visa = parseFloat(document.querySelector('#visa').value) || 0;
            let master = parseFloat(document.querySelector('#master_c').value) || 0;
            let diners = parseFloat(document.querySelector('#diners').value) || 0;
            let a_express = parseFloat(document.querySelector('#a_express').value) || 0;
            let yape = parseFloat(document.querySelector('#yape').value) || 0;
            let plin = parseFloat(document.querySelector('#plin').value) || 0;
            let izipay = parseFloat(document.querySelector('#izipay').value) || 0;
            let niubiz = parseFloat(document.querySelector('#niubiz').value) || 0;
            let cortesia = parseFloat(document.querySelector('#cortesia').value) || 0;
            let pos = parseFloat(document.querySelector('#pos').value) || 0;
            let transferencia = parseFloat(document.querySelector('#transferencia').value) || 0;
            let total_pago = efectivo + visa + master + diners + a_express + cortesia + yape + plin + izipay + niubiz + pos + transferencia;
            let totalVentaText = document.querySelector('#total_venta').textContent;
            let totalWithoutCommas = totalVentaText.replace(/[^\d.]/g, '');
            let total = parseFloat(totalWithoutCommas);
            document.getElementById('total_pedido').value = parseFloat(total);

            let totalIgvText = document.querySelector('#igv').textContent;
            let totalIgvWithoutCommas = totalIgvText.replace(/[^\d.]/g, '');
            let total_igv = parseFloat(totalIgvWithoutCommas);
            document.getElementById('total_igv').value = parseFloat(total_igv);

            
            let totalBaseText = document.querySelector('#subtotal').textContent;
            let totalBaseWithoutCommas = totalBaseText.replace(/[^\d.]/g, '');
            let total_base = parseFloat(totalBaseWithoutCommas);
            document.getElementById('total_base').value = parseFloat(total_base);
    
            if (total_pago < total) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'FALTA EFECTIVO',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                return;
            }
    
            const url = base_url + "Pedidos/registrarPedidoFacturado";
            const frm = document.getElementById("frmPedidoFacturado");
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
            http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    if (res['msg'] === 'ok') {
                        document.getElementById("loader").style.display = "none";
                        if(res['tipo_documento'] == 1 || res['tipo_documento'] == 2){
                            document.getElementById('cambio').value = (total_pago - total).toFixed(2);
                            if (total_pago == total) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'PEDIDO PROCESADO',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => {
                                    /*const serie = res['serie'];
                                    const correlativo = res['correlativo'];
                                    const datos = serie + '-' + correlativo;*/
                                    const id_pedido = res['id_pedido'];
                                    const pdfUrl = base_url + "Pedidos/generarPdfPedido/" + encodeURIComponent(id_pedido);
                                    window.open(pdfUrl, '_blank');
                                    localStorage.removeItem('listaCarrito');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 100);
                                });
                            } else if(total_pago >= total){
                                var vuelto = (total_pago - total).toFixed(2);
                                Swal.fire({
                                    position: 'center',
                                    icon: 'info',
                                    title: 'VUELTO: ' + vuelto,
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const id_pedido = res['id_pedido'];
                                        const pdfUrl = base_url + "Pedidos/generarPdfPedido/" + encodeURIComponent(id_pedido);
                                        window.open(pdfUrl, '_blank');
                                        localStorage.removeItem('listaCarrito');
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 100);
                                    }
                                });
                            }
                        }else{
                            document.getElementById('cambio').value = (total_pago - total).toFixed(2);
                            if (total_pago == total) {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'PEDIDO PROCESADO',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => {
                                    const id_pedido = res['id_pedido'];
                                    const pdfUrl = base_url + "Pedidos/generarPdfPedido/" + encodeURIComponent(id_pedido);
                                    window.open(pdfUrl, '_blank');
                                    localStorage.removeItem('listaCarrito');
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 100);
                                });
                            } else if(total_pago >= total){
                                var vuelto = (total_pago - total).toFixed(2);
                                Swal.fire({
                                    position: 'center',
                                    icon: 'info',
                                    title: 'VUELTO: ' + vuelto,
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        const id_pedido = res['id_pedido'];
                                        const pdfUrl = base_url + "Pedidos/generarPdfPedido/" + encodeURIComponent(id_pedido);
                                        window.open(pdfUrl, '_blank');
                                        localStorage.removeItem('listaCarrito');
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 100);
                                    }
                                });
                            }
                        }
                    } else {
                        alertas(res.msg, res.icon);
                    }
                }else{
                    document.getElementById("loader").style.display = "none";
                    alertas("Error de conexión: " + this.status, "error");
                }
            };
        } else {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'POR FAVOR, SELECCIONAR UN TIPO DE DOCUMENTO',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }else{
        let efectivo = parseFloat(document.querySelector('#efectivo').value) || 0;
        let visa = parseFloat(document.querySelector('#visa').value) || 0;
        let master = parseFloat(document.querySelector('#master_c').value) || 0;
        let diners = parseFloat(document.querySelector('#diners').value) || 0;
        let a_express = parseFloat(document.querySelector('#a_express').value) || 0;
        let yape = parseFloat(document.querySelector('#yape').value) || 0;
        let plin = parseFloat(document.querySelector('#plin').value) || 0;
        let izipay = parseFloat(document.querySelector('#izipay').value) || 0;
        let niubiz = parseFloat(document.querySelector('#niubiz').value) || 0;
        let cortesia = parseFloat(document.querySelector('#cortesia').value) || 0;
        let pos = parseFloat(document.querySelector('#pos').value) || 0;
        let transferencia = parseFloat(document.querySelector('#transferencia').value) || 0;
        let total_pago = efectivo + visa + master + diners + a_express + cortesia + yape + plin + izipay + niubiz + pos + transferencia;
        let totalVentaText = document.querySelector('#total_venta').textContent;
        let totalWithoutCommas = totalVentaText.replace(/[^\d.]/g, '');
        let total = parseFloat(totalWithoutCommas);
        document.getElementById('total_pedido').value = total;

        if (total_pago < total) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'FALTA EFECTIVO',
                showConfirmButton: true,
                confirmButtonText: 'OK'
            });
            return;
        }

        const url = base_url + "Pedidos/registrarPedidoFacturado";
        const frm = document.getElementById("frmPedidoFacturado");
        const formData = new FormData(frm);
        const datosPedido = {
            datos_pedido: Object.fromEntries(formData),
            listaCarrito: listaCarrito
        };

        const http = new XMLHttpRequest();
        http.open("POST", url, true);
        http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        http.send(JSON.stringify(datosPedido));
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if (res === 'ok') {
                    document.getElementById('cambio').value = (total_pago - total).toFixed(2);

                    if (total_pago >= total) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'PEDIDO PROCESADO',
                            showConfirmButton: false,
                            timer: 1000
                        }).then(() => {
                            localStorage.removeItem('listaCarrito');
                            setTimeout(() => {
                                window.location.reload();
                            }, 100);
                        });
                    } else {
                        var vuelto = (total_pago - total).toFixed(2);
                        Swal.fire({
                            position: 'center',
                            icon: 'info',
                            title: 'VUELTO: ' + vuelto,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                localStorage.removeItem('listaCarrito');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 100);
                            }
                        });
                    }
                } else {
                    alertas(res.msg, res.icon);
                }
            }
        };
    }
}

function btnVerDetallePedido() {
    const url = base_url + "Pedidos/listarPed";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(JSON.stringify(listaCarrito));
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);

            let html = '';
            let tieneIGV = false;
            res.productos.forEach(row => {
                const precioSinComa = parseFloat(row.precio.replace(/,/g, ''));
                const cantidad = parseFloat(row.cantidad);
                html += `<tr>
                    <td>${row.nombre}</td>
                    <td><span class="badge bg-info">S/. ${row.precio}</span></td>
                    <td>${row.cantidad}</td>
                    <td>${row.igv}</td>
                    <td>S/. ${row.sub_total}</td>
                </tr>`;
                if (row.igv_dato == 1) {
                    tieneIGV = true;
                }
            });

            let totalSinComa = parseFloat(res.total.replace(/,/g, ''));
            let igv = 0.00;
            if (tieneIGV) {
                igv = totalSinComa * 0.18;
            }
            var igvRedondeado = igv.toFixed(2);
            let subTotal = totalSinComa - igv;
            var subTotalRedondeado = subTotal.toFixed(2);

            document.querySelector('#tblDetalleOP').innerHTML = html;
            document.querySelector('#subtotal').textContent = subTotalRedondeado;
            document.querySelector('#igv').textContent = tieneIGV ? igvRedondeado : "0.00";
            document.querySelector('#total_venta').textContent = res.total;

            document.getElementById('t_documento').addEventListener('change', function() {
                var selectedOption = this.value;
                var campoDatos = document.getElementById('campo_datos');
                var campoDatosFact = document.getElementById('campo_datos_fact');
                if (selectedOption == 1) {
                    buscarSCV('B');
                    document.getElementById("dni").value = "";
                    document.getElementById("nombres").value = "";
                    document.getElementById("apellido_paterno").value = "";
                    document.getElementById("apellido_materno").value = "";
                    document.getElementById("correo").value = "";
                    // Elimina la coma antes de comparar
                    if (totalSinComa > 700) {
                        campoDatos.classList.remove('d-none');
                        campoDatosFact.classList.add('d-none');
                        document.getElementById("campo_correo").classList.remove('d-none');
                    } else {
                        campoDatos.classList.add('d-none');
                        campoDatosFact.classList.add('d-none');
                        document.getElementById("campo_correo").classList.add('d-none');
                    }
                } else if(selectedOption == 2){
                    buscarSCV('F');
                    document.getElementById("ruc").value = "";
                    document.getElementById("razon_social").value = "";
                    document.getElementById("direccion").value = "";
                    document.getElementById("correo").value = "";
                    campoDatosFact.classList.remove('d-none');
                    campoDatos.classList.add('d-none');
                    document.getElementById("campo_correo").classList.remove('d-none');
                } else {
                    document.getElementById("dni").value = "";
                    document.getElementById("nombres").value = "";
                    document.getElementById("apellido_paterno").value = "";
                    document.getElementById("apellido_materno").value = "";
                    document.getElementById("ruc").value = "";
                    document.getElementById("razon_social").value = "";
                    document.getElementById("direccion").value = "";
                    document.getElementById("serie").value = "";
                    document.getElementById("correlativo").value = "";
                    document.getElementById("correo").value = "";
                    campoDatos.classList.add('d-none');
                    campoDatosFact.classList.add('d-none');
                    document.getElementById("campo_correo").classList.add('d-none');
                }
            });
        }
    }
}

function buscarSCV(parametro)
{
    const url = base_url + "Pedidos/buscarSCB/" + parametro;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("serie").value = res.serie;
            document.getElementById("correlativo").value = res.correlativo;
            document.getElementById("parametro").value = parametro;
        }
    }
}

function verificarPedido()
{
    btnVerDetallePedido();
    document.getElementById('btnAccion').innerHTML = "Registrar";
    const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz','pos', 'transferencia'];
    const camposop = ['op_efect', 'op_visa', 'op_mast', 'op_diners', 'op_express', 'op_yape', 'op_plin', 'op_izipay', 'op_niubiz', 'op_pos', 'op_transf'];
    const mostrar = ['mostrarEfectivo', 'mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz', 'mostrarPos', 'mostrarTransferencia'];

    function actualizarVuelto(totalVenta, dineroActual) {
        let vuelto;
        if (dineroActual === 0 || dineroActual < totalVenta) {
            vuelto = 0;
        } else {
            vuelto = dineroActual - totalVenta;
            vuelto = Math.max(0, vuelto);
        }
        const vueltoElement = document.getElementById('vuelto');
        vueltoElement.innerHTML = `<strong>S/. ${vuelto.toFixed(2)}</strong>`;
        vueltoElement.style.fontSize = '40px';
        vueltoElement.style.color = '#800000';
    }

    function actualizarFalta(totalVenta, dineroActual) {
        let falta;
        if (dineroActual === 0) {
            falta = 0;
        } else {
            falta = totalVenta - dineroActual;
            falta = Math.max(0, falta);
        }
        const faltaElement = document.getElementById('falta');
        faltaElement.innerHTML = `<strong>S/. ${falta.toFixed(2)}</strong>`;
        faltaElement.style.fontSize = '40px';
        faltaElement.style.color = '#FF5733';
    }

    function actualizarDineroActual() {
        let total = 0;

        for (const campo of campos) {
            const valor = parseFloat(document.getElementById(campo).value) || 0;
            total += valor;
        }
        const dineroActualElement = document.getElementById('dineroActual');
        dineroActualElement.innerHTML = `<strong>S/. ${total.toFixed(2)}</strong>`;
        dineroActualElement.style.fontSize = '40px';
        dineroActualElement.style.color = '#004080';

        const totalVenta = parseFloat(document.getElementById('total_venta').textContent.replace(/[^\d.]/g, ''));
        actualizarVuelto(totalVenta, total);
        actualizarFalta(totalVenta, total);
    }
    function onCantidadInputChange() {
        actualizarDineroActual();
    }
    for (const campo of campos) {
        const inputCampo = document.getElementById(campo);
        inputCampo.addEventListener('input', onCantidadInputChange);
    }

    for (const campo of campos) {
        const mostrarCampo = mostrar[campos.indexOf(campo)];
        const inputCampo = document.getElementById(campo);
        document.getElementById(mostrarCampo).checked = false;
        toggleCampoEntrada(mostrarCampo, campo, camposop[campos.indexOf(campo)]);
        inputCampo.value = "";
    }
    $("#modalProcesarPedido").modal("show");
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

function consultaRuc(e)
{
    e.preventDefault();
    const ruc = document.getElementById("ruc").value;
    if(ruc != ''){
        const ruc = document.getElementById("ruc").value;
        const url = base_url + "Pedidos/consultaRUC/"+ruc;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if('razon_social' in res){
                    document.getElementById("razon_social").value = res.razon_social;
                }else if('razonSocial' in res){
                    document.getElementById("razon_social").value = res.razonSocial;
                }
                document.getElementById("direccion").value = res.direccion;
                document.getElementById("correo").value = res.correo;
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR RUC',
        });
    }
}

function consultaDni(e) {
    e.preventDefault();
    const dni = document.getElementById("dni").value;
    if(dni != ''){
        const dni = document.getElementById("dni").value;
        const url = base_url + "Pedidos/consultaDNI/"+dni;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                document.getElementById("nombres").value = res.nombres;
                document.getElementById("apellido_paterno").value = res.apellidoPaterno;
                document.getElementById("apellido_materno").value = res.apellidoMaterno;
                document.getElementById("correo").value = res.correo;
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR DNI',
        });
    }
}