function generarPDF() {
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    const banco = document.getElementById('banco').value;
    const url = `${base_url}Bancos/pdfDetalladoBanco/?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&banco=${banco}`;
    window.open(url, '_blank');
}

function generarExcel() {
    const fechaDesde = document.getElementById('fecha_desde').value;
    const fechaHasta = document.getElementById('fecha_hasta').value;
    const banco = document.getElementById('banco').value;
    const url = `${base_url}Bancos/excelDetalladoBanco/?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&banco=${banco}`;
    window.open(url, '_blank');
}

function registrarIngresoBanco(){
    const url = base_url + "Bancos/RegistrarMovimiento/";
    const frm = document.getElementById("frmInfoBancoIgreso");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            console.log(this.responseText);
            if(res['icono'] == 'success'){
                const pdfUrl = base_url + "Bancos/generarPdfBancos/" + res['id_banco'];
                const ventana = window.open(pdfUrl, '_blank', 'width=300,height=800,toolbar=0,scrollbars=1,resizable=1');
                setTimeout(() => {
                    window.location.reload();
                }, 100);
                if (ventana) {
                    ventana.focus();
                } else {
                    alert('Su navegador está bloqueando ventanas emergentes. Por favor, habilite las ventanas emergentes para ver el PDF generado.');
                }
            }else{
                alertas(res['msg'], res['icono']);
            }
        } else {
            console.error("Error en la solicitud. Estado:", this.status);
        }
    }
}

function registrarSalidaBanco(){
    const url = base_url + "Bancos/RegistrarMovimiento/";
    const frm = document.getElementById("frmInfoBancoIgreso");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            const res = JSON.parse(this.responseText);
            if (typeof res.dato !== 'undefined' && res.dato !== null) {
                alertas(res.msg, res.icono);
            } else {
                alertas(res.msg, res.icono);
                frm.reset();
            }
        }
    }
}

function agregarDenomBilletes()
{
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
    $('#agregar_denominacion').modal('show');
}

document.addEventListener("DOMContentLoaded", function () {
    tblPedidosPagados = $("#tblMovimientoBancosGEN").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false
    });
  });


function buscarKardexBancos()
{
    const url = base_url + "Bancos/buscarKardexBanco/";
    const frm = document.getElementById("frmBuscarKardexBanco");
    const http = new XMLHttpRequest();
    http.open("POST", url, true);
    http.send(new FormData(frm));
    http.onreadystatechange = function(){
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            let html = '';
            let saldo = 0;
            res.forEach(row => {
                let total_saldo;
                if (saldo == 0) {
                    total_saldo = parseFloat(row.ingresos) - parseFloat(row.salidas);
                } else {
                    total_saldo = parseFloat(saldo) + (parseFloat(row.ingresos) - parseFloat(row.salidas));
                }
                total_saldo = total_saldo.toFixed(2);
                const nombreConSeparador = `${row.nombre} | ${row.cuenta} | ${row.cuenta_contable}`;
        
                html += `<tr>
                    <td>${row.fecha}</td>
                    <td>${row.id}</td>
                    <td>${row.nombre_usuario}</td>
                    <td>${row.descripcion}</td>
                    <td>${row.operacion}</td>
                    <td>${nombreConSeparador}</td>
                    <td>${row.numero_operacion}</td>
                    <td>${row.ingresos}</td>
                    <td>${row.salidas}</td>
                    <td>${total_saldo}</td>
                </tr>`;
                saldo = total_saldo;
            });
            if ($.fn.DataTable.isDataTable('#tblMovimientoBancosGEN')) {
                $('#tblMovimientoBancosGEN').DataTable().destroy();
            }
            document.querySelector('#tblMovimientoBancos').innerHTML = html;
            $('#tblMovimientoBancosGEN').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false
            });
        }
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
                document.getElementById("nombre").value = res.apellidoPaterno+" "+res.apellidoMaterno+" "+res.nombres;
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