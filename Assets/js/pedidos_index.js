
let tblObtenerPedidos, tblPedidosPagados, tblTokensPedidos, tblPedidosAnulados, tblHistorialEntradas;
document.addEventListener("DOMContentLoaded",function(){
    tblPedidosPagados = $("#tblPedidosPagados").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
    tblPedidosAnulados = $("#tblPedidosAnulados").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
    });

    document.getElementById('pdfButton').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/pdfPorFechas";
        document.getElementById('exportForm').submit();
    });
    document.getElementById('pdfVentaDetallado').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/pdfVentasDetalladas";
        document.getElementById('exportForm').submit();
    });
    
    document.getElementById('excelButton').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/exportarExcelPorRangos";
        document.getElementById('exportForm').submit();
    });
    
    document.getElementById('resumenGeneral').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/resumenGeneral";
        document.getElementById('exportForm').submit();
    });
    
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        document.getElementById('exportForm').action = base_url + "Pedidos/exportarExcelPorRangosDetallado";
        document.getElementById('exportForm').submit();
    });

    $('#usuario').select2({
        theme: 'bootstrap4'
    });
})

var selectedValue = 1;

function setValue(value) {
    selectedValue = value;
}

function buscarPedidosFecha() {
    if (!selectedValue) {
        selectedValue = 1;
    }

    const url = base_url + "Pedidos/buscarPedidosFecha/" + selectedValue;
    const frm = document.getElementById("exportForm");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));

    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';

            res.forEach(row => {
                html += `<tr>
                            <td>${row.id}</td>
                            <td>${row.almacen}</td>
                            <td>${row.fecha}</td>`;

                if (row.dni !== null) {
                    html += `<td>${row.dni}</td>
                             <td>${row.nombres}</td>`;
                } else {
                    html += `<td>${row.ruc}</td>
                             <td>${row.razon_social}</td>`;
                }

                html += `<td>${row.cconnumero ? row.cconnumero : '-'}</td>
                         <td>${row.Fcfmanivel ? row.Fcfmanivel : '-'}</td>
                         <td>${row.Fcfmaserie ? row.Fcfmaserie : '-'}</td>
                         <td>${row.Fcfmanumero ? row.Fcfmanumero : '-'}</td>
                         <td>${row.total}</td>
                         <td>${row.estado}</td>`;

                if (row.Fcfmanumero) {
                    html += `<td id="estado_sunat_${row.id}">Cargando...</td>`;
                } else {
                    html += `<td>-</td>`;
                }

                html += `<td>${row.acciones}</td>
                         </tr>`;
            });

            if (selectedValue == 1) {
                if ($.fn.DataTable.isDataTable('#tblPedidosPagados')) {
                    $('#tblPedidosPagados').DataTable().destroy();
                }
                document.querySelector('#tblPagosJs').innerHTML = html;
                $('#tblPedidosPagados').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "drawCallback": function(settings) {
                        $('#tblPedidosPagados tbody tr').each(function() {
                            const rowId = $(this).find('td:first').text();
                            const fcfmanumero = $(this).find('td:nth-child(9)').text();
                            obtenerEstadoSunat(fcfmanumero, rowId);
                        });
                    }
                });
            } else {
                if ($.fn.DataTable.isDataTable('#tblPedidosAnulados')) {
                    $('#tblPedidosAnulados').DataTable().destroy();
                }
                document.querySelector('#tblPagosAnuladosJS').innerHTML = html;
                $('#tblPedidosAnulados').DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "drawCallback": function(settings) {
                        $('#tblPedidosAnulados tbody tr').each(function() {
                            const rowId = $(this).find('td:first').text();
                            const fcfmanumero = $(this).find('td:nth-child(9)').text();
                            obtenerEstadoSunat(fcfmanumero, rowId);
                        });
                    }
                });
            }

            $("#fac_nofacf_proc").change(function() {
                const selectedIndex = this.selectedIndex;
                let searchValue = '';
                let table = $('#tblPedidosPagados').DataTable();

                switch (selectedIndex) {
                    case 1:
                        searchValue = '[^\\-]+';
                        break;
                    case 2:
                        searchValue = '^-$';
                        break;
                    case 3:
                        searchValue = '.*';
                        break;
                    default:
                        searchValue = '';
                        break;
                }

                table.column(5).search(searchValue, true, false).draw();
            });
        }
    }
}

  function actualizarEstadosSunat(tableSelector) {
    $(tableSelector + ' tbody tr').each(function() {
        const row = $(this).find('td')[7];
        const numeroFactura = $(row).text().trim();
        if (numeroFactura) {
            const idPedido = $(this).find('td')[0].textContent.trim();
            obtenerEstadoSunat(numeroFactura, idPedido);
        }
    });
}

document.addEventListener("DOMContentLoaded", function(){
    tblHistorialEntradas = $('#tblHistorialEntradas').DataTable({
                ajax: {
                    url: base_url + "Pedidos/listarHistorialEntradas",
                    dataSrc: '',
                    contentType: "application/json; charset=utf-8"
                },
                columns: [
                {
                    'data' : 'id'
                },
                {
                    'data' : 'fecha'
                },
                {
                    'data' : 'nombre_usuario'
                },
                {
                    'data' : 'nombre_almacen'
                },
                {
                    'data' : 'documento'
                },
                {
                    'data' : 'serie'
                },
                {
                    'data' : 'correlativo'
                },
                {
                    'data' : 'estado'
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false
        });
});

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

function btnEditarTipoPago(idPedido){
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
            document.getElementById("id_pedido").value = res.id;
            document.querySelector('#subtotal').textContent = subTotalRedondeado;
            document.querySelector('#igv').textContent = igvRedondeado;
            document.querySelector('#total_venta').textContent = res.total;
            document.querySelector('#dineroActual').innerHTML = `<strong>S/. ${res.total}</strong>`;
            document.getElementById('pedido_numero').textContent = res.id;
            const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz','pos', 'transferencia'];
            const camposop = ['op_efect', 'op_visa', 'op_mast', 'op_diners', 'op_express', 'op_yape', 'op_plin', 'op_izipay', 'op_niubiz','op_pos', 'op_transf'];
            const mostrar = ['mostrarEfectivo','mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz','mostrarPos', 'mostrarTransferencia'];
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
            }
            function cantidadTotalEdit() {
                actualizarDineroActual();
            }

            for (const campo of campos) {
                const inputCampo = document.getElementById(campo);
                inputCampo.addEventListener('input', cantidadTotalEdit);
            }
        
            for (const campo of campos) {
                const mostrarCampo = mostrar[campos.indexOf(campo)];
                document.getElementById(mostrarCampo).checked = false;
                toggleCampoEntrada(mostrarCampo, campo, camposop[campos.indexOf(campo)]);
            }
            
            for (const campo of campos) {
                const mostrarCampo = mostrar[campos.indexOf(campo)];
                const inputCampo = document.getElementById(campo);
                const inputCampoOp = document.getElementById(camposop[campos.indexOf(campo)]);
                inputCampo.addEventListener('input', cantidadTotalEdit);
                
                if (res[campo] !== "0.00") {
                    document.getElementById(mostrarCampo).checked = true;
                    toggleCampoEntrada(mostrarCampo, campo, camposop[campos.indexOf(campo)]);
                    inputCampo.value = res[campo];
                } else {
                    inputCampo.value = "";
                }

                if (res[camposop[campos.indexOf(campo)]] !== "0") {
                    inputCampoOp.value = res[camposop[campos.indexOf(campo)]];
                } else {
                    inputCampoOp.value = "";
                }
            }
            $("#modalProcesarPedido").modal("show");
        }
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

function btnAnularPedido(idPedido){
    Swal.fire({
        title: 'Esta seguro?',
        text: "Se anulara el pedido de forma permanente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Anular'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Pedidos/cancelarPedido/"+idPedido;
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'PEDIDO ANULADO',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        location.reload();
                    });
                }
            }
        }
      })
}

function obtenerEstadoSunat(Fcfmanumero, rowId) {
    if (Fcfmanumero == "-") {
        return;
    }
    const url = base_url + "Pedidos/consultaEstadoDocSunat/" + Fcfmanumero;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            const elemento = document.getElementById(`estado_sunat_${rowId}`);
            if (elemento) {
                if (res['res_post'] == "no") {
                    elemento.textContent = "";
                } else {
                    if (res['codigo'] == 0) {
                        elemento.textContent = "ACEPTADO";
                    } else if (res['codigo'] != 0) {
                        elemento.textContent = "RECHAZADO";
                    }
                }
            }
        }
    }
}

function detalleEstadoSunat(Fcfmanumero) {
    if(!Fcfmanumero){
        alertas('Este pedido un no fue procesado por SUNAT', 'warning');
    }else{
        const url = base_url + "Pedidos/consultaEstadoDocSunat/" + Fcfmanumero;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                if(res['res_post'] == 'si'){
                    document.getElementById('codigo_doc').value = res['codigo']
                    document.getElementById('descripcion_doc').value = res['descripcion']
                    document.getElementById('hash_doc').value = res['hash']
    
                    const qrImg = document.createElement('img');
                    qrImg.src = res['qr'];
                    qrImg.style.width = '200px';
                    qrImg.style.height = 'auto';
                    const qrContainer = document.getElementById('qr_container');
                    qrContainer.innerHTML = '';
                    qrContainer.appendChild(qrImg);
                    $("#modal_consulta_sunat").modal("show");
                }else{
                    alertas('No se encontro', 'warning');
                }
            }
        }
    }
}