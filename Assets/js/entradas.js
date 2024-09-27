let tblEntradaNormal, tblEntradaCort, tblEntradaPromot;
document.addEventListener("DOMContentLoaded", function(){
    tblEntradaNormal = $('#tblEntradaNormal').DataTable({
                ajax: {
                    url: base_url + "Entradas/listar",
                    dataSrc: ''
                },
                columns: [
                {
                    'data' : 'id'
                },
                {
                    'data' : 'dni'
                },
                {
                    'data' : 'ruc'
                },
                {
                    'data' : 'nombre'
                },
                {
                    'data' : 'genero'
                },
                {
                    'data' : 'fecha'
                },
                {
                    'data' : "total"
                },
                {
                    'data' : "acciones"
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
    
        const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz'];
        const camposop = ['op_efect', 'op_visa', 'op_mast', 'op_diners', 'op_express', 'op_yape', 'op_plin', 'op_izipay', 'op_niubiz'];
        const mostrar = ['mostrarEfectivo', 'mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz'];
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
})


document.getElementById('tipo_doc').addEventListener("change", function() {
    var valorSeleccionado = this.value; 

    if (valorSeleccionado === "1") {
        buscarCorr('BE');
        document.getElementById('campos_dni').classList.remove('d-none');
        document.getElementById('campos_ruc').classList.add('d-none');
    } else if (valorSeleccionado === "2"){
        buscarCorr('FE');
        document.getElementById('campos_ruc').classList.remove('d-none');
        document.getElementById('campos_dni').classList.add('d-none');
    } else {
        document.getElementById('campos_ruc').classList.add('d-none');
        document.getElementById('campos_dni').classList.add('d-none');
        document.getElementById('serie_doc').value = "";
        document.getElementById('correl_doc').value = "";
        document.getElementById("nombre_cli").value = "";
    }
});

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

function frmEntradaCortesia() {
    $("#pedido_cortesia").modal("show");
}

function frmEntradaPromotor() {
    $("#pedido_promotor").modal("show");
}

function regEntrNorml(e){
    e.preventDefault();
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
    let total_pago = efectivo + visa + master + diners + a_express + cortesia + yape + plin + izipay + niubiz;
    let cantidad = document.getElementById("cantidad").value;
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
    if(cantidad <= 0){
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESE UNA CANTIDAD',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        return;
    }

    if(document.getElementById("tipo_cambio").value == "" || document.getElementById("nombre_cli").value == "" || document.getElementById("tipo_entrada").value == "" || document.getElementById("tipo_doc").value == ""){
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESE TODOS LOS CAMPOS',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        return;
    }

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

    if(total >= 700){
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'MONTO MAYOR A 700 , ES NECESARIO DIGITAR UN DNI',
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
        return;
    }

    document.getElementById("loader").style.display = "block";
    const url = base_url + "Entradas/regEntrNorml";
    const frm = document.getElementById("registroEntradaForm");
    document.getElementById("loader").style.display = "block";
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if (res['msg'] === 'ok') {
                document.getElementById("loader").style.display = "none";
                document.getElementById('cambio').value = (total_pago - res.total).toFixed(2);
                if (total_pago == res.total) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'VENTA REALIZADA',
                        showConfirmButton: false,
                        timer: 1000
                    }).then(() => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    });
                } else if(total_pago >= res.total){
                    var vuelto = (total_pago - res.total).toFixed(2);
                    Swal.fire({
                        position: 'center',
                        icon: 'info',
                        title: 'VUELTO: ' + vuelto,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 100);
                        }
                    });
                }
            }else {
                alertas(res.msg, res.icon);
            }
        }else{
            document.getElementById("loader").style.display = "none";
            alertas("Error de conexión: " + this.status, "error");
        }
    }
}

function buscarCorr(parametro)
{
    const url = base_url + "Entradas/buscarCorr/" + parametro;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("serie_doc").value = res.serie;
            document.getElementById("correl_doc").value = res.correlativo;
            document.getElementById("parametro").value = parametro;
        }
    }
}
function regEntrCort(){
    const url = base_url + "Entradas/regEntrCort";
    const frm = document.getElementById("registroEntradaCortForm");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            frm.reset();
            $("#pedido_cortesia").modal("hide");
            alertas(res.msg, res.icono);
            setTimeout(function() {
                location.reload();
            }, 1000);   
        }
    }
}

function buscarToken(e) {
    e.preventDefault();
    const token = document.getElementById("token").value;
    if(token != ''){
        const token = document.getElementById("token").value;
        const url = base_url + "Entradas/buscarSolicitante/"+token;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res){
                    document.getElementById("solic").value = res.solicitante;
                    document.getElementById("cant").value = res.disponible;
                }else{
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'TOKEN NO VALIDO',
                    }); 
                }
                
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR TOKEN',
        });
    }
}

function buscarTokenPromot() {
    const token = document.getElementById("token").value;
    if(token != ''){
        const token = document.getElementById("token").value;
        const url = base_url + "Entradas/buscarSolicitantPromot/"+token;
        const http = new XMLHttpRequest();
        http.open("GET", url, true);
        http.send();
        http.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const res = JSON.parse(this.responseText);
                if(res == ''){
                    setTimeout(function() {
                        location.reload();
                    }, 1000); 
                }else{
                    document.getElementById("solic").value = res.solicitante;
                    document.getElementById("cant").value = res.disponible;
                }
            }
        }
    }else{
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'INGRESAR TOKEN',
        });
    }
}

function regEntrPromot(){
    const url = base_url + "Entradas/regEntrPromot";
    const frm = document.getElementById("registroEntradaPromotForm");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById('nombre_cli').value = '';
            $cant = document.getElementById("cant").value;
            buscarTokenPromot();
            alertas(res.msg, res.icono);
            tblEntradaPromot.ajax.reload();
        }
    }
}

function consultaDNI(e) {
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
                document.getElementById("nombre_cli").value = res.nombres+' '+res.apellidoPaterno+' '+res.apellidoMaterno;
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

function consultaRUC(e) {
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
                    document.getElementById("nombre_cli").value = res.razon_social;
                }else if('razonSocial' in res){
                    document.getElementById("nombre_cli").value = res.razonSocial;
                }
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

function consultaTipoCambio(e) {
    e.preventDefault();
    const url = base_url + "Entradas/consulta_tipo_cambio/";
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            document.getElementById("tipo_cambio").value = res.precioVenta;
        }
    }
}

function buscarEnBD(idEntrada)
{
    const url = base_url + "Entradas/consultarPrecioProd/"+ idEntrada;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let totalSinComa = parseFloat(res.precio_venta.replace(/,/g, ''));
            let igv = 0.00;
            if(res.afecta_igv == 1){
                igv = totalSinComa * 0.18;
            }

            var igvRedondeado = igv.toFixed(2);
            let subTotal = totalSinComa - igv;
            var subTotalRedondeado = subTotal.toFixed(2);

            document.querySelector('#subtotal').textContent = subTotalRedondeado;
            document.querySelector('#igv').textContent = (res.afecta_igv == 1) ? igvRedondeado : "0.00";
            document.querySelector('#total_venta').textContent = res.precio_venta;
            const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz'];
            const camposop = ['op_efect', 'op_visa', 'op_mast', 'op_diners', 'op_express', 'op_yape', 'op_plin', 'op_izipay', 'op_niubiz'];
            const mostrar = ['mostrarEfectivo', 'mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz'];
        
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
            function onCantidadInputChange() {
                const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
                const totalVenta = parseFloat(res.precio_venta);
                const nuevoTotal = cantidad * totalVenta;
                document.getElementById('total_venta').textContent = nuevoTotal.toFixed(2);
                document.getElementById('subtotal').textContent = nuevoTotal.toFixed(2);
                actualizarDineroActual();
            }
            document.getElementById('cantidad').value = "";
            document.getElementById('cantidad').addEventListener('keyup', onCantidadInputChange);
            reiniciarCampos();
        }
    }
}

function reiniciarCampos() {
    const campos = ['efectivo', 'visa', 'master_c', 'diners', 'a_express', 'yape', 'plin', 'izipay', 'niubiz'];
    const mostrar = ['mostrarEfectivo', 'mostrarVisa', 'mostrarMaster', 'mostrarDiners', 'mostrarExpress', 'mostrarYape', 'mostrarPlin', 'mostrarizipay', 'mostrarniubiz'];

    for (const campo of campos) {
        const inputCampo = document.getElementById(campo);
        inputCampo.value = "";
    }

    // Desmarcar las casillas de verificación
    for (const mostrarCampo of mostrar) {
        document.getElementById(mostrarCampo).checked = false;
    }

    // Reiniciar valores de dineroActual, falta y vuelto
    document.getElementById('dineroActual').innerHTML = "<strong>S/. 0.00</strong>";
    document.getElementById('falta').innerHTML = "<strong>S/. 0.00</strong>";
    document.getElementById('vuelto').innerHTML = "<strong>S/. 0.00</strong>";
}