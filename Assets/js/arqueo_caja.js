let tblArqueoCaja;
document.addEventListener("DOMContentLoaded", function(){
    tblArqueoCaja = $('#tblArqueoCaja').DataTable({
                ajax: {
                    url: base_url + "Arqueo/listar",
                    dataSrc: '',
                    contentType: "application/json; charset=utf-8"
                },
                columns: [
                {
                    'data' : 'id'
                },
                {
                    'data' : 'nombre'
                },
                {
                    'data' : "monto_inicial"
                },
                {
                    'data' : "monto_corte"
                },
                {
                    'data' : "monto_final"
                },
                {
                    'data' : "fecha_apertura"
                },
                {
                    'data' : "fecha_cierre"
                },
                {
                    'data' : "total_ventas"
                },
                {
                    'data' : "monto_total"
                },
                {
                    'data' : "estado"
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false
    });
    const http = new XMLHttpRequest();
    const url = base_url + "Arqueo/verEstadoArqueo/";
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            let consult_caja = res.cajaAbierta[0].estado;
            if(consult_caja === 1){
                document.getElementById('a_caja').classList.add("d-none");
                document.getElementById('p_caja').classList.remove("d-none");
            }else if(consult_caja === 2){
                document.getElementById('a_caja').classList.add("d-none");
                document.getElementById('p_caja').classList.add("d-none");
                document.getElementById('c_caja').classList.remove("d-none");
            }else{
                document.getElementById('p_caja').classList.add("d-none");
                document.getElementById('c_caja').classList.add("d-none");
            }
        }
    }
})

document.addEventListener("DOMContentLoaded", function(){
    tblProductosStock = $('#tblProductosStock').DataTable({
                ajax: {
                    url: base_url + "Arqueo/listarStock",
                    dataSrc: '',
                    contentType: "application/json; charset=utf-8"
                },
                columns: [
                {
                    'data' : 'id_producto'
                },
                {
                    'data' : 'foto'
                },
                {
                    'data' : 'descripcion'
                },
                {
                    'data' : 'nombre_almacen'
                },
                {
                    'data' : 'stock'
                },
                {
                    'data' : 'unidad_med'
                },
                {
                    'data' : 'seleccion'
                },
                {
                    'data' : 'cantidad'
                },
                {
                    'data' : 'observacion'
                }
            ],
            "responsive": true, "lengthChange": false, "autoWidth": false,
            columnDefs: [
                { targets: [6], width: '100px' }
            ]
        });
})

function ArqueoCaja() {
    document.getElementById('ocultar_campos').classList.add('d-none');
    document.getElementById('campos_billetes').classList.remove('d-none');
    document.getElementById('campos_monedas').classList.remove('d-none');
    const camposSuma = [
        'b_200',
        'b_100',
        'b_50',
        'b_20',
        'b_10',
        'm_5',
        'm_2',
        'm_1',
        'm_050',
        'm_020',
        'm_010'
    ];

    document.getElementById('total_dinero').classList.remove('d-none');

    const factores = {
        'b_200': 200,
        'b_100': 100,
        'b_50': 50,
        'b_20': 20,
        'b_10': 10,
        'm_5': 5,
        'm_2': 2,
        'm_1': 1,
        'm_050': 0.50,
        'm_020': 0.20,
        'm_010': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_dinero').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });

    document.getElementById('btnAccion').textContent = 'Abrir Caja';
    $('#abrir_caja').modal('show');
}

function PrimerCorte(){
    const camposSuma = [
        'b_200c',
        'b_100c',
        'b_50c',
        'b_20c',
        'b_10c',
        'm_5c',
        'm_2c',
        'm_1c',
        'm_050c',
        'm_020c',
        'm_010c'
    ];

    const factores = {
        'b_200c': 200,
        'b_100c': 100,
        'b_50c': 50,
        'b_20c': 20,
        'b_10c': 10,
        'm_5c': 5,
        'm_2c': 2,
        'm_1c': 1,
        'm_050c': 0.50,
        'm_020c': 0.20,
        'm_010c': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_dinero_corte').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });

    $('#abrir_primer_corte').modal('show');
}

function UltimoCorte(){
    const camposSuma = [
        'b_200cc',
        'b_100cc',
        'b_50cc',
        'b_20cc',
        'b_10cc',
        'm_5cc',
        'm_2cc',
        'm_1cc',
        'm_050cc',
        'm_020cc',
        'm_010cc'
    ];

    const factores = {
        'b_200cc': 200,
        'b_100cc': 100,
        'b_50cc': 50,
        'b_20cc': 20,
        'b_10cc': 10,
        'm_5cc': 5,
        'm_2cc': 2,
        'm_1cc': 1,
        'm_050cc': 0.50,
        'm_020cc': 0.20,
        'm_010cc': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_ultimo_corte').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });

    $('#ultimoCorte').modal('show');
}

function abrirArqueoCaja(e){
    document.getElementById("title").innerHTML = "Arqueo de Caja";
    e.preventDefault();
    const url = base_url + "Arqueo/abrirArqueo/";
    const frm = document.getElementById("frmAbrirCaja");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            setTimeout(function() {
                window.location.reload();
            }, 1000);
            $('#abrir_caja').modal('hide');
        }
    }
}

function CrearPrimerCorte(e){
    e.preventDefault();
    const url = base_url + "Arqueo/primerCorte/";
    const frm = document.getElementById("frmPrimerCorte");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            setTimeout(function() {
                window.location.reload();
            }, 1000);
            $('#abrir_primer_corte').modal('hide');
        }
    }
}

function CerraCaja(e){
    e.preventDefault();
    const url = base_url + "Arqueo/cerrarCaja/";
    const frm = document.getElementById("frmUltimoCorte");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            alertas(res.msg, res.icono);
            $('#ultimoCorte').modal('hide');
            getStockAlmacen();
        }
    }
}

function VerificarStock(){
    Swal.fire({
        title: 'Estas seguro?',
        text: "Verificó el Stock Entregado",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
            const url = base_url + "Arqueo/VerificarStock/";
            const http = new XMLHttpRequest();
            http.open("GET", url, true);
            http.send();
            http.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    const res = JSON.parse(this.responseText);
                    alertas(res.msg, res.icono);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            }
        }
      })
}



function getStockAlmacen()
{
    $('#getStockAlmacen').modal('show');
}

function generarSalida(e) {
    e.preventDefault();
    if(document.getElementById("fecha_ingreso").value == ""){
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'Elementos faltantes'+"\n"+"(FECHA RETORNO)",
            showConfirmButton: false,
            timer: 3000
        });
        return; 
    }else{
        var productosSeleccionados = [];

        for (var i = 0; i < tblProductosStock.page.info().pages; i++) {
            tblProductosStock.page(i).draw('page');
            tblProductosStock.rows().every(function () {
                var rowData = this.data();
                var productId = rowData.id_producto;
                var checkbox = document.getElementById("product_select_" + productId);
                
                if (checkbox && checkbox.checked) {
                    var cantidadInput = document.getElementById("cant_stock_" + productId);
                    if (cantidadInput) {
                        var cantidad = cantidadInput.value;
                        var observacion = document.getElementById("product_observacion_"+productId).value;
                        var fecha_operacion = document.getElementById("fecha_ingreso").value;
                        productosSeleccionados.push({
                            id_producto: productId,
                            cantidad: cantidad,
                            observacion: observacion,
                            fecha_operacion: fecha_operacion
                        });
                    }
                }
            });
        }
        // Mostrar el modal de confirmación
        Swal.fire({
            title: '¿Está seguro?',
            html: 'El retorno se generará con la fecha: (' + document.getElementById("fecha_ingreso").value + ')<br>' +
                  'Productos seleccionados: ' + productosSeleccionados.length,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Generar'
        }).then((result) => {
            if (result.isConfirmed) {
                if (productosSeleccionados.length > 0) {
                    const url = base_url + "Arqueo/registrarSalida/";
                    const http = new XMLHttpRequest();
                    http.open("POST", url, true);
                    http.send(JSON.stringify(productosSeleccionados));
                    http.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            const res = JSON.parse(this.responseText);
                            alertas("Se generó la salida "+res['serie']+"-"+res['correlativo'], "success");
                            const pdfUrl = base_url + "Arqueo/generarPdfSalida/" + parseInt(res['correlativo']);
                            window.open(pdfUrl, '_blank');
                            var fechaInput = document.getElementById('fecha_ingreso');
                            var fechaActual = new Date();
                            var fechaFormateada = fechaActual.getFullYear() + '-' + ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ('0' + fechaActual.getDate()).slice(-2);
                            fechaInput.value = fechaFormateada;
                            tblProductosStock.ajax.reload();
                        }
                    }
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'warning',
                        title: 'Elementos faltantes'+"\n"+"(0 PRODUCTOS SELECCIONADOS)",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return; 
                }        
            }
        });
    }
}
function apertura(id)
{
    document.getElementById('ocultar_campos').classList.add('d-none');
    document.getElementById('campos_billetes').classList.remove('d-none');
    document.getElementById('campos_monedas').classList.remove('d-none');
    document.getElementById("title").innerHTML = "Editar Primer Corte";
    document.getElementById("btnAccion").innerHTML = "Guardar Cambios";
    const camposSuma = [
        'b_200',
        'b_100',
        'b_50',
        'b_20',
        'b_10',
        'm_5',
        'm_2',
        'm_1',
        'm_050',
        'm_020',
        'm_010'
    ];

    document.getElementById('total_dinero').classList.remove('d-none');

    const factores = {
        'b_200': 200,
        'b_100': 100,
        'b_50': 50,
        'b_20': 20,
        'b_10': 10,
        'm_5': 5,
        'm_2': 2,
        'm_1': 1,
        'm_050': 0.50,
        'm_020': 0.20,
        'm_010': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_dinero').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });
    const url = base_url + "Arqueo/editarApertura/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            const handleNullValue = (value) => {
                return value === null ? '' : value;
            };
            document.getElementById("id").value = res.id;
            document.getElementById("b_200").value = handleNullValue(res.b_200);
            document.getElementById("b_100").value = handleNullValue(res.b_100);
            document.getElementById("b_50").value = handleNullValue(res.b_50);
            document.getElementById("b_20").value = handleNullValue(res.b_20);
            document.getElementById("b_10").value = handleNullValue(res.b_10);
            document.getElementById("m_5").value = handleNullValue(res.m_5);
            document.getElementById("m_2").value = handleNullValue(res.m_2);
            document.getElementById("m_1").value = handleNullValue(res.m_1);
            document.getElementById("m_050").value = handleNullValue(res.m_050);
            document.getElementById("m_020").value = handleNullValue(res.m_020);
            document.getElementById("m_010").value = handleNullValue(res.m_010);
            monto_inicial = handleNullValue(res.monto_inicial);
            document.querySelector('#total_dinero').textContent = `TOTAL: S/. ${monto_inicial}`;
            $('#abrir_caja').modal('show');
        }
    }
}

function editarPrimerCorte(id)
{
    const camposSuma = [
        'b_200c',
        'b_100c',
        'b_50c',
        'b_20c',
        'b_10c',
        'm_5c',
        'm_2c',
        'm_1c',
        'm_050c',
        'm_020c',
        'm_010c'
    ];

    const factores = {
        'b_200c': 200,
        'b_100c': 100,
        'b_50c': 50,
        'b_20c': 20,
        'b_10c': 10,
        'm_5c': 5,
        'm_2c': 2,
        'm_1c': 1,
        'm_050c': 0.50,
        'm_020c': 0.20,
        'm_010c': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_dinero_corte').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });

    const url = base_url + "Arqueo/editarPrimerCorte/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            const handleNullValue = (value) => {
                return value === null ? '' : value;
            };
            document.getElementById("id_pcorte").value = res.id;
            document.getElementById("b_200c").value = handleNullValue(res.b_200);
            document.getElementById("b_100c").value = handleNullValue(res.b_100);
            document.getElementById("b_50c").value = handleNullValue(res.b_50);
            document.getElementById("b_20c").value = handleNullValue(res.b_20);
            document.getElementById("b_10c").value = handleNullValue(res.b_10);
            document.getElementById("m_5c").value = handleNullValue(res.m_5);
            document.getElementById("m_2c").value = handleNullValue(res.m_2);
            document.getElementById("m_1c").value = handleNullValue(res.m_1);
            document.getElementById("m_050c").value = handleNullValue(res.m_050);
            document.getElementById("m_020c").value = handleNullValue(res.m_020);
            document.getElementById("m_010c").value = handleNullValue(res.m_010);
            monto_total = handleNullValue(res.monto_total);
            document.querySelector('#total_dinero_corte').textContent = `TOTAL: S/. ${monto_total}`;
            $('#abrir_primer_corte').modal('show');
        }
    }
}

function editarUltimoCorte(id)
{
    const camposSuma = [
        'b_200cc',
        'b_100cc',
        'b_50cc',
        'b_20cc',
        'b_10cc',
        'm_5cc',
        'm_2cc',
        'm_1cc',
        'm_050cc',
        'm_020cc',
        'm_010cc'
    ];

    const factores = {
        'b_200cc': 200,
        'b_100cc': 100,
        'b_50cc': 50,
        'b_20cc': 20,
        'b_10cc': 10,
        'm_5cc': 5,
        'm_2cc': 2,
        'm_1cc': 1,
        'm_050cc': 0.50,
        'm_020cc': 0.20,
        'm_010cc': 0.10
    };

    let total = 0;
    
    function actualizarTotal() {
        total = 0;
        camposSuma.forEach(campo => {
            const campoInput = document.getElementById(campo);
            const valor = parseFloat(campoInput.value) || 0;
            total += valor * factores[campo];
        });
        document.querySelector('#total_ultimo_corte').textContent = `TOTAL: S/. ${total.toFixed(2)}`;
    }
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            actualizarTotal();
        });
    });
    
    camposSuma.forEach(campo => {
        const campoInput = document.getElementById(campo);
        campoInput.addEventListener('input', function () {
            if (campoInput.value === '') {
                actualizarTotal();
            }
        });
    });

    const url = base_url + "Arqueo/editarUltimoCorte/"+id;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            const handleNullValue = (value) => {
                return value === null ? '' : value;
            };
            document.getElementById("id_ucorte").value = res.id_arqueo;
            document.getElementById("b_200cc").value = handleNullValue(res.b_200);
            document.getElementById("b_100cc").value = handleNullValue(res.b_100);
            document.getElementById("b_50cc").value = handleNullValue(res.b_50);
            document.getElementById("b_20cc").value = handleNullValue(res.b_20);
            document.getElementById("b_10cc").value = handleNullValue(res.b_10);
            document.getElementById("m_5cc").value = handleNullValue(res.m_5);
            document.getElementById("m_2cc").value = handleNullValue(res.m_2);
            document.getElementById("m_1cc").value = handleNullValue(res.m_1);
            document.getElementById("m_050cc").value = handleNullValue(res.m_050);
            document.getElementById("m_020cc").value = handleNullValue(res.m_020);
            document.getElementById("m_010cc").value = handleNullValue(res.m_010);
            monto_total = handleNullValue(res.monto_total);
            document.querySelector('#total_ultimo_corte').textContent = `TOTAL: S/. ${monto_total}`;
            $('#ultimoCorte').modal('show');
        }
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('excelDetalladoButton').addEventListener('click', function () {
        var table = $('#tblProductosStock').DataTable();
        var cabeceras = [];
        $('#tblProductosStock thead th').each(function() {
            cabeceras.push($(this).text());
        });
        var contenido = [];
        for (var i = 0; i < table.page.info().pages; i++) {
            table.page(i).draw('page');
            $('#tblProductosStock tbody tr').each(function() {
                var fila = [];
                $(this).find('td').each(function() {
                    fila.push($(this).text());
                });
                contenido.push(fila);
            });
        }

        $('#titulo').val('PRODUCTOS RETORNO');
        $('#cabeceras').val(JSON.stringify(cabeceras));
        $('#contenido').val(JSON.stringify(contenido));

        document.getElementById('exportForm').action = base_url + "Exports/exportToExcel";
        document.getElementById('exportForm').submit();
    });
});